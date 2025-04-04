<?php

namespace App\Http\Controllers\Startups;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Disponibilite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validation simple des paramètres (optionnel mais recommandé)
        $request->validate([
            'coach_id' => 'required|integer|exists:coach,id',
            'date' => 'required|date',
            'availability_id' => 'required|integer|exists:disponibilite,id',
        ]);
    
        $coachId = $request->query('coach_id');
        $date = $request->query('date');
        $availabilityId = $request->query('availability_id');
    
        // Vérification que la disponibilité correspond bien au coach et à la date
        $availability = Disponibilite::where('id', $availabilityId)
            ->where('coach_id', $coachId)
            ->where('date', $date)
            ->firstOrFail();
    
        // Récupération du coach
        $coach = Coach::findOrFail($coachId);
    
        // Rendu Inertia avec les données
        return Inertia::render('Startups/ReservationCoach', [
            'coach' => $coach,
            'availability' => $availability,
            'date' => $date,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
