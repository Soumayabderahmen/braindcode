<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatbotReaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatbotReactionAdminController extends Controller
{
    public function index()
    {
        $reactions = ChatbotReaction::with('user:id,name')
            ->latest()
            ->get()
            ->map(function ($reaction) {
                // Recherche du message utilisateur ayant précédé la réponse du bot
                $userMessage = ChatMessage::where('user_id', $reaction->user_id)
                    ->where('is_from_bot', false)
                    ->where('created_at', '<=', $reaction->created_at)
                    ->orderBy('created_at', 'desc')
                    ->first();
    
                return [
                    'id' => $reaction->id,
                    'user' => $reaction->user,
                    'message' => $reaction->message, // réponse du bot
                    'reaction' => $reaction->reaction,
                    'created_at' => $reaction->created_at,
                    'originalMessage' => $userMessage?->message, // <-- ici
                ];
            });
    
        return Inertia::render('Admin/ChatbotReactions', [
            'reactions' => $reactions
        ]);
    }
}

