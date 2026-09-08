<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordleController;
use App\Models\GameStat;
use Illuminate\Http\Request;

Route::get('/', [WordleController::class, 'index']);

Route::post('/wordle/result', function (Request $request) {

    $data = $request->validate([
        'won' => ['required', 'boolean'],
        'guessNumber' => ['nullable', 'integer', 'between:1,6'],
    ]);

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

    // Tambah jumlah permainan
    $stats->increment('games_played');

    if ($data['won']) {

        // Tambah jumlah kemenangan
        $stats->increment('games_won');

        // Ambil ulang data terbaru
        $stats->refresh();

        // Tambah streak
        $stats->increment('current_streak');

        // Ambil ulang setelah increment
        $stats->refresh();

        // Update best streak
        if ($stats->current_streak > $stats->best_streak) {
            $stats->best_streak = $stats->current_streak;
            $stats->save();
        }

        // Tambah Guess Distribution
        if ($data['guessNumber']) {

            $guessColumn = 'guess_' . $data['guessNumber'];

            $stats->increment($guessColumn);
        }

    } else {

        // Jika kalah, streak kembali 0
        $stats->current_streak = 0;
        $stats->save();
    }

    // Ambil data PALING BARU dari database
    $stats->refresh();

    return response()->json([
        'success' => true,
        'received' => $data,
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
