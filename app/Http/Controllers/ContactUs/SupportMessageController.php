<?php

namespace App\Http\Controllers\ContactUs;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class SupportMessageController extends Controller
{
    public function create()
{
    return view('Home.contact'); 
}


    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'category' => 'required|in:technical,general,other',
            'file' => 'nullable|file|max:2048',
        ]);

        // Protection contre les injections XSS
        $validated['message'] = strip_tags($validated['message']);
        $validated['name'] = strip_tags($validated['name']);

        // Gestion du fichier
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('support_files', 'public');
        }

        // Enregistrement en base de données
        SupportMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'category' => $validated['category'],
            'file_path' => $filePath,
        ]);

        // Envoi d'un email de confirmation
        Mail::raw("Votre demande de support a bien été reçue.", function ($message) use ($validated) {
            $message->to($validated['email'])->subject('Confirmation de votre demande');
        });

        return redirect()->route('contactus')->with('success', 'Votre message a été envoyé.');
    }
}

