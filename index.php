<?php
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
    $triedLetters = '';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wordIndex = $_POST['wordIndex'];
    $trials = $_POST['trials'];
    $lettersCount = $_POST['lettersCount'];
    $triedLetter = $_POST['triedLetter'];
    $replacementString = $_POST['replacementString'];
    $triedLetters = $_POST['triedLetters'];
    $letters = unserialize(urldecode($_POST['serializedLetters']));
    $letters[$triedLetter] = false;
    $word = strtolower($words[$wordIndex]);

    $triedLetters .= $triedLetter;
    //concaténation : $triedLetters = $triedLetters . $triedLetter;

    $letterFound = false;
    for ($i = 0; $i < $lettersCount; $i++) {
        if ($triedLetter === substr($word, $i, 1)) {
            $replacementString[$i] = $triedLetter;
            $letterFound = true;
        }
    }
    if (!$letterFound) {
        $trials++;
        if (MAX_TRIALS <= $trials) {
           $gameState = 'lost';
            //require ('./views/lost.php');
        } else {
            if($word === $replacementString){
                $gameState = 'win';
                //require('./views/won.php');
            }
        }
    }
} else {
    header('HTTP/1.1 405 Not Allowed');
    exit("Vous n'avez pas le droit d'exécuter cette commande");
}
$serializedLetters = urlencode(serialize($letters));
require('./views/start.php');

