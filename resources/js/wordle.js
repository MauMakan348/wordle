const secretWord = window.secretWord;
const validWords = window.validWords;

let currentRow = 0;
let currentCol = 0;
let gameOver = false;

const keyStatus = {};

const tiles = document.querySelectorAll('.tile');


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

function checkGuess() {

    const guess = getCurrentGuess();
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
        setTimeout(function() {
            alert('Kamu menang!');
        }, 100);
        return 'won';
    }
    return 'continue';
}

// ==========================
// FUNGSI ENTER
// ==========================

function handleEnter() {

    if (gameOver) {
        return;
    }

    if (currentCol !== 5) {
        alert('Masukkan 5 huruf terlebih dahulu!');
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

        setTimeout(function() {
            alert('Game Over! Jawabannya adalah ' + secretWord);
        }, 100);
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

            currentCol++;
        }

    });

});