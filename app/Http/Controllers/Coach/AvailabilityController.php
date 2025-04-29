<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Disponibilite;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AvailabilityController extends Controller


{

    public function index()
    {
        $coach = Auth::user()->coach;

        $availabilities = Disponibilite::where('coach_id', $coach->id)->get();
    
        return view('Disponibilite.index', [
            'availabilities' => $availabilities,
            'coachId' => $coach->id, 
        ]);
    }
    

    
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'coach_id' => 'required|exists:coach,id',
            'day_of_week' => 'nullable|string',
            'date' => 'nullable|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'statut' => 'required|in:available,unavailable',
            'honoraire' => 'nullable|numeric', // Validation du champ honoraire
        ]);
    
        $coach = Coach::findOrFail($request->coach_id); 
        if (Auth::user()->id !== $coach->user_id) {
            return back()->withErrors(['error' => 'Accès non autorisé.']);
        }
    
        // Vérifier si le créneau existe déjà pour ce coach
        $existingAvailability = Disponibilite::where('coach_id', $coach->id)
            ->where('date', $request->date)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->exists();
    
        if ($existingAvailability) {
            return back()->withErrors(['error' => 'Ce créneau est déjà enregistré.']);
        }
    
        // Créer la disponibilité
        Disponibilite::create([
            'coach_id' => $coach->id,
            'day_of_week' => $request->day_of_week,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'statut' => $request->statut,
            'honoraire' => $request->honoraire, // Ajouter le champ honoraire
        ]);
    
        return back()->with('success', 'Disponibilité est ajoutée avec succès.');
    }
    

public function updateTimes(Request $request, $id)
{
    $availability = Disponibilite::findOrFail($id);

    // Vérification d'accès
    if (Auth::user()->coach->id !== $availability->coach_id) {
        return back()->withErrors(['error' => 'Accès non autorisé']);
    }

    // Nettoyage et préparation des données
    $cleanedData = [
        'date' => $request->date,
        'start_time' => $request->start_time ? $this->formatTime($request->start_time) : null,
        'end_time' => $request->end_time ? $this->formatTime($request->end_time) : null,
        'day_of_week' => $request->date ? $this->getDayOfWeek($request->date) : null,
        'honoraire' => $request->honoraire ,
    ];

    // Validation
    $validator = Validator::make($cleanedData, [
        'date' => 'required|date',
        'day_of_week' => 'required|string|max:20',
        'start_time' => 'required|date_format:H:i:s',
        'end_time' => 'required|date_format:H:i:s|after:start_time',
         'honoraire' => 'nullable|numeric|min:0'
    ], [
        'date.required' => 'La date est obligatoire',
        'start_time.required' => 'L\'heure de début est obligatoire',
        'start_time.date_format' => 'Le format de l\'heure doit être HH:MM',
        'end_time.required' => 'L\'heure de fin est obligatoire',
        'end_time.date_format' => 'Le format de l\'heure doit être HH:MM',
        'end_time.after' => 'L\'heure de fin doit être après l\'heure de début',
        'honoraire.numeric' => 'L\'honoraire doit être un nombre',
        'honoraire.min' => 'L\'honoraire doit être supérieur ou égal à 0'
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    $availability->update($cleanedData);
    
    return response()->json(['success' => true, 'message' => 'Disponibilité mise à jour avec succès.']);
    
}

// Ajoutez ces méthodes dans votre contrôleur
protected function formatTime($time)
{
    // Format HH:MM ou HH:MM:SS → HH:MM:00
    if (strlen($time) === 5) {
        return $time . ':00';
    }
    if (strlen($time) === 8) {
        return substr($time, 0, 5) . '00';
    }
    return null;
}

protected function getDayOfWeek($date)
{
    $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    return $days[(new DateTime($date))->format('w')];
}

   


public function updateStatus(Request $request, $id)
{
    $availability = Disponibilite::findOrFail($id);

    // Vérifier si l'utilisateur est bien le coach concerné
    if (Auth::user()->coach->id !== $availability->coach_id) {
        return response()->json(['error' => 'Accès non autorisé'], 403);
    }

    // Validation uniquement pour le statut
    $request->validate([
        'statut' => 'required|in:available,unavailable',
    ]);

    // Mise à jour du statut uniquement
    $availability->update([
        'statut' => $request->statut,
    ]);

    return response()->json(['success' => true, 'message' => 'Statut mis à jour avec succès.']);
}
    /**
     * Supprimer une disponibilité
     */
    public function destroy($id)
    {
        $availability = Disponibilite::findOrFail($id);

        // Vérifier si l'utilisateur est bien le coach concerné
        if (Auth::user()->coach->id !== $availability->coach_id) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $availability->delete();

        return response()->json(['success' => true, 'message' => 'Disponibilité supprimée avec succès.']);    }

    public function FullCalandry()
{
    
    $availabilities = Auth::user()->coach->availabilities()
        ->select('id', 'date', 'start_time', 'end_time', 'statut')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
                'statut' => $item->statut
            ];
        });

    return Inertia::render('Coach/Calandry', [
        'availabilities' => $availabilities,
        'coachId' => Auth::user()->coach->id
    ]);
}

public function calendarEvents(Request $request)
{
    $events = Auth::user()->coach->availabilities()
        ->select('id', 'date', 'start_time', 'end_time', 'statut')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->statut === 'available' ? 'Disponible' : 'Indisponible',
                'start' => $item->date . 'T' . $item->start_time,
                'end' => $item->date . 'T' . $item->end_time,
                'color' => $item->statut === 'available' ? '#28a745' : '#dc3545'
            ];
        });

    return response()->json($events);
}
}
