<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    // Affiche la liste des utilisateurs (sauf ceux ayant le rôle "admin")
    public function index()
    {
        $user = auth()->user();
        $users = User::where('role', '!=', 'admin')->get();

        return Inertia::render('Admin/AdminDashboard', [
            'users' => $users,
            'user' => $user,
        ]);
    }

    public function coachs()
    {
        $coachs = User::where('role', 'coach')->get();
        return response()->json($coachs);
    }

    public function investors()
    {
        $investors = User::where('role', 'investisseur')->get();
        return response()->json($investors);
    }

    public function startups()
    {
        $startups = User::where('role', 'startup')->get();
        return response()->json($startups);
    }
    
     /**
     * Approuver un utilisateur.
     */
    public function approve($id)
{
    $user = User::findOrFail($id);

    // Commutation de l'état "approved"
    $user->approved = !$user->approved;
    $user->save();

    // Retourner une réponse JSON (utile pour Vue 3)
    $message = $user->approved
        ? "Startup approuvée avec succès !"
        : "Startup désapprouvée avec succès !";

    return response()->json([
        'success' => true,
        'message' => $message,
    ]);
}

    
  
}