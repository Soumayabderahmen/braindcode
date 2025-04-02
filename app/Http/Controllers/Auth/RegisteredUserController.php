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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\CoachRegistered;
use App\Mail\InvestisseurRegistered;
use App\Mail\StartupRegistered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
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
            'phone_number' => 'required|string|max:20', 

        ]);
        $statut = 'inactive';
       


        if ($request->role === 'investisseur') {
            $data['visibility'] = $request->visibility;
            $data['image'] = $request->file('image') ? $request->file('image')->store('investor_images') : null;
        }
        if ($request->role === 'startup') {
            $data['domain_name'] = $request->domain_name;
            $data['image'] = $request->file('image') ? $request->file('image')->store('startup_images') : null;

        }

        if ($request->role === 'coach') {
            $data['specialty'] = $request->specialty;
            $data['image'] = $request->file('image') ? $request->file('image')->store('coach_images') : null;
            $data['document'] = $request->hasFile('document') ? $request->file('document')->store('coach_documents', 'public') : null; // ✅ Correction ici
        }
       
        $user = User::create([
            'name' => $request->input('name'),  // Utiliser $request->input() pour obtenir le champ 'name'
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),  // Utiliser $request->input() pour le mot de passe
            'role' => $request->input('role'),  // Utiliser $request->input() pour le rôle
            'visibility' => $request->input('visibility') ?? null,  // Assurez-vous que 'visibility' est bien dans la requête
            'image' => $request->input('image') ?? null,  // Image, ou null si elle n'est pas présente
            'domain_name' => $request->input('domain_name') ?? null,  // Domain Name pour le startup
            'specialty' => $request->input('specialty') ?? null, 
            'statut' => $statut,
            'document' => $request->hasFile('document') ? $request->file('document')->store('coach_documents', 'public') : null, // Ajout du document dans la table User
            'phone_number' => $request->input('phone_number'),

        ]);
        if ($request->role === 'investisseur') {
            $investisseur = new Investisseur([
                'user_id' => $user->id,
               
            ]);
            $investisseur->save();
            $admin = User::where('role', 'admin')->first();

            if ($admin) {
                // ✅ Envoyer l'email à l'admin
                Mail::to($admin->email)->send(new InvestisseurRegistered($user));
            }
    
            return redirect()->route('activation.message');
        }
        if ($request->role === 'startup') {
            $startup = new Startup([
                'user_id' => $user->id,
                
            ]);
            $startup->save();
            $admin = User::where('role', 'admin')->first();

            if ($admin) {
                // ✅ Envoyer l'email à l'admin
                Mail::to($admin->email)->send(new StartupRegistered($user));
            }
    
            return redirect()->route('activation.message');
        }
        if ($request->role === 'coach') {
            $coach = new Coach([
                'user_id' => $user->id,
               'pdf_document'=>$data['document'],
            ]);
            $coach->save();

            $admin = User::where('role', 'admin')->first();

            if ($admin) {
                // ✅ Envoyer l'email à l'admin
                Mail::to($admin->email)->send(new CoachRegistered($user));
            }
    
            return redirect()->route('activation.message');
        }
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('profile.edit', absolute: false));
    }
}
