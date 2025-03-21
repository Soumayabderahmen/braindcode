<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté et qu'il a le rôle "admin"
        if (!$request->user() || $request->user()->role !== 'admin') {
            //return redirect()->route('dashboard')->with('error', 'Accès réservé aux administrateurs.');
            abort(403, 'OOPS! Vous n\'êtes pas un administrateur.');
        }

        return $next($request); // Continue l'exécution normale si l'utilisateur est admin
    }
}

