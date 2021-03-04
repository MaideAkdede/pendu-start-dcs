<?php
define("PAGE_TITLE", "Le Pendu");
define("MAX_TRIALS", 8);
define("REPLACEMENT_CHAR", '*');

$words = file('datas/words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$wordsCount = count($words);
$gameState = 'start';
