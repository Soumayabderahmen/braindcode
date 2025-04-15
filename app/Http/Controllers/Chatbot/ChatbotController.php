<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatMessage; // Stocker les messages si utilisateur authentifié
use Illuminate\Support\Facades\Auth;
//chabotcontroller.php

class ChatbotController extends Controller
{
    /**
     * Gérer l'envoi d'un message au chatbot
     */
//     public function sendMessage(Request $request)
// {
//     $request->validate([
//         'message' => 'required|string|max:1000',
//     ]);

//     $userMessage = $request->input('message');
//     $user = Auth::user();
//     $source = 'ollama';

//     if ($user) {
//         // Enregistrer le message utilisateur
//         ChatMessage::create([
//             'user_id' => $user->id,
//             'message' => $userMessage,
//             'sender' => 'user',
//         ]);
//     }

//     try {
//         $response = Http::timeout(300)->post(env('CHATBOT_API_URL'), [
//             'sender' => $user ? "user_{$user->id}" : "guest",
//             'message' => $userMessage,
//         ]);

//         if ($response->successful()) {
//             $botReply = $response->json();
//             $botMessage = $botReply['reply'] ?? "Je n'ai pas compris.";

//             // ✅ Enregistrer la réponse du bot
//             if ($user) {
//                 ChatMessage::create([
//                     'user_id' => $user->id,
//                     'message' => $botMessage,
//                     'sender' => 'bot',
//                 ]);
//             }
//         } else {
//             $botMessage = "Erreur du chatbot.";
//         }
//     } catch (\Exception $e) {
//         $botMessage = "Chatbot hors ligne.";
//     }

//     return response()->json([
//         'reply' => $botMessage,
//         'source' => $source,
//     ]);
// }
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
        ]);

        ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->input('botMessage'),
            'sender' => 'bot',
        ]);
    }

    return response()->json(['status' => 'saved']);
}

    public function getHistory()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['history' => []]); // Pas connecté → pas d’historique
        }
    
        $twoWeeksAgo = now()->subWeeks(2);
    
        $messages = ChatMessage::where('user_id', $user->id)
            ->where('created_at', '>=', $twoWeeksAgo)
            ->orderBy('created_at', 'asc')
            ->get(['message', 'sender', 'created_at']); // filtrer les colonnes utiles
    
        return response()->json(['history' => $messages]);
    }

    
}
