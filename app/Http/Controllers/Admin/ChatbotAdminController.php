<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PredefinedResponse;
use App\Models\ChatMessage;
use App\Models\ChatbotReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ChatbotAdminController extends Controller
{
    public function index()
    {
        $users = User::whereHas('chatMessages')->get();

        return view('Chatbot.chatbot', [
            'users' => $users
        ]);
    }

    public function management()
    {
        return view('Chatbot.management', [
            'stats' => [
                'total_conversations' => ChatMessage::count(),
                'resolution_rate' => 78, 
                'avg_response_time' => 3.2, // idem
                'satisfaction' => 86, // idem
            ],
            'settings' => [
                'enabled' => true,
                'bot_name' => 'Support Bot',
                'welcome_message' => 'Hello! How can I help you today?',
                'timeout_message' => 'Sorry for the delay...',
                'primary_color' => '#2563eb',
            ],
            'responses' => PredefinedResponse::all()
        ]);
    }

 
    
    public function stats()
    {
        $totalMessages = ChatMessage::count();
        $guestCount =  ChatMessage::whereNull('user_id')->distinct('chat_session_id')->count('chat_session_id');
        $userCount = ChatMessage::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        $average = $userCount > 0 ? round($totalMessages / $userCount, 2) : 0;
    
        // 🔥 Utiliser la table ChatbotReaction ici
        $positive = ChatbotReaction::where('reaction', '👍')->count();
        $negative = ChatbotReaction::where('reaction', '👎')->count();
    
        // Taux de satisfaction basé sur les réactions
        $totalReactions = $positive + $negative;
        $satisfaction = $totalReactions ? round(($positive / $totalReactions) * 100) : 0;
    
        // Engagement : % d’utilisateurs ayant interagi (ayant envoyé >=1 msg)
        $totalUsers = User::count();
        $engagedUsers = ChatMessage::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        $engagementRate = $totalUsers > 0 ? round(($engagedUsers / $totalUsers) * 100, 1) : 0;
    
        // Temps moyen de réponse (différence entre msg user et msg bot suivant)
        $avgResponseTime = ChatMessage::whereNotNull('user_id')
            ->orderBy('created_at')
            ->get()
            ->groupBy('user_id')
            ->map(function ($messages) {
                $pairs = [];
                $prev = null;
                foreach ($messages as $msg) {
                    if ($prev && $prev->sender === 'user' && $msg->sender === 'bot') {
                        $diff = $msg->created_at->diffInSeconds($prev->created_at);
                        $pairs[] = $diff;
                    }
                    $prev = $msg;
                }
                return count($pairs) ? array_sum($pairs) / count($pairs) : null;
            })
            ->filter()
            ->avg();
    
        $avgResponseTime = $avgResponseTime ? round($avgResponseTime, 1) : null;
    
        // Top 5 questions
        $topQuestions = ChatMessage::where('sender', 'user')
            ->selectRaw('message, COUNT(*) as count')
            ->groupBy('message')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('message');
    
        // Répartition par rôle
        $roles = User::whereNotNull('role')->pluck('role')->unique();

        $roleDistribution = [];
        foreach ($roles as $role) {
            $roleDistribution[$role] = ChatMessage::whereHas('user', function ($q) use ($role) {
                $q->where('role', $role);
            })->count();
        }
        $daily = ChatMessage::select(
            DB::raw("DATE(created_at) as day"),
            DB::raw("count(*) as total")
        )
        ->where('created_at', '>=', now()->subDays(6))
        ->groupBy('day')
        ->orderBy('day')
        ->get()
        ->pluck('total', 'day');
        
        // Générer les 7 jours de la semaine avec zéro par défaut
        $dailyConversations = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $dailyConversations[] = $daily[$date] ?? 0;
        }
        return response()->json([
            'totalMessages' => $totalMessages,
            'dailyConversations' => $dailyConversations,
            'userCount' => $userCount,
             'guestCount' => $guestCount,
            'averageMessagesPerUser' => $average,
            'positiveReactions' => $positive,
            'negativeReactions' => $negative,
            'satisfaction' => $satisfaction,
            'engagementRate' => $engagementRate,
            'avgResponseTime' => $avgResponseTime,
            'topQuestions' => $topQuestions,
            'roleDistribution' => $roleDistribution,
            'satisfactionBreakdown' => [
                'Satisfaits' => $positive,
                'Insatisfaits' => $negative,
                'Neutres' => max(0, $totalMessages - $positive - $negative)
            ]
        ]);
    }
    
/**
     * Liste des messages pour la vue historique.
     */
    public function messages()
    {
        $messages = ChatMessage::orderBy('created_at', 'asc')->get();
    
        $data = $messages->map(function ($msg) {
            $user = $msg->user; // Peut être null
            $sessionId = $msg->chat_session_id;
    
            return [
                'user_id' => $user?->id ?? null,
                'session_id' => $sessionId,
                'user_name' => $user?->name ?? 'Invité #' . substr($sessionId ?? 'anonyme', 0, 6),
                'user_role' => $user?->role ?? 'invité',
                'message' => $msg->message,
                'sender' => $msg->sender,
                'intent' => $msg->intent,
                'created_at' => $msg->created_at,
            ];
        });
    
        return response()->json(['messages' => $data]);
    }
    


}
