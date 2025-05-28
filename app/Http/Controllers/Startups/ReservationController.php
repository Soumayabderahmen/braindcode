<?php

namespace App\Http\Controllers\Startups;
use GuzzleHttp\Exception\RequestException;
use A4Anthony\WherebyLaravel\Facades\WherebyLaravel;
use GuzzleHttp\Client;

use App\Http\Controllers\Controller;
use App\Mail\StatutUpdatedMail;
use App\Models\Coach;
use App\Models\Disponibilite;
use App\Models\Reservation;
use App\Models\Startup;
use App\Models\User;
use App\Notifications\ReservationRequestNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
        public function indexAdmin()
        {
            $reservations = Reservation::with(['startup.user', 'coach.user'])->get();        
            return view('reservations.index', [
                'reservations' => $reservations,
            ]);
        }
   
        public function indexStartup()
        {
            $startup = Auth::user()->startup;

            $reservations = Reservation::where('startup_id', $startup->id)
            ->with(['startup.user', 'coach.user'])
            ->get();        
            return view('reservations.ReservationStartup', [
                'reservations' => $reservations,
            ]);
        }
        public function indexCoach()
        {
            $coach = Auth::user()->coach;

            $reservations = Reservation::where('coach_id', $coach->id)
            ->with(['startup.user', 'coach.user'])
            ->get();        
            return view('reservations.ReservationCoach', [
                'reservations' => $reservations,
            ]);
        }
    
    public function create(Request $request)
    {
        $coachId = $request->query('coach_id');
        $availabilityId = $request->query('availability_id');
    
        $coach = Coach::with('user')->findOrFail($coachId);
        $availability = Disponibilite::findOrFail($availabilityId);
        $honoraire= $availability->honoraire;
        // Génération des créneaux (slots)
        $start = Carbon::createFromFormat('H:i:s', $availability->start_time);
        $end = Carbon::createFromFormat('H:i:s', $availability->end_time);
    
        $slots = [];

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(30); // Durée de chaque slot
        }
    
        return view('reservations.AddReservation', [
            'coach' => $coach,
            'availability' => $availability,
            'slots' => $slots,
            'date' => $availability->date,
            'honoraire' => $honoraire,
            'startup_id' => Auth::user()->startup->id,
        ]);
    }
    
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'availability_id' => 'required|exists:disponibilite,id',
            'selected_time'   => 'required|date_format:H:i',
            'duration'        => 'required|integer|min:15',
            'honoraire'       => 'required|numeric|min:0',
            'total'           => 'required|numeric|min:0',
            'message'         => 'nullable|string',
        ]);

        $startup = Startup::findOrFail($request->startup_id); 
        if (Auth::user()->id !== $startup->user_id) {
            return back()->withErrors(['error' => 'Accès non autorisé.']);
        }
        $availability = Disponibilite::findOrFail($validated['availability_id']);

    // Création de la réservation
    $reservation = Reservation::create([
        'coach_id'      => $availability->coach_id,
        'startup_id'    => $startup->id,
        'meeting_time'  => $validated['selected_time'],
        'duration'      => $validated['duration'],
        'total'         => $validated['total'],
        'message'       => $validated['message'] ?? '',
        'statut'        => 'en attente', // valeur par défaut
        'disponibilite_id' => $availability->id,
    ]);
    $coachUser = $reservation->coach->user ?? null;
    if ($coachUser) {
        $coachUser->notify(new ReservationRequestNotification($reservation));
    }

    return response()->json([
        'success' => true,
        'message' => 'Réservation enregistrée avec succès.',
        'redirect' => route('startup.reservation.message'),
    ]);

    }

public function respond(Request $request, Reservation $reservation)
{
    $status = $request->input('statut');
    $reservation->statut = $status;

    if ($status === 'acceptée') {
        try {
            // Vérifie que la clé Whereby est définie
            $apiKey = config('services.whereby.key');
            if (!$apiKey) {
                throw new \Exception('Clé API Whereby manquante dans .env');
            }

            // Initialisation de Guzzle
            $client = new Client([
                'base_uri' => 'https://api.whereby.dev/v1/',
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            // Construction des dates
            $startDate = now()->addMinutes(1);
            $endDate = (clone $startDate)->addMinutes($reservation->duration ?? 30);

            // Payload envoyé à l'API
            $payload = [
                'startDate' => $startDate->toIso8601String(),
                'endDate'   => $endDate->toIso8601String(),
                'fields'    => ['hostRoomUrl'],
            ];

            // Appel à l’API Whereby
            $response = $client->post('meetings', ['json' => $payload]);
            $data = json_decode($response->getBody(), true);

            // Vérifie que le lien est bien reçu
            if (!empty($data['roomUrl'])) {
                $reservation->meeting_url = $data['roomUrl'];
            } else {
                Log::warning('[Whereby] Lien manquant dans la réponse : ' . json_encode($data));
            }

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $body = $response ? (string) $response->getBody() : 'Pas de réponse';

            Log::error('[Whereby Guzzle Error]', [
                'message' => $e->getMessage(),
                'response' => $body,
            ]);

            return response()->json([
                'error' => 'Erreur Whereby (RequestException)',
                'details' => $body,
            ], 500);

        } catch (\Exception $e) {
            Log::error('[Whereby Exception]', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'error' => 'Erreur Whereby générale',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    // Sauvegarder la réservation (avec ou sans lien)
    $reservation->save();

    // Envoi d'e-mail à la startup si nécessaire
    $startupUser = $reservation->startup->user ?? null;
    if ($startupUser && $startupUser->email) {
        Mail::to($startupUser->email)->send(new StatutUpdatedMail($reservation));
    }

    return response()->json(['success' => true]);
}


  public function notifications()
      {
          return view('coach.notification.index');
      }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
