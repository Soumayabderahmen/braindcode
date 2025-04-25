<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatbotReaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'reaction'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
