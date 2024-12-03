<?php
$input = file_get_contents('../inputs/day-3.txt');
$lines = explode("\n", $input);

$regexPattern = '/mul\(\d{1,3},\d{1,3}\)|do\(\)|don\'t\(\)/';
$totalSum = 0;
$isDo = true;

function stripAndMultiply($string) {
    $numbers = explode(',', str_replace(array('mul(', ')'), '', $string));
    return $numbers[0] * $numbers[1];
}

foreach ($lines as $line) {
    if ($line == '') { continue; }

    preg_match_all($regexPattern, $line, $matches);
    
    foreach($matches[0] as $match) {
        if($match === 'do()') {
            $isDo = true;
        } else if($match === 'don\'t()') {
            $isDo = false;
        } else {
            if($isDo) {
                $totalSum += stripAndMultiply($match);
            }
        }
    }
}

echo "\nTotal sum: $totalSum\n";
?>