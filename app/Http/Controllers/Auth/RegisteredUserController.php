<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Investisseur;
use App\Models\Startup;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Redirige vers la sélection du rôle.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('register.select');
    }

    /**
     * Affiche le formulaire d'inscription pour une startup.
     */
    public function createStartup(): Response
    {
        return Inertia::render('Auth/Register', ['defaultRole' => 'startup']);
    }

    /**
     * Affiche le formulaire d'inscription pour un coach.
     */
    public function createCoach(): Response
    {
        return Inertia::render('Auth/Register', ['defaultRole' => 'coach']);
    }

    /**
     * Affiche le formulaire d'inscription pour un investisseur.
     */
    public function createInvestisseur(): Response
    {
        return Inertia::render('Auth/Register', ['defaultRole' => 'investisseur']);
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:startup,coach,investisseur',
            'document' => $request->role === 'coach' ? 'required|file|mimes:pdf|max:10240' : 'nullable',
            'statut' => 'required|in:active,inactive',
        ]);

        $statut = $request->role === 'coach' ? 'inactive' : 'active';

        // Préparer les données supplémentaires selon le rôle
        $data = [];

        if ($request->role === 'investisseur') {
            $data['visibility'] = $request->visibility;
            $data['image'] = $request->file('image')?->store('investor_images');
        }

        if ($request->role === 'startup') {
            $data['domain_name'] = $request->domain_name;
            $data['image'] = $request->file('image')?->store('startup_images');
        }

        if ($request->role === 'coach') {
            $data['specialty'] = $request->specialty;
            $data['image'] = $request->file('image')?->store('coach_images');
            $data['document'] = $request->file('document')?->store('coach_documents', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'visibility' => $data['visibility'] ?? null,
            'image' => $data['image'] ?? null,
            'domain_name' => $data['domain_name'] ?? null,
            'specialty' => $data['specialty'] ?? null,
            'statut' => $statut,
            'document' => $data['document'] ?? null,
        ]);

        match ($request->role) {
            'investisseur' => Investisseur::create(['user_id' => $user->id]),
            'startup' => Startup::create(['user_id' => $user->id]),
            'coach' => Coach::create([
                'user_id' => $user->id,
                'document' => $data['document'] ?? null
            ]),
        };

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Compte créé avec succès. Veuillez vous connecter.');
    }
}
