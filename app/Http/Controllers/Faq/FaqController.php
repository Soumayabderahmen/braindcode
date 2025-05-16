<?php

namespace App\Http\Controllers\Faq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Faq;
use Inertia\Inertia;
class FaqController extends Controller
{
    // Affichage de la page Vue (frontend)
    public function index()
    {
        return view('Home.faq');
    }

    // Appel API pour récupérer les données dynamiques
    public function list()
    {
        return Faq::where('is_active', true)->orderBy('id')->get();
    }
}




