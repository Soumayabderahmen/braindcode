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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('email')->default('startup');
            $table->enum('visibility', ['public', 'private'])->nullable();
            $table->string('image')->nullable();
            $table->string('domain_name')->nullable();
            $table->string('specialty')->nullable();
            $table->string('document')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'visibility', 'image', 'domain_name', 'specialty','document']);
        });
    }
};
