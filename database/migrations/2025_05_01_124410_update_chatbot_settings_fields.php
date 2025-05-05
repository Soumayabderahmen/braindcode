<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chatbot_settings', function (Blueprint $table) {
            $table->renameColumn('timeout_response', 'timeout_message');
            $table->text('training_text')->nullable()->after('timeout_message');
        });
    }

    public function down(): void
    {
        Schema::table('chatbot_settings', function (Blueprint $table) {
            $table->renameColumn('timeout_message', 'timeout_response');
            $table->dropColumn('training_text');
        });
    }
};

