<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatbotReaction;
use Carbon\Carbon;
use App\Models\ChatSession;

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
             'startTime' => 'nullable|numeric',
         ]);
     
         $user = Auth::user();
         $responseTime = null;
     
         if ($request->filled('startTime') && is_numeric($request->startTime)) {
            try {
                $responseTime = now()->diffInSeconds(Carbon::createFromTimestampMs((int) $request->startTime));
            } catch (\Exception $e) {
                \Log::error('Erreur calcul responseTime', ['startTime' => $request->startTime, 'error' => $e->getMessage()]);
                $responseTime = null;
            }
        }
        
     
         if ($user) {
            \Log::info('SAVE CHAT', [
                'user' => $user ? $user->id : null,
                'session_id' => $request->header('X-Session-ID'),
                'userMessage' => $request->input('userMessage'),
                'botMessage' => $request->input('botMessage'),
                'responseTime' => round($responseTime, 4)
            ]);
            
             // Utilisateur connecté
             ChatMessage::create([
                 'user_id' => $user->id,
                 'message' => $request->input('userMessage'),
                 'sender' => 'user',
                 'response_time' => null
             ]);
     
             ChatMessage::create([
                 'user_id' => $user->id,
                 'message' => $request->input('botMessage'),
                 'sender' => 'bot',
                 'intent' => $request->input('intent') ?? null,
                 'response_time' => round($responseTime, 4)
             ]);
         } else {
             // Utilisateur non connecté
             $sessionId = $request->header('X-Session-ID');
             if (!$sessionId) {
                 return response()->json(['error' => 'Session ID manquant.'], 400);
             }
     
             // Vérifie ou crée une session anonyme
             $session = ChatSession::firstOrCreate([
                 'session_id' => $sessionId,
             ]);
     
             ChatMessage::create([
                 'chat_session_id' => $session->id,
                 'message' => $request->input('userMessage'),
                 'sender' => 'user',
                 'response_time' => null
             ]);
     
             ChatMessage::create([
                 'chat_session_id' => $session->id,
                 'message' => $request->input('botMessage'),
                 'sender' => 'bot',
                 'intent' => $request->input('intent') ?? null,
                 'response_time' => $responseTime !== null ? round($responseTime) : null,

             ]);
         }
     
         return response()->json(['status' => 'saved']);
     }
     public function getHistory(Request $request)
     {
         $user = Auth::user();
         $sessionId = $request->header('X-Session-ID');
     
         $query = ChatMessage::query();
     
         if ($user) {
             $query->where('user_id', $user->id);
         } elseif ($sessionId) {
             $session = ChatSession::where('session_id', $sessionId)->first();
             if ($session) {
                 $query->where('chat_session_id', $session->id);
             } else {
                 return response()->json(['history' => []]);
             }
         } else {
             return response()->json(['history' => []]);
         }
     
         $twoWeeksAgo = now()->subWeeks(2);
     
         $messages = $query->where('created_at', '>=', $twoWeeksAgo)
             ->orderBy('created_at', 'asc')
             ->get()
             ->map(function ($msg) use ($user) {
                 $reaction = null;
     
                 // Ne cherche une réaction que si le message vient du bot
                 if ($msg->sender === 'bot' && $user) {
                     $reaction = ChatbotReaction::where('user_id', $user->id)
                         ->where('message', $msg->message)
                         ->value('reaction');
                 }
     
                 return [
                     'message' => $msg->message,
                     'sender' => $msg->sender,
                     'created_at' => $msg->created_at,
                     'reaction' => $reaction,
                 ];
             });
     
         return response()->json(['history' => $messages]);
     }
     
     

}
