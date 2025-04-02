<?php

namespace App\Http\Controllers\Investisseurs;
use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class ListInvestisseurController extends Controller
{
    public function List(){
        $investisseurs = User::where('role', 'investisseur')
        ->whereHas('investisseur', function ($query) {
            $query->where('statut', 'active');  
        })
        ->with('investisseur')
        ->get();
                return Inertia::render('Investisseur/ListInvestisseur', [
                'investisseurs' => $investisseurs
            ]);
     
    }
    public function profile($id) {
        // Récupère le coach spécifique par son ID
        $investisseur = User::where('role', 'investisseur')
        ->where('id', $id)
        ->with('investisseur') // Charge la relation coach
        ->first();
        // Si le coach n'existe pas, tu peux rediriger vers une page d'erreur ou afficher un message
        if (!$investisseur) {
            return redirect()->route('home')->with('error', 'investisseur not found');
        }
    
        // Renvoie la vue avec les données du coach spécifique
        return Inertia::render('Investisseur/ProfileInvestisseur', [
            'investisseur' => $investisseur
        ]);
    }
}