<?php
$input = file_get_contents('../inputs/day-10.txt');
$lines = explode("\n", $input);
$symbolBorder = 'B';
$hikingTrailsMap = [];
$hikingTrails = [];
$totalAmountOfTrailheads = 0;
$cache = []; // for memoization

foreach($lines as $line) {
    if($line == '') { continue; }

    $hikingTrailsMap[] = array_merge([$symbolBorder], array_map('intval', str_split(trim($line))), [$symbolBorder]);
}

$borderRow = array_fill(0, count($hikingTrailsMap[0]), $symbolBorder);
$hikingTrailsMap = array_merge([$borderRow], $hikingTrailsMap, [$borderRow]);

function getLocationsOfNine($x, $y, $currentHeight, &$hikingTrailsMap) {
    global $cache;
    $key = $x . '/' . $y . '/' . $currentHeight;
    $endpoints = [];

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    // base case: if current position is 9, return it
    if ($hikingTrailsMap[$y][$x] === 9) {
        $endpoints[] = [
            'x' => $x,
            'y' => $y,
        ];

        $cache[$key] = $endpoints;
        return $endpoints;
    }

    // recursive case: explore neighbors
    $neighbors = [
        [$x - 1, $y], // left
        [$x + 1, $y], // right
        [$x, $y - 1], // up
        [$x, $y + 1], // down
    ];

    foreach ($neighbors as $neighbor) {
        list($nx, $ny) = $neighbor;

        if ($hikingTrailsMap[$ny][$nx] === $hikingTrailsMap[$y][$x] + 1) {
            $result = getLocationsOfNine($nx, $ny, $currentHeight + 1, $hikingTrailsMap);
            $endpoints = array_merge($endpoints, $result);
            $endpoints = array_unique($endpoints, SORT_REGULAR);
            $cache[$key] = $endpoints;
        }
    }

    return $endpoints;
}

for($y = 1; $y < count($hikingTrailsMap) - 1; $y++) {
    for($x = 1; $x < count($hikingTrailsMap[$y]) - 1; $x++) {
        if($hikingTrailsMap[$y][$x] === 0) {
            $trailHeads = getLocationsOfNine($x, $y, 0, $hikingTrailsMap);
            // $hikingTrails['('.$x.'/'.$y.')'] = $trailHeads;
            $totalAmountOfTrailheads += count($trailHeads);
        }
    }
}

// echo '<pre>';
// print_r($hikingTrails);
echo "<br />Amount of trailheads: " . $totalAmountOfTrailheads . "<br />";
?>