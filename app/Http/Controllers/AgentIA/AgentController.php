<?php

namespace App\Http\Controllers\AgentIA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgentController extends Controller
{



public function agentia()
     {
         return view('agent-ia.index');
     }
     public function addAgentia()
     {
         return view('agent-ia.add');
     }
     public function detailsAgentia()
     {
         return view('agent-ia.details');
     }
}