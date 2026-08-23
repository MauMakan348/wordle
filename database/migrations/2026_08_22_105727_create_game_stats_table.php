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
        Schema::create('game_stats', function (Blueprint $table) {
            $table->id();

            $table->integer('games_played')->default(0);
            $table->integer('games_won')->default(0);

            $table->integer('current_streak')->default(0);
            $table->integer('best_streak')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_stats');
    }
};
