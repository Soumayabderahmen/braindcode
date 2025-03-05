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
            $coach = $user->coach;
            $coach->update($request->only([
                'phone_number', 'diploma', 'competence', 'description', 'profile_image', 'cover_image', 'pdf_document'
            ]));
            break;
        case 'investisseur':
            $investisseur = $user->investisseur;
            $investisseur->update($request->only([
                'video_presentation', 'description', 'website_link', 'social_links', 'profile_image', 'cover_image'
            ]));
            break;
    }

    return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
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
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone_number' => 'nullable|string|max:20',
                'diploma' => 'nullable|string|max:255',
                'competence' => 'nullable|string',
                'description' => 'nullable|string',
                'profile_image' => 'nullable|image|max:1024',
                'cover_image' => 'nullable|image|max:1024',
                'pdf_document' => 'nullable|file|mimes:pdf|max:2048',
            ];
        
        case 'investisseur':
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'video_presentation' => 'nullable|file|mimes:mp4,avi,mov|max:20480',
                'description' => 'nullable|string',
                'website_link' => 'nullable|url',
                'social_links' => 'nullable|json',
                'profile_image' => 'nullable|image|max:1024',
                'cover_image' => 'nullable|image|max:1024',
            ];

        default:
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ];
    }
}

}
