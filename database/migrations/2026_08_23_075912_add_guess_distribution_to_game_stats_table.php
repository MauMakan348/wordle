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
        Schema::table('game_stats', function (Blueprint $table) {
            $table->integer('guess_1')->default(0);
            $table->integer('guess_2')->default(0);
            $table->integer('guess_3')->default(0);
            $table->integer('guess_4')->default(0);
            $table->integer('guess_5')->default(0);
            $table->integer('guess_6')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::table('game_stats', function (Blueprint $table) {
            $table->dropColumn([
                'guess_1',
                'guess_2',
                'guess_3',
                'guess_4',
                'guess_5',
                'guess_6',
            ]);
        });
    }
};
