<?php
$input = file_get_contents('../inputs/day-12.txt');
$lines = explode("\n", $input);
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

        $gardenAreas[$currentSymbol] ??= [];
        $gardenAreas[$currentSymbol][] = [
            'area' => $currentAmountArea,
            'perimeter' => $currentAmountPerimeter,
        ];

        $totalPerimeterPrice += ($currentAmountArea * $currentAmountPerimeter);
    }
}

// echo '<pre>';
// print_r($gardenAreas);
echo "<br />Total perimeter price: $totalPerimeterPrice<br />";
?>