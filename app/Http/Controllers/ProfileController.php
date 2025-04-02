<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'userInfo' => Auth::user(),
            'role' => Auth::user()->role,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Validation dynamique basée sur le rôle
    $validationRules = $this->getValidationRules($user->role);
    
    // Valider les données de la requête
    $request->validate($validationRules);

    // Mettre à jour l'utilisateur
    if ($user instanceof User) {
        // Appel de la méthode update
        $user->update($request->only(['name', 'email', 'visibility', 'specialty', 'domain_name']));
    }
    // Mettre à jour les champs spécifiques au rôle
    switch ($user->role) {
        case 'coach':
            if ($request->hasFile('diploma')) {
                $validatedData['diploma'] = $request->file('diploma')->store('diplomas', 'public');
            }
            
            $coach = $user->coach;
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                if ($file->isValid()) {
                    // Stocker le fichier dans le répertoire 'investisseurs/profile_images' sous 'public'
                    $profileImagePath = $file->store('Coachs/profile_images', 'public');
                    // Enregistrer le chemin relatif dans la base de données
                    $coach->profile_image = $profileImagePath;
                } else {
                    return redirect()->back()->with('error', 'Le fichier d\'image de profil est invalide.');
                }
            }
        
            // Traitement de l'image de couverture
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                if ($file->isValid()) {
                    // Stocker le fichier dans le répertoire 'investisseurs/cover_images' sous 'public'
                    $coverImagePath = $file->store('Coachs/cover_images', 'public');
                    // Enregistrer le chemin relatif dans la base de données
                    $coach->cover_image = $coverImagePath;
                } else {
                    return redirect()->back()->with('error', 'Le fichier d\'image de couverture est invalide.');
                }
            }
            $coach->update($request->only([
                 'diploma', 'competence', 'description'           ]));
            break;

            case 'startup':
                $startup = $user->startup;
                if ($request->hasFile('logo_startup')) {
                    // Vérifie si le fichier existe
                    $file = $request->file('logo_startup');
                    
                    if ($file->isValid()) {
                        // Stocker le fichier dans le répertoire 'startups' sous 'public'
                        $logoPath = $file->store('startups', 'public');
                        
                        // Enregistrer le chemin relatif dans la base de données
                        $startup->logo_startup = $logoPath;  // Sauvegarder le chemin relatif
                    } else {
                        return redirect()->back()->with('error', 'Fichier invalide.');
                    }
                }
                $startup->update($request->only([
                     'adresse', 'NameCo_fondateur'    
                            ]));
                break;
                case 'investisseur':
                    $investisseur = $user->investisseur;
                
                    // Traitement de l'image de profil
                    if ($request->hasFile('profile_image')) {
                        $file = $request->file('profile_image');
                        if ($file->isValid()) {
                            // Stocker le fichier dans le répertoire 'investisseurs/profile_images' sous 'public'
                            $profileImagePath = $file->store('investisseurs/profile_images', 'public');
                            // Enregistrer le chemin relatif dans la base de données
                            $investisseur->profile_image = $profileImagePath;
                        } else {
                            return redirect()->back()->with('error', 'Le fichier d\'image de profil est invalide.');
                        }
                    }
                
                    // Traitement de l'image de couverture
                    if ($request->hasFile('cover_image')) {
                        $file = $request->file('cover_image');
                        if ($file->isValid()) {
                            // Stocker le fichier dans le répertoire 'investisseurs/cover_images' sous 'public'
                            $coverImagePath = $file->store('investisseurs/cover_images', 'public');
                            // Enregistrer le chemin relatif dans la base de données
                            $investisseur->cover_image = $coverImagePath;
                        } else {
                            return redirect()->back()->with('error', 'Le fichier d\'image de couverture est invalide.');
                        }
                    }
                
                    // Traitement de la vidéo de présentation
                    if ($request->hasFile('video_presentation')) {
                        $file = $request->file('video_presentation');
                        if ($file->isValid()) {
                            // Stocker le fichier vidéo dans le répertoire 'investisseurs/video_presentations' sous 'public'
                            $videoPath = $file->store('investisseurs/video_presentations', 'public');
                            // Enregistrer le chemin relatif dans la base de données
                            $investisseur->video_presentation = $videoPath;
                        } else {
                            return redirect()->back()->with('error', 'Le fichier vidéo est invalide.');
                        }
                    }
                
                    // Mettre à jour les autres champs
                    $investisseur->update($request->only([
                        'description', 'website_link', 'social_links'
                    ]));
                
                    break;
                
            ;
    }

    return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
}

/**
 * Get the validation rules based on the user's role.
 *
 * @param  string  $role
 * @return array
 */
private function getValidationRules(string $role): array
{
    switch ($role) {
        case 'coach':
            return [
                
                'diploma' => 'nullable|file|mimes:pdf|max:2048',
                'competence' => 'nullable|string',
                'description' => 'nullable|string',
                'profile_image' => 'nullable|image|max:1024',
                'cover_image' => 'nullable|image|max:1024',
            ];
        
        case 'investisseur':
            return [
                
                'video_presentation' => 'nullable|file|mimes:mp4,avi,mov,mkv|max:20480',
                'description' => 'nullable|string',
                'website_link' => 'nullable|url',
                'social_links' => 'nullable|url',
                'profile_image' => 'nullable|image|max:1024',
                'cover_image' => 'nullable|image|max:1024',
            ];
            case 'startup':
                return [
                    'logo_startup' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'adresse'=> 'nullable|string',
                    'NameCo_fondateur'=> 'nullable|string',
                    
                ];
        default:
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ];
    }
}

}
