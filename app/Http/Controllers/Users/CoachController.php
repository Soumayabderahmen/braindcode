<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class CoachController extends Controller
{
    public function index()
    {
        // Vous pouvez ajouter des données spécifiques pour le tableau de bord du coach
        return Inertia::render('Users/DashboardCoach');
    }
}
