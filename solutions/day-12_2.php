<?php
$input = file_get_contents('../inputs/day-12.txt');
$lines = explode("<br />", $input);
$gardenLayout = [];
$symbolBorder = '#';
$gardenAreas = [];

foreach($lines as $line) {
    if($line == '') { continue; }
    $gardenLayout[] = array_merge([$symbolBorder], str_split(trim($line)), [$symbolBorder]);
}

$borderRow = array_fill(0, count($gardenLayout[0]), $symbolBorder);
$gardenLayout = array_merge([$borderRow], $gardenLayout, [$borderRow]);

$currentSymbol = $symbolBorder;
$currentAmountArea = 0;
$currentAmountPerimeter = 0;
$visited = [];
$visitedPerimeter = [];
$totalPerimeterPrice = 0;

function getAmountOfNeighbours($xAxis, $yAxis) {
    global $gardenLayout, $symbolBorder, $currentSymbol, $currentAmountArea, $currentAmountPerimeter, $visited;

    $stack = [[$xAxis, $yAxis]];
    $visited[$yAxis][$xAxis] = true; // mark as visited

    while ($stack) {
        // work of firstmost element in the stack
        list($x, $y) = array_shift($stack);

        // check if the current plot (cell) is part of the current area
        if ($gardenLayout[$y][$x] == $currentSymbol) {
            $currentAmountArea++;

            $potentialNeighbours = [
                [$y - 1, $x],
                [$y + 1, $x],
                [$y, $x - 1],
                [$y, $x + 1],
            ];

            foreach ($potentialNeighbours as $neighbour) {
                list($ny, $nx) = $neighbour;

                if (isset($gardenLayout[$ny][$nx]) && $gardenLayout[$ny][$nx] != $currentSymbol) {
                    // If the neighboring cell is a different crop, increment the perimeter count
                    $currentAmountPerimeter++;
                } elseif (isset($gardenLayout[$ny][$nx]) && $gardenLayout[$ny][$nx] == $currentSymbol && !isset($visited[$ny][$nx])) {
                    // If the neighboring cell is the same crop and hasn't been visited, add it to the stack
                    $stack[] = [$nx, $ny];
                    $visited[$ny][$nx] = true; // mark as visited
                }
            }
        }
    }
}

function getPerimeter($symbol, $xAxis, $yAxis) {
    global $gardenLayout, $symbolBorder, $visitedPerimeter;

    echo "<br />start with symbol: $symbol, xAxis: $xAxis, yAxis: $yAxis";

    $tempX = $xAxis;
    $tempY = $yAxis;
    $perimeterCount = 0;
    $direction = 0; // 0: right, 1: down, 2: left, 3: up

    while(true) {
        $nextStep = getNextStep($tempX, $tempY, $direction);

        if( isset($gardenLayout[$nextStep['yAxis']][$nextStep['xAxis']]) &&
            $gardenLayout[$nextStep['yAxis']][$nextStep['xAxis']] == $symbol
        ) {
            // next plot in current direction still belongs to same area
            echo "<br />next plot in current direction ( tempX: $nextStep[xAxis], tempY: $nextStep[yAxis], direction: $direction) still belongs to same area";

            // take a step forward
            $tempX = $nextStep['xAxis'];
            $tempY = $nextStep['yAxis'];

            // end round-trip if we are back at the starting position
            if($tempX == $xAxis && $tempY == $yAxis) {
                echo "<br />arrived back at the starting position ( tempX: $tempX, tempY: $tempY, direction: $direction)";
                break;
            }

        } else {
            // take a turn to the right
            $perimeterCount++;
            $direction = ($direction + 1) % 4;
            echo "<br />take a turn to the right ( tempX: $tempX, tempY: $tempY, direction: $direction)";
        }
    }

    return $perimeterCount;
}

function getNextStep($xAxis, $yAxis, $direction = 0) {
    switch($direction) {
        case 0:
            $xAxis++;
            break;
        case 1:
            $yAxis++;
            break;
        case 2:
            $xAxis--;
            break;
        case 3:
            $yAxis--;
            break;
    }

    return [
        'xAxis' => $xAxis, 
        'yAxis' => $yAxis,
    ];
}

foreach ($gardenLayout as $yAxis => $row) {
    foreach ($row as $xAxis => $gardenPlot) {
        if ($gardenPlot == $symbolBorder) {
            continue;
        }

        if (isset($visited[$yAxis][$xAxis])) {
            continue; // already visited as part of an existing area
        }

        $currentSymbol = $gardenPlot;
        $currentAmountArea = 0;
        $currentAmountPerimeter = 0;

        getAmountOfNeighbours($xAxis, $yAxis);
        $currentAmountPerimeter = getPerimeter($currentSymbol, $xAxis, $yAxis);

        $gardenAreas[$currentSymbol] ??= [];
        $gardenAreas[$currentSymbol][] = [
            'area' => $currentAmountArea,
            'perimeter' => $currentAmountPerimeter,
        ];

        $totalPerimeterPrice += ($currentAmountArea * $currentAmountPerimeter);
    }
}

echo '<pre>';
print_r($gardenAreas);
// echo "<br />Total perimeter price: $totalPerimeterPrice<br />";
?>