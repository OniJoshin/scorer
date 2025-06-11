<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('game_session_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->nullable(); // 1st, 2nd, etc.
            $table->json('custom_score')->nullable(); // Dynamic game-specific score values
            $table->timestamps();

            $table->unique(['game_session_id', 'player_id']); // Prevent duplicates
        });
    }

    public function down(): void {
        Schema::dropIfExists('game_session_results');
    }
};

