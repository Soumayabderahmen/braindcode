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
    // Récupérer tous les coachs avec leurs disponibilités
    $coachs = User::where('role', 'coach')
        ->with('coach.availabilities') // Récupère la relation 'coach' et les 'availabilities' associées
        ->get();
    
    // Vérifier si des coachs existent
    if ($coachs->isEmpty()) {
        return redirect()->route('home')->with('error', 'No coaches found');
    }

    // Préparer les disponibilités des coachs
    $availabilities = $coachs->flatMap(function ($coach) {
        return $coach->coach->availabilities->map(function ($item) use ($coach) {
            return [
                'coach_name' => $coach->name,  // Ajout du nom du coach
                'coach_id' => $coach->id,
                'id' => $item->id,
                'date' => $item->date,
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
                'statut' => $item->statut,
            ];
        });
    });

    // Renvoie les données à la vue Inertia
    return Inertia::render('Startups/Calander', [
        'availabilities' => $availabilities,
        'coachs' => $coachs,  // Passer la liste des coachs à la vue
    ]);
}

public function create(Request $request)
{
    // 1. Validation basique
    $validated = $request->validate([
        'coach_id' => 'required|exists:coach,id',
        'date' => 'required|date',
        'availability_id' => 'required|integer|exists:disponibilite,id'
    ]);

    // 2. Récupération des données
    $coach = Coach::findOrFail($validated['coach_id']);
    $availability = Disponibilite::findOrFail($validated['availability_id']);

    // 3. Vérification que la disponibilité est libre
    if ($availability->status !== 'available') {
        return back()->with('error', 'Ce créneau n\'est plus disponible');
    }

    // 4. Retour de la vue Inertia SANS redirection
    return inertia('Reservations/Create', [
        'coach' => $coach,
        'availability' => $availability,
        'date' => $validated['date']
    ]);
}

}