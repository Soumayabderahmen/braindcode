<?php

namespace App\Http\Controllers\Startups;
use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class ListStartupController extends Controller
{
    public function List(){
        $startups = User::where('role', 'startup')
        ->whereHas('startup', function ($query) {
            $query->where('statut', 'active');  
        })
        ->with('startup')->get();
        return Inertia::render('Startups/ListStartup', [
                'startups' => $startups
            ]);
     
    }
    public function profile($id) {
        // Récupère le coach spécifique par son ID
        $startup = User::where('role', 'startup')
        ->where('id', $id)
        ->with('startup') // Charge la relation coach
        ->first();
        // Si le coach n'existe pas, tu peux rediriger vers une page d'erreur ou afficher un message
        if (!$startup) {
            return redirect()->route('home')->with('error', 'startup not found');
        }
    
        // Renvoie la vue avec les données du coach spécifique
        return Inertia::render('Startups/ProfileStartup', [
            'startup' => $startup
        ]);
    }
}