<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','chat_session_id', 'message', 'sender','intent','response_time'];
    public $timestamps = true;


    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
    
}
