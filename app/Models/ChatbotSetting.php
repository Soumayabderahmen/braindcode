<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotSetting extends Model
{
    protected $table = 'chatbot_settings';

    protected $fillable = [
        'enabled',
        'bot_name',
        'welcome_message',
        'position',
        'primary_color',
        'timeout_message',
        'training_text',
    ];
}
