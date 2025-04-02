<?php

namespace App\Http\Controllers\Coach;
use App\Http\Controllers\Controller;

use App\Models\User;
use Inertia\Inertia;

class CoachProfileController extends Controller
{
    public function profile($id) {
        // Récupère le coach spécifique par son ID
        $coach = User::where('role', 'coach')
        ->where('id', $id)
        ->with('coach') // Charge la relation coach
        ->first();
        // Si le coach n'existe pas, tu peux rediriger vers une page d'erreur ou afficher un message
        if (!$coach) {
            return redirect()->route('home')->with('error', 'Coach not found');
        }
    
        // Renvoie la vue avec les données du coach spécifique
        return Inertia::render('Coach/profileCoach', [
            'coach' => $coach
        ]);
    }
public function List(){
    $coachs = User::where('role', 'coach')
    ->whereHas('coach', function ($query) {
        $query->where('statut', 'active');  
    })
    ->with('coach')->get();
    return Inertia::render('Coach/ListCoach', [
            'coachs' => $coachs
        ]);
 
}
}
