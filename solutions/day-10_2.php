<?php
$input = file_get_contents('../inputs/day-10.txt');
$lines = explode("\n", $input);
$symbolBorder = 'B';
$hikingTrailsMap = [];
$hikingTrails = [];
$totalAmountOfPaths = 0;
$cache = []; // for memoization

foreach($lines as $line) {
    if($line == '') { continue; }

    $hikingTrailsMap[] = array_merge([$symbolBorder], array_map('intval', str_split(trim($line))), [$symbolBorder]);
}

$borderRow = array_fill(0, count($hikingTrailsMap[0]), $symbolBorder);
$hikingTrailsMap = array_merge([$borderRow], $hikingTrailsMap, [$borderRow]);

function getPathstoNine($x, $y, $currentHeight, &$hikingTrailsMap) {
    global $cache;
    $key = $x . '/' . $y . '/' . $currentHeight;
    $paths = [];

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    // base case: if current position is 9, return it
    if ($hikingTrailsMap[$y][$x] === 9) {
        $paths[] = [
            'path' => [['x' => $x, 'y' => $y]],
        ];

        $cache[$key] = $paths;
        return $paths;
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
            $result = getPathsToNine($nx, $ny, $currentHeight + 1, $hikingTrailsMap);

            foreach ($result as $path) {
                $newPath = [['x' => $x, 'y' => $y]];
                $newPath = array_merge($newPath, $path['path']);
                $paths[] = ['path' => $newPath];
            }
        }
    }

    $cache[$key] = $paths;
    return $paths;
}

for($y = 1; $y < count($hikingTrailsMap) - 1; $y++) {
    for($x = 1; $x < count($hikingTrailsMap[$y]) - 1; $x++) {
        if($hikingTrailsMap[$y][$x] === 0) {
            $pathsToNine = getPathsToNine($x, $y, 0, $hikingTrailsMap);

            // echo "<br />Amount of paths to 9: " . count($pathsToNine) . "<br />";
            $totalAmountOfPaths += count($pathsToNine);
        }
    }
}

echo "<br />Amount of paths: " . $totalAmountOfPaths . "<br />";
?>