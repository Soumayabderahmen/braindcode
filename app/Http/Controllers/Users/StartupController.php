<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class StartupController extends Controller
{
    public function index()
    {
        // Vous pouvez ajouter des données spécifiques pour le tableau de bord de la startup
        return Inertia::render('Users/DashboardStarup');
    }
}
