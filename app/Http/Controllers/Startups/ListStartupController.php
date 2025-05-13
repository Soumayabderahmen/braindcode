<?php

namespace App\Http\Controllers\Startups;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Disponibilite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function ListMembres(){
        return Inertia::render('Startups/DashboardStartup',[
            'membres' => User::where('role', 'startup')->get(),

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

    public function FullCalandryStartup()
    {
        // Récupérer les utilisateurs qui sont coachs avec leurs coachs et disponibilités
        $users = User::where('role', 'coach')
            ->with('coach.availabilities') // charge la relation coach et les disponibilités
            ->get();
    
        // Vérifier si des coachs existent
        if ($users->isEmpty()) {
            return redirect()->route('home')->with('error', 'No coaches found');
        }
    
        // Préparer la liste des coachs avec infos nécessaires
        $coachs = $users->map(function ($user) {
            return [
                'coach_id' => $user->coach->id,       // coachs.id
                'user_id' => $user->id,               // users.id
                'name' => $user->name,                // nom de l'utilisateur
                'specialty' => $user->specialty ?? null, // si tu ajoutes "specialty" dans la table coachs
            ];
        });
    
        // Préparer les disponibilités
        $availabilities = $users->flatMap(function ($user) {
            return $user->coach->availabilities->map(function ($item) use ($user) {
                return [
                    'coach_id' => $user->coach->id,
                    'coach_name' => $user->name,
                    'id' => $item->id,
                    'date' => $item->date,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                    'statut' => $item->statut,
                    'title' => $item->titre,
                ];
            });
        });
    
        return view('Calendrier.index', [
            'availabilities' => $availabilities,
            'coachs' => $coachs,
        ]);
    }
    


}