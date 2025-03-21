<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Vérifie si l'utilisateur est authentifié et que son rôle correspond
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            //return redirect()->route('dashboard')->with('error', 'Accès interdit.');
            abort(403, 'OOPS! Vous n\'avez pas accès à cette page.');
        }

        return $next($request);
    }
}

