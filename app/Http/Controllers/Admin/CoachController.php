<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CoachActivated;
use App\Mail\InvestisseurActivated;
use App\Mail\StartupActivated;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CoachController extends Controller
{
    public function activateCoach($id)
    {
        try {
            $coach = User::where('id', $id)->where('role', 'coach')->firstOrFail();
    
            // Activer le coach
            $coach->statut = 'active';
            $coach->save();
    
            // Vérifier si l'email existe et envoyer l'email
            if ($coach->email) {
                $this->sendEmailCoach($coach);
            } else {
                Log::error("Aucun email trouvé pour le coach ID {$coach->id}");
            }
    
            return Redirect::route('admin.coaches')->with('success', 'Le coach a été activé avec succès.');
    
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'activation du coach : " . $e->getMessage());
            return Redirect::route('admin.coaches')->with('error', "Une erreur est survenue lors de l'activation du coach.");
        }
    }
    
    private function sendEmailCoach($coach)
    {
        try {
            Mail::to($coach->email)->send(new CoachActivated($coach));
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email : " . $e->getMessage());
            throw new \Exception('Erreur lors de l\'envoi de l\'email.');
        }
    }
    

    public function activateStartup($id)
    {
        try {
            $startup = User::where('id', $id)->where('role', 'startup')->firstOrFail();
    
            // Activer le startup
            $startup->statut = 'active';
            $startup->save();
    
            // Envoyer l'email
            if ($startup->email) {
                $this->sendEmail($startup);
            } else {
                Log::error("Aucun email trouvé pour la startup ID {$startup->id}");

            }
            return Redirect::route('admin.startups')->with('success', 'Le compte du  startup a été activé avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur lors de l'activation de la startup : " . $e->getMessage());
        }
        return Redirect::route('admin.startups')->with('success', 'Le compte du  startup a été activé avec succès.');

    }
    
    private function sendEmail($startup)
    {
        try {
            Mail::to($startup->email)->send(new StartupActivated($startup));
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email : " . $e->getMessage());
            // Vous pouvez aussi retourner une réponse d'erreur ici si vous souhaitez
            throw new \Exception('Erreur lors de l\'envoi de l\'email.');
        }
    }
    
   
        public function activateInvestisseur($id)
        {
            try {
                $investisseur = User::where('id', $id)->where('role', 'investisseur')->firstOrFail();
        
                // Activer l'investisseur
                $investisseur->statut = 'active';
                $investisseur->save();
        
                // Vérifier si l'email existe et envoyer l'email
                if ($investisseur->email) {
                    $this->sendEmailInvestisseur($investisseur);
                } else {
                    Log::error("Aucun email trouvé pour l'investisseur ID {$investisseur->id}");
                    return response()->json(['error' => "Aucun email trouvé pour cet investisseur."], 400);  // Code HTTP 400 pour erreur
                }
        
                return Redirect::route('admin.investisseurs')->with('success', 'Le compte de l\'investisseur a été activé avec succès.');
        
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'activation de l'investisseur : " . $e->getMessage());
                return Redirect::route('admin.investisseurs')->with('error', "Une erreur est survenue lors de l'activation de l'investisseur.");
            }
        }
        
        private function sendEmailInvestisseur($investisseur)
        {
            try {
                Mail::to($investisseur->email)->send(new InvestisseurActivated($investisseur));
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi de l'email : " . $e->getMessage());
                throw new \Exception('Erreur lors de l\'envoi de l\'email.');
            }
        }
        
    public function index()
{
    return Inertia::render('Admin/ActivateCoach', [
        'coaches' => User::where('role', 'coach')->get()
    ]);
}
public function Dashboard()
    {
        $users = User::all();
        $reservations = Reservation::all();

        return Inertia::render('Admin/Dashboard', [
            'users' => $users,
            'reservations' => $reservations,

        ]);
       
    }

    public function startup()
    {
        $startups = User::where('role', 'startup')->get();
        return Inertia::render('Admin/startup', [
            'startups' => $startups
        ]);
       
    }

    public function investisseurs()
    {
        $investisseurs = User::where('role', 'investisseur')->get();
        return Inertia::render('Admin/investisseur', [
            'investisseurs' => $investisseurs
        ]);
       
    }
}
