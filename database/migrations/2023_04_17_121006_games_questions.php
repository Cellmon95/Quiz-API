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
        Schema::create('games_questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('question_id')->constrained();
            $table->foreignId('game_id')->constrained();
            $table->string('result', 20);
            $table->boolean('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games_questions');
    }
};
