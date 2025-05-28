<?php

namespace App\Http\Controllers\Coach;
use App\Http\Controllers\Controller;
use App\Models\Disponibilite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoachProfileController extends Controller
{
    public function profile($id)
    {
        // Récupère le coach spécifique par son ID avec sa relation
        $coach = User::where('role', 'coach')
            ->where('id', $id)
            ->with(['coach.availabilities']) // relation "coach" (détails supplémentaires)
            ->first();
    
        if (!$coach) {
            return redirect()->route('home')->with('error', 'Coach not found');
        }

        // Récupère les disponibilités pour CE coach
        $availabilities = $coach->coach->availabilities ?? collect();
        // Renvoie les données à la vue Inertia
        return view('Startup.ProfileCoach', [
            'coach' => $coach,

            'availabilities' => $availabilities,
            'coachId' => $coach->id,
        ]);
    }
    
public function List(){
    $coachs = User::where('role', 'coach')
   
    ->with('coach')->get();
    return view('Startup.ListCoach', [
            'coachs' => $coachs
        ]);
 
}
}
