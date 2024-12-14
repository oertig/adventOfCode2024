<?php
$input = trim(file_get_contents('../inputs/day-14.txt'));
$lines = explode("\n", $input);
$amountOfIterations = 100;
$robots = [];
$gridSize = [
    'x' => 101,
    'y' => 103,
];
$quadrantSize = [
    'x' => floor($gridSize['x'] / 2),
    'y' => floor($gridSize['y'] / 2),
];
$quadrants = [
    'top-left' => 0,
    'top-right' => 0,
    'bottom-left' => 0,
    'bottom-right' => 0,
];

foreach($lines as $line) {
    preg_match_all("/-?\d+/", $line, $matches);

    $robots[] = [
        'positionX' => (int)$matches[0][0],
        'positionY' => (int)$matches[0][1],
        'velocityX' => (int)$matches[0][2],
        'velocityY' => (int)$matches[0][3],
    ];
}

// reposition robots
foreach($robots as $key => $robot) {
    for($i = 0; $i < $amountOfIterations; $i++) {
        $newPositionX = $robots[$key]['positionX'] + $robot['velocityX'];
        $newPositionY = $robots[$key]['positionY'] + $robot['velocityY'];

        if($newPositionX >= $gridSize['x']) {
            $newPositionX -= $gridSize['x'];
        } elseif($newPositionX < 0) {
            $newPositionX += $gridSize['x'];
        }

        if($newPositionY >= $gridSize['y']) {
            $newPositionY -= $gridSize['y'];
        } elseif($newPositionY < 0) {
            $newPositionY += $gridSize['y'];
        }

        $robots[$key]['positionX'] = $newPositionX;
        $robots[$key]['positionY'] = $newPositionY;
    }
}

// count amount of robots per quadrant
foreach($robots as $robot) {
    if($robot['positionX'] < $quadrantSize['x']) {
        if($robot['positionY'] < $quadrantSize['y']) {
            $quadrants['top-left']++;
        } else if($robot['positionY'] > $quadrantSize['y']) {
            $quadrants['bottom-left']++;
        }
    } else if($robot['positionX'] > $quadrantSize['x']) {
        if($robot['positionY'] < $quadrantSize['y']) {
            $quadrants['top-right']++;
        } else if($robot['positionY'] > $quadrantSize['y']) {
            $quadrants['bottom-right']++;
        }
    }
}

echo '<pre>';
print_r($quadrants);
echo '</pre>';
echo "<br />Safety factor is: " . $quadrants['top-left'] * $quadrants['top-right'] * $quadrants['bottom-left'] * $quadrants['bottom-right'];
?>