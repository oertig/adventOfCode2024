<?php
$input = trim(file_get_contents('../inputs/day-11.txt'));
$stones = array_map('intval', explode(" ", $input));
$amountOfBlinks = 75;
$amountOfStones = 0;
$cache = []; // memoization

function getAmountOfNewStones($currentStone, $amountOfQueuedBlinks) {
    if($amountOfQueuedBlinks === 0) {
        return 1;
    }

    global $cache;

    if(
        isset($cache[$currentStone]) && 
        isset($cache[$currentStone][$amountOfQueuedBlinks]) // holds the value of amount of substones it would create with this many blinks left
    ) {
        return $cache[$currentStone][$amountOfQueuedBlinks];
    } else {
        $cache[$currentStone] ??= [];
        $cache[$currentStone][$amountOfQueuedBlinks] = 0;
    }

    switch(true) {
        case $currentStone === 0:
            $nextStones = [1];
            break;

        case strlen(strval($currentStone)) % 2 === 0:
            $halfLength = strlen(strval($currentStone)) / 2;
            $nextStones = [
                intval(substr(strval($currentStone), 0, $halfLength)), 
                intval(substr(strval($currentStone), $halfLength)),
            ];
            break;

        default:
            $nextStones = [$currentStone * 2024];
            break;
    }

    //nextStones now holds 1 or 2 elements with a new number

    $amountSubStones = 0;
    foreach($nextStones as $nextStone) {
        $amountSubStones += getAmountOfNewStones($nextStone, $amountOfQueuedBlinks - 1);
    }

    $cache[$currentStone][$amountOfQueuedBlinks] = $amountSubStones;
    return $amountSubStones;
}

foreach($stones as $stone) {
    $amountOfStones += getAmountOfNewStones($stone, $amountOfBlinks);
}

echo "<br />Amount of stones: $amountOfStones<br />";
?>