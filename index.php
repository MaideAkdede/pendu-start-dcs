<?php
session_start();

//$start = microtime(true);

require('./configs/config.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $words = file('datas/words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $wordsCount = count($words);
    $wordIndex = rand(0, $wordsCount - 1);
    $_SESSION['word'] = strtolower($words[$wordIndex]);
    $lettersCount = strlen($_SESSION['word']);
    $trials = 0;
    $triedLetters = [];
    $_SESSION['letters'] = [
        'a' => true,
        'b' => true,
        'c' => true,
        'd' => true,
        'e' => true,
        'f' => true,
        'g' => true,
        'h' => true,
        'i' => true,
        'j' => true,
        'k' => true,
        'l' => true,
        'm' => true,
        'n' => true,
        'o' => true,
        'p' => true,
        'q' => true,
        'r' => true,
        's' => true,
        't' => true,
        'u' => true,
        'v' => true,
        'w' => true,
        'x' => true,
        'y' => true,
        'z' => true
    ];
    $replacementString = str_pad('', $lettersCount, REPLACEMENT_CHAR);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //récupération des données de la requête
    //$wordIndex = $_COOKIE['wordIndex'];
    $lettersCount = strlen($_SESSION['word']);
    $triedLetter = $_POST['triedLetter'];
    $replacementString = str_pad('', $lettersCount, REPLACEMENT_CHAR);

    //calcul des nouvelles valeurs du State
    $_SESSION['letters'][$triedLetter] = false;
    $triedLetters = array_filter($_SESSION['letters'], fn($b) => !$b);
    $trials = count(array_filter(array_keys($triedLetters), fn($l) => !str_contains($_SESSION['word'], $l)));

    $letterFound = false;
    for ($i = 0; $i < $lettersCount; $i++) {
        $replacementString[$i] = array_key_exists($_SESSION['word'][$i], $triedLetters) ? $_SESSION['word'][$i] : REPLACEMENT_CHAR;
        if ($triedLetter === substr($_SESSION['word'], $i, 1)) {
            $letterFound = true;
        }
    }
    //echo $_SESSION['word'];
    if (!$letterFound) {
        if (MAX_TRIALS <= $trials) {
            $gameState = 'lost';
        }
    } else {
        if ($_SESSION['word'] === $replacementString) {
            $gameState = 'win';
        }
    }
} else {
    header('HTTP/1.1 405 Not Allowed');
    exit("Vous n'avez pas le droit d'exécuter cette commande");
}
$triedLettersStr = implode(',', array_keys($triedLetters));
require('./views/start.php');