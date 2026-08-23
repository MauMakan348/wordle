<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Wordle</title>

    @vite('resources/css/wordle.css')
</head>

<body>

    <header>
        <h1>WORDLE</h1>
        <button id="helpButton">?</button>
    </header>

<!-- STATS -->
    <div class="stats">
        <div class="stat">
            <strong id="gamesPlayed">{{ $stats->games_played }}</strong>
            <span>Played</span>
        </div>
        <div class="stat">
            <strong id="gamesWon">{{ $stats->games_won }}</strong>
            <span>Menang</span>
        </div>
        <div class="stat">
            <strong id="winRate">
                @if ($stats->games_played > 0)
                    {{ round(($stats->games_won / $stats->games_played) * 100) }}%
                @else
                    0%
                @endif
            </strong>
            <span>Win Rate</span>
        </div>
        <div class="stat">
            <strong id="currentStreak">{{ $stats->current_streak }}</strong>
            <span>Streak</span>
        </div>
        <div class="stat">
            <strong id="bestStreak">{{ $stats->best_streak }}</strong>
            <span>Best</span>
        </div>
    </div>

<!-- distribusi -->
<div class="distribution">
    <h3>GUESS DISTRIBUTION</h3>
    <div class="distribution-row">
        <span>1</span>
        <div class="bar">
            <div id="guess1Bar" class="bar-fill">0</div>
        </div>
    </div>
    <div class="distribution-row">
        <span>2</span>
        <div class="bar">
            <div id="guess2Bar" class="bar-fill">0</div>
        </div>
    </div>
    <div class="distribution-row">
        <span>3</span>
        <div class="bar">
            <div id="guess3Bar" class="bar-fill">0</div>
        </div>
    </div>
    <div class="distribution-row">
        <span>4</span>
        <div class="bar">
            <div id="guess4Bar" class="bar-fill">0</div>
        </div>
    </div>
    <div class="distribution-row">
        <span>5</span>
        <div class="bar">
            <div id="guess5Bar" class="bar-fill">0</div>
        </div>
    </div>
    <div class="distribution-row">
        <span>6</span>
        <div class="bar">
            <div id="guess6Bar" class="bar-fill">0</div>
        </div>
    </div>
</div>

<!-- MAIN -->
    <div class="game">

        <div id="message"></div>

        <button id="restartButton">Main Lagi</button>
        <button id="resetStatsButton" class="reset-button">
            RESET STATISTIK
        </button>

        <div class="board">

            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>

            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>

            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>

            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>

            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>

            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>
            <div class="tile"></div>

        </div>
        <div class="keyboard">

            <div class="keyboard-row">
                <button class="key">Q</button>
                <button class="key">W</button>
                <button class="key">E</button>
                <button class="key">R</button>
                <button class="key">T</button>
                <button class="key">Y</button>
                <button class="key">U</button>
                <button class="key">I</button>
                <button class="key">O</button>
                <button class="key">P</button>
            </div>

        <div class="keyboard-row">
            <button class="key">A</button>
            <button class="key">S</button>
            <button class="key">D</button>
            <button class="key">F</button>
            <button class="key">G</button>
            <button class="key">H</button>
            <button class="key">J</button>
            <button class="key">K</button>
            <button class="key">L</button>
        </div>

        <div class="keyboard-row">
            <button class="key wide">ENTER</button>

            <button class="key">Z</button>
            <button class="key">X</button>
            <button class="key">C</button>
            <button class="key">V</button>
            <button class="key">B</button>
            <button class="key">N</button>
            <button class="key">M</button>

            <button class="key wide">⌫</button>
        </div>
</div>

    </div>

<!-- CARA MAIN -->
<div id="helpModal" class="modal">
    <div class="modal-content">
        <button id="closeHelp">×</button>
        <h2>Cara Bermain</h2>
        <p>
            Tebak kata yang terdiri dari 5 huruf.
            Kamu memiliki 6 kesempatan.
        </p>
        <div class="example">
            <div class="example-tile correct">R</div>
            <span>Huruf benar dan posisi benar.</span>
        </div>
        <div class="example">
            <div class="example-tile present">U</div>
            <span>Huruf ada, tetapi posisi salah.</span>
        </div>
        <div class="example">
            <div class="example-tile absent">M</div>
            <span>Huruf tidak ada dalam kata.</span>
        </div>
    </div>
</div>

<!-- STATS MODAL -->
<div id="statsModal" class="modal">
    <div class="modal-content stats-modal-content">
        <h2 id="gameResult">STATISTIK</h2>
        <div class="stats">
            <div class="stat">
                <strong id="modalGamesPlayed">
                    {{ $stats->games_played }}
                </strong>
                <span>Played</span>
            </div>
            <div class="stat">
                <strong id="modalGamesWon">
                    {{ $stats->games_won }}
                </strong>
                <span>Menang</span>
            </div>
            <div class="stat">
                <strong id="modalWinRate">0%</strong>
                <span>Win Rate</span>
            </div>
            <div class="stat">
                <strong id="modalCurrentStreak">
                    {{ $stats->current_streak }}
                </strong>
                <span>Streak</span>
            </div>
            <div class="stat">
                <strong id="modalBestStreak">
                    {{ $stats->best_streak }}
                </strong>
                <span>Best</span>
            </div>
        </div>
        <button id="playAgainButton">
            MAIN LAGI
        </button>
    </div>
</div>

    <script>
        window.secretWord = @json($secretWord);
        window.validWords = @json($validWords);
    </script>

    @vite('resources/js/wordle.js')

</body>
</html>