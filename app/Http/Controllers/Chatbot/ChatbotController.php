<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatMessage; // Stocker les messages si utilisateur authentifié
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Gérer l'envoi d'un message au chatbot
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $user = Auth::user();

        // Vérifier si l'utilisateur est authentifié
        if ($user) {
            //  Stocker le message en base de données (2 semaines max)
            ChatMessage::create([
                'user_id' => $user->id,
                'message' => $userMessage,
                'sender' => 'user',
            ]);
        }

        // Envoyer le message à l'API FastAPI/Flask qui communique avec Rasa
        $response = Http::post(env('CHATBOT_API_URL', 'http://localhost:5005/webhooks/rest/webhook'), [
            'sender' => $user ? "user_{$user->id}" : "guest",
            'message' => $userMessage
        ]);

        //  Vérifier si la réponse est correcte
        if ($response->successful()) {
            $botReply = $response->json();
            $botMessage = $botReply[0]['text'] ?? "Je n'ai pas compris.";

            if ($user) {
                ChatMessage::create([
                    'user_id' => $user->id,
                    'message' => $botMessage,
                    'sender' => 'bot',
                ]);
            }

            return response()->json(['reply' => $botMessage]);
        }

        return response()->json(['reply' => 'Erreur de communication avec le chatbot.'], 500);
    }
}
