<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
    
        $user = Auth::user();
    
        // 👉 Cas spécial : admin → redirection vers page Blade
        if ($user->role === 'admin') {
            return Inertia::location(route('admin.dashboard'));
        }
    
        // 👉 Pour les autres rôles, on garde Inertia (SPA)
        if ($this->isProfileComplete($user)) {
            return redirect()->route('dashboard'); // Inertia / SPA
        }
    
        return Inertia::location(route('dashboard'));
    }
public function isProfileComplete($user):bool{
    if ($user->role === 'coach') {
        return $user->coach && 
               $user->coach->diploma &&
               $user->coach->competence &&
               $user->coach->description &&
               $user->coach->profile_image &&
               $user->coach->cover_image;
    }

    if ($user->role === 'investisseur') {
        return $user->investisseur &&
               $user->investisseur->video_presentation &&
               $user->investisseur->description &&
               $user->investisseur->website_link &&
               $user->investisseur->social_links &&
               $user->investisseur->profile_image &&
               $user->investisseur->cover_image;
    }
    if ($user->role === 'startup') {
        return $user->startup &&
               $user->startup->logo_startup &&
               $user->startup->adresse &&
               $user->startup->NameCo_fondateur ;
              
    }
    return false; 
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
