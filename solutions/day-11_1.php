<?php
$input = trim(file_get_contents('../inputs/day-11.txt'));
$stones = array_map('intval', explode(" ", $input));
$amountOfBlinks = 25;

for($i = 0; $i < $amountOfBlinks; $i++) {
    $tempStones = [];

    foreach($stones as $stone) {
        switch(true) {
            case $stone === 0:
                $tempStones[] = 1;
                break;

            case strlen(strval($stone)) % 2 === 0:
                $halfLength = strlen(strval($stone)) / 2;
                $tempStones[] = intval(substr(strval($stone), 0, $halfLength)); // first half of the number
                $tempStones[] = intval(substr(strval($stone), $halfLength)); // second half of the number
                break;

            default:
                $tempStones[] = $stone * 2024;
                break;
        }
    }

    // echo "round " . ($i + 1) . "<br />";
    // echo implode(" ", $tempStones) . "<br />";
    // echo '<hr />';
    $stones = $tempStones;
}

echo "amount of stones: " . count($stones) . "<br />";
?>