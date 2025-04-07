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
        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id');
            $table->unsignedBigInteger('startup_id');

            // Détails de la réservation
            $table->time('meeting_time'); 
            $table->integer('duration');
            $table->decimal('total', 8, 2); 
            $table->text('message')->nullable();
            $table->string('statut')->default('en attente'); 

            $table->timestamps();

            // Clés étrangères avec contraintes
            $table->foreign('coach_id')->references('id')->on('coach')->onDelete('cascade');
            $table->foreign('startup_id')->references('id')->on('startup')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation');
    }
};
