<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatbotReaction;
use Carbon\Carbon;

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
        'userMessage' => 'required|string|max:3000',
        'botMessage' => 'required|string|max:3000',
    ]);

    $user = Auth::user();

    if ($user) {
        $responseTime = null;
    
        if ($request->has('startTime')) {
            $responseTime = now()->diffInSeconds(Carbon::createFromTimestampMs($request->startTime));
        }
    
        // Message de l'utilisateur
        ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->input('userMessage'),
            'sender' => 'user',
            'response_time' => null // l'utilisateur ne génère pas de temps de réponse
        ]);
    
        // Message du bot
        ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->input('botMessage'),
            'sender' => 'bot',
            'intent' => $request->input('intent') ?? null,
            //'response_time' => $responseTime, // temps de réponse du bot
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
