<?php
$input = file_get_contents('../inputs/day-3.txt');
$lines = explode("\n", $input);

$regexPattern = '/mul\(\d{1,3},\d{1,3}\)/';
$totalSum = 0;

function stripAndMultiply($string) {
    $numbers = explode(',', str_replace(array('mul(', ')'), '', $string));
    return $numbers[0] * $numbers[1];
}

foreach ($lines as $line) {
    if ($line == '') { continue; }

    preg_match_all($regexPattern, $line, $matches);
    
    foreach($matches[0] as $match) {
        $totalSum += stripAndMultiply($match);
    }
}

echo "\nTotal sum: $totalSum\n";
?>