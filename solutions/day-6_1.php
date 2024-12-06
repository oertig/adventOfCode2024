<?php
$input = file_get_contents('../inputs/day-6.txt');
$lines = explode("\n", $input);
$patrolMap = [];
$patrolMapShadowCopy = [];

$symbolBorder = 'B';
$symbolVisited = 'X';
$symbolObstacle = '#';
$symbolDirections = [
    'up' => '^', 
    'down' => 'v', 
    'left' => '<', 
    'right' => '>',
];
$startingPosition = [
    'x' => 0, // horizontal
    'y' => 0, // vertical
    'direction' => 'up',
];

// build patrolMap
foreach($lines as $line) {
    if($line == '') { continue; }

    $patrolMap[] = array_merge([$symbolBorder], str_split(trim($line)), [$symbolBorder]);
}

$borderRow = array_fill(0, count($patrolMap[0]), $symbolBorder);
$patrolMap = array_merge([$borderRow], $patrolMap, [$borderRow]);
$patrolMapShadowCopy = $patrolMap;

// find starting position
foreach($patrolMap as $y => $row) {
    foreach($row as $x => $cell) {
        if($cell == $symbolDirections['up']) {
            $startingPosition['x'] = $x;
            $startingPosition['y'] = $y;
            break 2;
        }
    }
}

function isFacingObstacle($x, $y, $direction) {
    global $patrolMap;
    global $symbolObstacle;

    switch($direction) {
        case 'up':
            return $patrolMap[$y - 1][$x] == $symbolObstacle;
            break;
        case 'down':
            return $patrolMap[$y + 1][$x] == $symbolObstacle;
            break;
        case 'left':
            return $patrolMap[$y][$x - 1] == $symbolObstacle;
            break;
        case 'right':
            return $patrolMap[$y][$x + 1] == $symbolObstacle;
            break;
        default:
            return false;
            break;
    }
}

function turnRight($direction) {
    switch($direction) {
        case 'up':
            return 'right';
            break;
        case 'down':
            return 'left';
            break;
        case 'left':
            return 'up';
            break;
        case 'right':
            return 'down';
            break;
        default:
            return false;
            break;
    }
}

function moveForward($x, $y, $direction) {
    switch($direction) {
        case 'up':
            return [
                'x' => $x, 
                'y' => $y - 1,
                'direction' => $direction
            ];
            break;
        case 'down':
            return [
                'x' => $x, 
                'y' => $y + 1,
                'direction' => $direction
            ];
            break;
        case 'left':
            return [
                'x' => $x - 1, 
                'y' => $y,
                'direction' => $direction
            ];
            break;
        case 'right':
            return [
                'x' => $x + 1, 
                'y' => $y,
                'direction' => $direction
            ];
            break;
        default:
            return [
                'x' => $x, 
                'y' => $y,
                'direction' => $direction
            ];
            break;
    }
}

// move guard on the map
while($patrolMap[$startingPosition['y']][$startingPosition['x']] != $symbolBorder) {
    $patrolMapShadowCopy[$startingPosition['y']][$startingPosition['x']] = $symbolVisited;

    while(isFacingObstacle($startingPosition['x'], $startingPosition['y'], $startingPosition['direction'])) {
        $startingPosition['direction'] = turnRight($startingPosition['direction']);
    }

    $startingPosition = moveForward($startingPosition['x'], $startingPosition['y'], $startingPosition['direction']);
}

$amountVisitedFields = array_reduce($patrolMapShadowCopy, function ($carry, $row) {
    global $symbolVisited;
    $carry += array_count_values($row)[$symbolVisited] ?? 0;
    return $carry;
}, 0);

echo "<br />Total amount of visited fields: $amountVisitedFields<br />";
?>