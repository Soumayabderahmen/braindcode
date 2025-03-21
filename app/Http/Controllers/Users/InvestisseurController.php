<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class InvestisseurController extends Controller
{
    public function index()
    {
        // Vous pouvez ajouter des données spécifiques pour le tableau de bord de l'investisseur
        return Inertia::render('Users/DashboardInvestisseur');
    }
}
