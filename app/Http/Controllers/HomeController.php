<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function coach()
    {
        return view('Home.coach');
    }
public function startup()
{
    $aiFeatures = [
        ['title' => 'Assistance 24/7', 'description' => 'Réponses disponibles à tout moment, jour et nuit.'],
        ['title' => 'Apprentissage adaptatif', 'description' => 'L’IA s’adapte à votre niveau et vos besoins.'],
        ['title' => 'Ressources intelligentes', 'description' => 'Des outils pertinents sélectionnés pour vous.'],
        ['title' => 'Suivi de progression détaillé', 'description' => 'Visualisez vos performances et progrès.'],
    ];

    return view('Home.startup', compact('aiFeatures'));
}



    public function investisseur()
    {
        return view('Home.investisseur');
    }
    
    public function equipe()
    {
        return view('Home.equipe');
    }
    public function forum()
    {
        return view('Home.forum');
    }
    public function startinc()
    {
        return view('Home.startup-incube');
    }
    public function formation()
    {
        return view('Home.formation');
    }
    public function resources()
    {
        return view('Home.ressources');
    }
    public function agentia()
    {
        return view('Home.agentia');
    }
    public function agentia2()
    {
    return view('Home.agentia2');
    }

        public function tuto1()
    {
        return view('Home.tuto1');
    }
    public function tuto2()
    {
        return view('Home.tuto2');
    }
    public function tuto3()
    {
        return view('Home.tuto3');
    }
  
    // public function startupentrepreneuriat()
    // {
    //     return view('startupentrepreneuriat');
    // }
    // public function formationapprentissage()
    // {
    //     return view('formationapprentissage');
    // }
    // public function investissementfinancement()
    // {
    //     return view('investissementfinancement');
    // }
    // public function outilstechnologies()
    // {
    //     return view('outilstechnologies');
    // }
    // public function intelligenceartificielle()
    // {
    //     return view('intelligenceartificielle');
    // }
    // public function evenementsopportunites()
    // {
    //     return view('evenementsopportunites');
    // }
    
  
    
    // public function startupentrepreneuriat2()
    // {
    //     return view('startupentrepreneuriat2');
    // }
    // public function formationapprentissage2()
    // {
    //     return view('formationapprentissage2');
    // }
    // public function investissementfinancement2()
    // {
    //     return view('investissementfinancement2');
    // }
    // public function outilstechnologies2()
    // {
    //     return view('outilstechnologies2');
    // }
    // public function intelligenceartificielle2()
    // {
    //     return view('intelligenceartificielle2');
    // }
    // public function evenementsopportunites2()
    // {
    //     return view('evenementsopportunites2');
    // }
    // public function agc()
    // {
    //     return view('agc');
    // }
    // public function agc2()
    // {
    //     return view('agc2');
    // }
   
    
}
