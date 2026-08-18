<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Wordle</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #121213;
            color: white;
            font-family: Arial, sans-serif;
        }

        header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #3a3a3c;
        }

        header h1 {
            margin: 0;
            font-size: 32px;
        }

        .game {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
        }

        .board {
            display: grid;
            grid-template-columns: repeat(5, 60px);
            gap: 5px;
        }

        .tile {
            width: 60px;
            height: 60px;
            border: 2px solid #3a3a3c;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .keyboard {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .keyboard-row {
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .key {
            width: 43px;
            height: 58px;

            border: none;
            border-radius: 4px;

            background: #818384;
            color: white;

            font-size: 14px;
            font-weight: bold;

            cursor: pointer;
        }

        .key:hover {
            background: #6b6c6d;
        }

        .key:active {
            transform: scale(0.95);
        }

        .key.wide {
            width: 65px;
        }
    </style>
</head>

<body>

    <header>
        <h1>WORDLE</h1>
    </header>

    <div class="game">

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

<script>
    let currentRow = 0;
    let currentCol = 0;

    const secretWord = 'HOUSE';
    let gameOver = false;

    const tiles = document.querySelectorAll('.tile');

    document.addEventListener('keydown', function(event) {

        // Huruf A-Z
        if (/^[a-zA-Z]$/.test(event.key)) {
            if (gameOver) {
            return;
            }

            if (currentCol < 5) {
                const tile = tiles[currentRow * 5 + currentCol];
                tile.textContent = event.key.toUpperCase();
                currentCol++;
            }
        }

        // Backspace
        else if (event.key === 'Backspace') {
            if (gameOver){
                return;
            }
            if (currentCol > 0) {
                currentCol--;
                const tile = tiles[currentRow * 5 + currentCol];
                tile.textContent = '';
            }
        }

        // Enter
        else if (event.key === 'Enter') {
            if (gameOver){
                return
            }
            if (currentCol === 5) {
                const won = checkGuess();
                if (won){
                    return;
                }
                currentRow++;
                currentCol = 0;
                if (currentRow >= 6){
                    gameOver = true;
                    setTimeout(function(){
                        alert('Game Over!' + secretWord);
                    }, 100);
                }
            }
        }
    });

    function getCurrentGuess() {

        let guess = '';

        for (let i = 0; i < 5; i++) {
            guess += tiles[currentRow * 5 + i].textContent;
        }

        return guess;
    }

    function checkGuess() {

        const guess = getCurrentGuess();

        console.log('Jawaban pemain:', guess);

        for (let i = 0; i < 5; i++) {

            const tile = tiles[currentRow * 5 + i];
            const letter = guess[i];

            // Huruf benar dan posisi benar
            if (letter === secretWord[i]) {

                tile.style.backgroundColor = '#538d4e';
                tile.style.borderColor = '#538d4e';

            }

            // Huruf ada tetapi posisi salah
            else if (secretWord.includes(letter)) {

                tile.style.backgroundColor = '#b59f3b';
                tile.style.borderColor = '#b59f3b';

            }

            // Huruf tidak ada
            else {

                tile.style.backgroundColor = '#3a3a3c';
                tile.style.borderColor = '#3a3a3c';

            }
        }

        //menang
        if (guess === secretWord){
            gameOver = true;

            setTimeout(function(){
                alert('kamu menang!');
            }, 100);
            return true;
        }
        return false;
    }


const keys = document.querySelectorAll('.key');

keys.forEach(function(key) {

    key.addEventListener('click', function() {

        const letter = key.textContent;

if (letter === 'ENTER') {
    if (gameOver) {
        return;
    }
    if (currentCol === 5) {
        const won = checkGuess();
        if (won) {
            return;
        }
        currentRow++;
        currentCol = 0;
        if (currentRow >= 6) {
            gameOver = true;
            setTimeout(function() {
                alert('Game Over! Jawabannya adalah ' + secretWord);
            }, 100);
        }
    }
    return;
}

        // Tombol hapus
        if (letter === '⌫') {

            if (currentCol > 0) {

                currentCol--;

                const tile = tiles[currentRow * 5 + currentCol];

                tile.textContent = '';
            }

            return;
        }

        // Huruf biasa
        if (gameOver) {
        return;
        }
        if (currentCol < 5) {
            const tile = tiles[currentRow * 5 + currentCol];
            tile.textContent = letter;
            currentCol++;
        }

    });

});
</script>

</body>
</html>