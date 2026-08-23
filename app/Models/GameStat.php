<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameStat extends Model{
    protected $fillable = [
        'games_played',
        'games_won',
        'current_streak',
        'best_streak',
        'guess_1',
        'guess_2',
        'guess_3',
        'guess_4',
        'guess_5',
        'guess_6',
    ];
}