const secretWord = window.secretWord;
const validWords = window.validWords;

let currentRow = 0;
let currentCol = 0;
let gameOver = false;

const keyStatus = {};

const tiles = document.querySelectorAll('.tile');

const message = document.querySelector('#message');

const restartButton = document.querySelector('#restartButton');
const resetStatsButton = document.querySelector('#resetStatsButton');

const gamesPlayed = document.querySelector('#gamesPlayed');
const gamesWon = document.querySelector('#gamesWon');
const currentStreak = document.querySelector('#currentStreak');
const bestStreak = document.querySelector('#bestStreak');
const winRate = document.querySelector('#winRate');

const statsModal = document.querySelector('#statsModal');
const gameResult = document.querySelector('#gameResult');
const modalGamesPlayed = document.querySelector('#modalGamesPlayed');
const modalGamesWon = document.querySelector('#modalGamesWon');
const modalWinRate = document.querySelector('#modalWinRate');
const modalCurrentStreak = document.querySelector('#modalCurrentStreak');
const modalBestStreak = document.querySelector('#modalBestStreak');
const playAgainButton = document.querySelector('#playAgainButton');

const guessBars = [
    document.querySelector('#guess1Bar'),
    document.querySelector('#guess2Bar'),
    document.querySelector('#guess3Bar'),
    document.querySelector('#guess4Bar'),
    document.querySelector('#guess5Bar'),
    document.querySelector('#guess6Bar')
];


// ==========================
// KEYBOARD FISIK
// ==========================

document.addEventListener('keydown', function(event) {

    // Huruf A-Z
    if (/^[a-zA-Z]$/.test(event.key)) {

        if (gameOver) {
            return;
        }

        if (currentCol < 5) {

            const tile = tiles[currentRow * 5 + currentCol];

            tile.textContent = event.key.toUpperCase();
            tile.classList.add('pop');
            setTimeout(function(){
                tile.classList.remove('pop');
            }, 100);

            currentCol++;
        }
    }


    // BACKSPACE
    else if (event.key === 'Backspace') {

        if (gameOver) {
            return;
        }

        if (currentCol > 0) {
            currentCol--;
            const tile = tiles[currentRow * 5 + currentCol];
            tile.textContent = '';
        }
    }


    // ENTER
    else if (event.key === 'Enter') {

        handleEnter();
    }

});


// ==========================
// AMBIL KATA PEMAIN
// ==========================

function getCurrentGuess() {

    let guess = '';

    for (let i = 0; i < 5; i++) {

        guess += tiles[currentRow * 5 + i].textContent;
    }

    return guess;
}


// ==========================
// CEK KATA
// ==========================

function getKeyboardKey(letter) {

    const keys = document.querySelectorAll('.key');

    for (const key of keys) {

        if (key.textContent === letter) {
            return key;
        }
    }
}

function updateKeyboard(letter, status) {

    const keyboardKey = getKeyboardKey(letter);

    if (!keyboardKey) {
        return;
    }

    const currentStatus = keyStatus[letter];

    // Hijau adalah prioritas tertinggi
    if (currentStatus === 'correct') {
        return;
    }

    // Kuning tidak boleh ditimpa abu-abu
    if (currentStatus === 'present' && status === 'absent') {
        return;
    }

    keyStatus[letter] = status;

    if (status === 'correct') {
        keyboardKey.style.backgroundColor = '#538d4e';
    }
    else if (status === 'present') {
        keyboardKey.style.backgroundColor = '#b59f3b';
    }
    else if (status === 'absent') {
        keyboardKey.style.backgroundColor = '#3a3a3c';
    }
}

function showMessage(text, permanent = false){
    message.textContent = text;
    if (permanent){
        restartButton.style.display = 'block';
    } else {
        setTimeout(function(){
            message.textContent = '';
        }, 1500);
    }
    
}

function saveGameResult(won, resultText, guessNumber = null) {

    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    fetch('/wordle/result', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            won: won,
            guessNumber: guessNumber
        })
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        console.log('Statistik berhasil disimpan:', data);
        gamesPlayed.textContent = data.stats.games_played;
        gamesWon.textContent = data.stats.games_won;
                const rate = Math.round(
            (data.stats.games_won / data.stats.games_played) * 100
        );
        winRate.textContent = rate + '%';
        currentStreak.textContent = data.stats.current_streak;
        bestStreak.textContent = data.stats.best_streak;

        showStatsModal(resultText, data.stats);
    })
    .catch(function(error) {
        console.error('Gagal menyimpan statistik:', error);
    });
}

function showStatsModal(result, stats) {
    gameResult.textContent = result;
    modalGamesPlayed.textContent = stats.games_played;
    modalGamesWon.textContent = stats.games_won;
    const rate = Math.round(
        (stats.games_won / stats.games_played) * 100
    );
    modalWinRate.textContent = rate + '%';
    modalCurrentStreak.textContent = stats.current_streak;
    modalBestStreak.textContent = stats.best_streak;
    statsModal.classList.add('show');

    const guesses = [
        stats.guess_1,
        stats.guess_2,
        stats.guess_3,
        stats.guess_4,
        stats.guess_5,
        stats.guess_6
    ];
    const maxGuess = Math.max(...guesses, 1);
    guesses.forEach(function(count, index) {
        const bar = guessBars[index];
        const width = (count / maxGuess) * 100;
        bar.style.width = width + '%';
        bar.textContent = count;
    });
}

function checkGuess() {

    const guess = getCurrentGuess();

    if (!validWords.includes(guess)){
        shakeRow();
        showMessage('Kata tidak di temukan!');
        return 'invalid';
    }

    console.log('Jawaban pemain:', guess);

    // Status setiap huruf
    const result = [
        'absent',
        'absent',
        'absent',
        'absent',
        'absent'
    ];

    // Pecah kata jawaban menjadi array
    const secretLetters = secretWord.split('');

    // CEK HIJAU DULU
    for (let i = 0; i < 5; i++) {
        const letter = guess[i];
        if (letter === secretLetters[i]) {
            result[i] = 'correct';
            // Huruf sudah digunakan
            secretLetters[i] = null;
        }
    }

    // CEK KUNING
    for (let i = 0; i < 5; i++) {
        const letter = guess[i];
        // Jika sudah hijau, lewati
        if (result[i] === 'correct') {
            continue;
        }

        const index = secretLetters.indexOf(letter);

        if (index !== -1) {
            result[i] = 'present';
            // Hapus agar tidak digunakan dua kali
            secretLetters[index] = null;
        }
    }

    // TERAPKAN WARNA
    for (let i = 0; i < 5; i++) {

        const tile = tiles[currentRow * 5 + i];
        const letter = guess[i];

        setTimeout(function(){
            tile.classList.add('flip');
            // HIJAU
            if (result[i] === 'correct') {
                tile.style.backgroundColor = '#538d4e';
                tile.style.borderColor = '#538d4e';
                updateKeyboard(letter, 'correct');
            }
            // KUNING
            else if (result[i] === 'present') {
                tile.style.backgroundColor = '#b59f3b';
                tile.style.borderColor = '#b59f3b';
                updateKeyboard(letter, 'present');
            }
            // ABU-ABU
            else {
                tile.style.backgroundColor = '#3a3a3c';
                tile.style.borderColor = '#3a3a3c';
                updateKeyboard(letter, 'absent');
            }
        }, i * 300);
    }

    // MENANG
    if (guess === secretWord) {
        gameOver = true;
        saveGameResult(true, 'KAMU MENANG!', currentRow + 1);
        return 'won';
    }
    return 'continue';
}

function shakeRow(){
    
    const currentTiles = [];
    
    for (let i = 0; i < 5; i++){
        const tile = tiles[currentRow * 5 + i];
        currentTiles.push(tile);
    }

    currentTiles.forEach(function(tile){
        tile.classList.add('shake');
        setTimeout(function(){
            tile.classList.remove('shake');
        }, 400);
    });
}

// FUNGSI ENTER
function handleEnter() {

    if (gameOver) {
        return;
    }

    if (currentCol !== 5) {
        shakeRow();
        showMessage('Masukkan 5 huruf terlebih dahulu!');
        return;
    }

    const result = checkGuess();

    if (result === 'invalid') {
        return;
    }

    if (result === 'won') {
        return;
    }

    currentRow++;
    currentCol = 0;

    if (currentRow >= 6) {
        gameOver = true;
        saveGameResult(
            false,
            'Game Over! Jawabannya: ' + secretWord
        );
    }
}


// ==========================
// KEYBOARD VIRTUAL
// ==========================

const keys = document.querySelectorAll('.key');


keys.forEach(function(key) {

    key.addEventListener('click', function() {

        const letter = key.textContent;


        // ENTER
        if (letter === 'ENTER') {

            handleEnter();

            return;
        }


        // BACKSPACE
        if (letter === '⌫') {

            if (gameOver) {
                return;
            }

            if (currentCol > 0) {

                currentCol--;

                const tile =
                    tiles[currentRow * 5 + currentCol];

                tile.textContent = '';
            }

            return;
        }


        // HURUF
        if (gameOver) {
            return;
        }


        if (currentCol < 5) {

            const tile =
                tiles[currentRow * 5 + currentCol];

            tile.textContent = letter;
            tile.classList.add('pop');
            setTimeout(function(){
                tile.classList.remove('pop');
            }, 100);

            currentCol++;
        }

    });

});

//restart button
restartButton.addEventListener('click', function() {
    window.location.reload();
});

//MODAL
const helpButton = document.querySelector('#helpButton');
const helpModal = document.querySelector('#helpModal');
const closeHelp = document.querySelector('#closeHelp');

helpButton.addEventListener('click', function(){
    helpModal.classList.add('show');
});

closeHelp.addEventListener('click', function(){
    helpModal.classList.remove('show');
});

// tombol main lagi
playAgainButton.addEventListener('click', function() {
    window.location.reload();
});

// reset button
resetStatsButton.addEventListener('click', function() {
    const confirmReset = confirm(
        'Yakin ingin menghapus semua statistik?'
    );

    if (!confirmReset) {
        return;
    }

    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');

    fetch('/wordle/reset-stats', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        const stats = data.stats;
        gamesPlayed.textContent =
            stats.games_played;
        gamesWon.textContent =
            stats.games_won;
        winRate.textContent = '0%';
        currentStreak.textContent =
            stats.current_streak;
        bestStreak.textContent =
            stats.best_streak;
        showStatsModal('STATISTIK DIRESET', stats);
    })
    .catch(function(error) {
        console.error(
            'Gagal mereset statistik:',
            error
        );
    });

});