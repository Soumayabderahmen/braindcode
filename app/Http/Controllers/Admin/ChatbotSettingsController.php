<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSetting;
use Illuminate\Http\Request;

class ChatbotSettingsController extends Controller
{
    public function getSettings()
    {
        $settings = ChatbotSetting::first();
        return response()->json($settings);
    }

    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'bot_name' => 'nullable|string|max:255',
            'welcome_message' => 'nullable|string',
            'timeout_message' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7', // ex: #2563eb
            'training_text' => 'nullable|string',
        ]);

        $settings = ChatbotSetting::first() ?? new ChatbotSetting();
        $settings->fill($validated);
        $settings->save();

        return response()->json(['message' => 'Paramètres mis à jour']);
    }
}
