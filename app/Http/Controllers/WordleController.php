<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\GameStat;

class WordleController extends Controller
{
    public function index(): View{
        $words = [
            'KOKOP',
            'KURSI',
            'PUTRA',
            'PUTRI',
            'RUMAH',
            'KAPAL',
            'KARAM',
            'LOLOK',
            'GELAS',
            'ANIES',
            'RUKUN',
            'RUSAK',
            'BETIS',
            'KIPAS',
            'MERAH',
            'BALON',
            'PANAS',
            'WAJAN',
            'SIKAT',
            'PENIS',
            'ISTRI',
            'SUAMI',
            'BOKEP',
            'JARAH',
            'IKLAS',
            'RINDU',
            'MASIH',
            'LEBAR',
            'SEREM',
            'MASUK',
            'NGANU',
            'ORANG',
            'ADMIN',
            'BAGUS',
            'SIANG',
            'MALAM',
            'LAMAR',
            'PUNYA',
            'BENAR',
            'BEJAR',
            'MAKAN',
        ];

        $stats = GameStat::first();
        if (!$stats) {
            $stats = GameStat::create([
                'games_played' => 0,
                'games_won' => 0,
                'current_streak' => 0,
                'best_streak' => 0,
            ]);
        }

        return view('wordle', [
            'secretWord' => $words[array_rand($words)],
            'validWords' => $words,
            'stats' => $stats,
        ]);
    }
}
