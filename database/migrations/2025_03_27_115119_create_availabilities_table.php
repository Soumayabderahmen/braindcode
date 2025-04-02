<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disponibilite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('coach')->onDelete('cascade'); 
            $table->string('day_of_week')->nullable(); 
            $table->date('date')->nullable(); 
            $table->time('start_time'); 
            $table->time('end_time'); 
            $table->string('statut')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilite');
    }
};
