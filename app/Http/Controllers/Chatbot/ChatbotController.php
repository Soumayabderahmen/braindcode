<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatbotReaction;

use App\Models\ChatMessage; // Stocker les messages si utilisateur authentifié
use Illuminate\Support\Facades\Auth;
//chabotcontroller.php

class ChatbotController extends Controller
{
    /**
     * Gérer l'envoi d'un message au chatbot
     */

public function saveHistory(Request $request)
{
    $request->validate([
        'userMessage' => 'required|string|max:1000',
        'botMessage' => 'required|string|max:2000',
    ]);

    $user = Auth::user();

    if ($user) {
        ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->input('userMessage'),
            'sender' => 'user',
            //'intent' => $request-> null
        ]);

        ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->input('botMessage'),
            'sender' => 'bot',
            'intent' => $request->input('intent') ?? null,
        ]);
    }

    return response()->json(['status' => 'saved']);
}

public function getHistory()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['history' => []]);
    }

    $twoWeeksAgo = now()->subWeeks(2);

    // Charger tous les messages de l'utilisateur
    $messages = ChatMessage::where('user_id', $user->id)
        ->where('created_at', '>=', $twoWeeksAgo)
        ->orderBy('created_at', 'asc')
        ->get();

    // Charger toutes les réactions de l'utilisateur
    $reactions = ChatbotReaction::where('user_id', $user->id)->get();

    // Fusionner les réactions avec les messages
    $history = $messages->map(function ($msg) use ($reactions) {
        $reaction = $reactions->firstWhere('message', $msg->message);

        return [
            'message' => $msg->message,
            'sender' => $msg->sender,
            'created_at' => $msg->created_at,
            'reaction' => $reaction ? $reaction->reaction : null, // ✅ Ajout de l'emoji
        ];
    });

    return response()->json(['history' => $history]);
}
    
}
