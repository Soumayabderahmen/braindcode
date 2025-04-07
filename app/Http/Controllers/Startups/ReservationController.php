<?php

namespace App\Http\Controllers\Startups;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Disponibilite;
use App\Models\Reservation;
use App\Models\Startup;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    
        return Inertia::render('Startups/ReservationCoach', [
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
    Reservation::create([
        'coach_id'      => $availability->coach_id,
        'startup_id'    => $startup->id,
        'meeting_time'  => $validated['selected_time'],
        'duration'      => $validated['duration'],
        'total'         => $validated['total'],
        'message'       => $validated['message'] ?? '',
        'statut'        => 'en attente', // valeur par défaut
    ]);

    return redirect()->route('reservations.index')->with('success', 'Réservation enregistrée avec succès.');

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
