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

$initialStartingPosition = $startingPosition;

function isFacingObstacle($x, $y, $direction, &$patrolMap) {
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

    while(isFacingObstacle($startingPosition['x'], $startingPosition['y'], $startingPosition['direction'], $patrolMap)) {
        $startingPosition['direction'] = turnRight($startingPosition['direction']);
    }

    $startingPosition = moveForward($startingPosition['x'], $startingPosition['y'], $startingPosition['direction']);
}

$originalVisitedFields = [];

foreach($patrolMapShadowCopy as $y => $row) {
    foreach($row as $x => $cell) {
        if($cell == $symbolVisited) {
            if($x == $initialStartingPosition['x'] && $y == $initialStartingPosition['y']) { 
                continue; // can't place obstacle at starting position
            }

            $originalVisitedFields[] = ['x' => $x, 'y' => $y];
        }
    }
}

/*****************************************************************************************************************************/

$amountInfiniteLoopsFound = 0;

foreach($originalVisitedFields as $originalVisitedField) {
    $obstacledPatrolMap = $patrolMap;
    $obstacledPatrolMap[$originalVisitedField['y']][$originalVisitedField['x']] = $symbolObstacle;
    $startingPosition = $initialStartingPosition;

    $obstacledPatrolMapVisitedFields = [];

    // build a map to hold all our already performed movements
    foreach($obstacledPatrolMap as $y => $row) {
        foreach($row as $x => $cell) {
            $obstacledPatrolMapVisitedFields[$y][$x] = [];
        }
    }

    while($obstacledPatrolMap[$startingPosition['y']][$startingPosition['x']] != $symbolBorder) {
        while(isFacingObstacle($startingPosition['x'], $startingPosition['y'], $startingPosition['direction'], $obstacledPatrolMap)) {
            $startingPosition['direction'] = turnRight($startingPosition['direction']); // turn right until there is no obstacle in front
        }

        $newPosition = moveForward($startingPosition['x'], $startingPosition['y'], $startingPosition['direction']);

        if(in_array($newPosition['direction'], $obstacledPatrolMapVisitedFields[$newPosition['y']][$newPosition['x']])) {
            // we are about to make a repeating movement, so we know we are entering an infinite loop
            $amountInfiniteLoopsFound++;
            break;
        }

        // save the movement we just made
        $obstacledPatrolMapVisitedFields[$newPosition['y']][$newPosition['x']][] = $newPosition['direction'];

        // remember our new position as the starting position for next iteration
        $startingPosition = $newPosition;
    }
}

echo "<br />Amount of infinite loops found: $amountInfiniteLoopsFound<br />";
?>