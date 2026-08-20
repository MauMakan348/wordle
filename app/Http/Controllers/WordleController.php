<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WordleController extends Controller
{
    public function index(): View{
        $words = [
            'KOKOP',
            'RUMAH',
            'PUTRA',
            'PUTRI',
            'RUMAH',
            'KAPAL',
            'KARAM',
            'LOLOK',
            'GELAS',
            'ANIES',
        ];

        $secretWord = $words[array_rand($words)];
        
        return view('wordle', [
            'secretWord' => $secretWord,
            'words' => $words,
        ]);
    }
}
