<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatbotReaction;

class ChatbotReactionController extends Controller
{
    /**
     * Enregistre ou supprime une réaction d'un utilisateur à un message du bot.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'reaction' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Supprimer toute réaction existante sur ce message par ce user
        ChatbotReaction::where('user_id', $user->id)
            ->where('message', $request->message)
            ->delete();

        // Si réaction non vide → enregistrer
        if ($request->reaction) {
            ChatbotReaction::create([
                'user_id' => $user->id,
                'message' => $request->message,
                'reaction' => $request->reaction,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
