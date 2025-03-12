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
        if (!Schema::hasTable('coach')) {
            Schema::create('coach', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('diplome')->nullable();
                $table->text('competence')->nullable();
                $table->text('description')->nullable();
                $table->string('profile_image')->nullable();
                $table->string('document')->nullable();
                $table->timestamps();
                $table->string('cover_image')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach');
    }
};
