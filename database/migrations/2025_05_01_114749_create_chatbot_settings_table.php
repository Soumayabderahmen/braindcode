<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('chatbot_settings', function (Blueprint $table) {
        $table->id();
        $table->boolean('enabled')->default(true);
        $table->string('bot_name')->nullable();
        $table->text('welcome_message')->nullable();
        $table->string('position')->nullable();
        $table->string('primary_color')->nullable();
        $table->text('timeout_response')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_settings');
    }
};
