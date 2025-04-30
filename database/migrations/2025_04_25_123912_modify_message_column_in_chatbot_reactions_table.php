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
        Schema::table('chatbot_reactions', function (Blueprint $table) {
            $table->text('message')->change(); // passage de string à text
        });
    }

    public function down()
    {
        Schema::table('chatbot_reactions', function (Blueprint $table) {
            $table->string('message', 255)->change(); // rollback
        });
    }
};
