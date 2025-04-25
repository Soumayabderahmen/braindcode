<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Intention;

class IntentionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Intention::create([
        'name' => 'probleme_etape',
        'example_message' => 'J’ai un problème à l’étape 4',
        'prompt_template' => "L'utilisateur a un souci à l'étape {{etape}}. Explique l'objectif et propose une aide claire."
    ]);

    Intention::create([
        'name' => 'salutation',
        'example_message' => 'Bonjour, ça va ?',
        'prompt_template' => "L'utilisateur te salue. Réponds de manière naturelle et chaleureuse."
    ]);
}
}
