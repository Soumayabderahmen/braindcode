<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatbotAdminController extends Controller
{
    public function index()
    {
        // Liste des utilisateurs ayant des messages
        $users = User::whereHas('chatMessages')->get();
    
        return Inertia::render('Admin/ChatbotAdmin', [
            'users' => $users
        ]);
    }
    
    // Admin\ChatbotAdminController.php

    public function messages()
    {
        $messages = ChatMessage::with('user')->orderBy('created_at', 'asc')->get();
    
        $data = $messages->map(function ($msg) {
            return [
                'user_name' => $msg->user?->name ?? 'Invité',
                'message' => $msg->message,
                'sender' => $msg->sender,
                'created_at' => $msg->created_at,
            ];
        });
    
        return response()->json(['messages' => $data]);
    }
    
    
}

