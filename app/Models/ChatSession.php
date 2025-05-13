<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = ['session_id'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_session_id');
    }
}
