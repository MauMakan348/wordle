<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordleController;
use App\Models\GameStat;
use Illuminate\Http\Request;

Route::get('/', [WordleController::class, 'index']);

Route::post('/wordle/result', function (Request $request) {
    $request->validate([
        'won' => ['required', 'boolean'],
        'guessNumber' => ['nullable', 'integer', 'between:1,6'],
    ]);

    $stats = GameStat::first();

    if (!$stats) {
        $stats = GameStat::create([
            'games_played' => 0,
            'games_won' => 0,
            'current_streak' => 0,
            'best_streak' => 0,
        ]);
    }

    $stats->games_played++;

    if ($request->won) {
        $stats->games_won++;
        $stats->current_streak++;
        if ($stats->current_streak > $stats->best_streak) {
            $stats->best_streak = $stats->current_streak;
        }
        $guessColumn = 'guess_' . $request->guessNumber;
        $stats->$guessColumn++;
    }

    $stats->save();

    return response()->json([
        'success' => true,
        'stats' => $stats,
    ]);
});

// reset button
Route::post('/wordle/reset-stats', function () {

    $stats = GameStat::firstOrCreate(
        ['id' => 1],
        [
            'games_played' => 0,
            'games_won' => 0,
            'current_streak' => 0,
            'best_streak' => 0,
            'guess_1' => 0,
            'guess_2' => 0,
            'guess_3' => 0,
            'guess_4' => 0,
            'guess_5' => 0,
            'guess_6' => 0,
        ]
    );

    $stats->update([
        'games_played' => 0,
        'games_won' => 0,
        'current_streak' => 0,
        'best_streak' => 0,
        'guess_1' => 0,
        'guess_2' => 0,
        'guess_3' => 0,
        'guess_4' => 0,
        'guess_5' => 0,
        'guess_6' => 0,
    ]);

    return response()->json([
        'stats' => $stats
    ]);
});
