<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position');
            $table->unsignedInteger('points');
            $table->timestamps();

            $table->unique(['player_id', 'game_id']); // Prevent duplicate scores for same player in one game
        });
    }

    public function down(): void {
        Schema::dropIfExists('scores');
    }
};

