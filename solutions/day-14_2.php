<?php
$input = trim(file_get_contents('../inputs/day-14.txt'));
$lines = explode("\n", $input);
$amountOfIterations = 10000;
$robots = [];
$gridSize = [
    'x' => 101,
    'y' => 103,
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
for($i = 0; $i < $amountOfIterations; $i++) {
    foreach($robots as $key => $robot) {
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

    generateImage($robots, $gridSize, $i);
}

// manual labor required :(
function generateImage(&$robots, &$gridSize, &$iterationCount) {
    $image = imagecreatetruecolor($gridSize['x'], $gridSize['y']); // create image with dimensions of the grid
    imagefill($image, 0, 0, imagecolorallocate($image, 0, 0, 0)); // set background color to black
    $robotColor = imagecolorallocate($image, 255, 255, 255);

    foreach($robots as $robot) {
        // position each robot as a pixel
        imagesetpixel($image, $robot['positionX'], $robot['positionY'], $robotColor);
    }

    $directory = '../images/day-14/';

    if(!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }

    imagepng($image, sprintf($directory . '%04d.png', ($iterationCount) + 1));
    imagedestroy($image);
}
?>