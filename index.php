<?php
session_start();

//$start = microtime(true);

require('./configs/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $wordIndex = rand(0, $wordsCount - 1);
    $word = $words[$wordIndex];
    $lettersCount = strlen($word);
    $letters = [
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
    $trials = 0;
    $replacementString = str_pad('', $lettersCount, REPLACEMENT_CHAR);
    $triedLetters = [];

    setcookie('wordIndex', $wordIndex);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //récupération des données de la requête
    $wordIndex = $_COOKIE['wordIndex'];
    $triedLetter = $_POST['triedLetter'];
    $letters = json_decode($_COOKIE['letters'], true);

    //calcul des nouvelles valeurs du State
    $letters[$triedLetter] = false;
    $triedLetters = array_filter($letters, fn($b) => !$b);
    $word = strtolower($words[$wordIndex]);
    $trials = count(array_filter(array_keys($triedLetters), fn($l) => !str_contains($word, $l)));
    $lettersCount = strlen($word);
    $replacementString =  str_pad('', $lettersCount, REPLACEMENT_CHAR);

    $letterFound = false;
    for ($i = 0; $i < $lettersCount; $i++) {
        $replacementString[$i] = array_key_exists($word[$i], $triedLetters) ? $word[$i] : REPLACEMENT_CHAR ;
        if ($triedLetter === substr($word, $i, 1)) {
            $letterFound = true;
        }
    }
    //echo $word;
    if (!$letterFound) {
        if (MAX_TRIALS <= $trials) {
            $gameState = 'lost';
        }
    } else {
        if ($word === $replacementString) {
            $gameState = 'win';
        }
    }
} else {
    header('HTTP/1.1 405 Not Allowed');
    exit("Vous n'avez pas le droit d'exécuter cette commande");
}
$triedLettersStr = implode(',', array_keys($triedLetters));

setcookie("letters", json_encode($letters));

require('./views/start.php');
//$end = microtime(true);
//$renderTime = $end - $start;
//printf('rendu de la page en %.6f milliseconde', $renderTime * 1000);