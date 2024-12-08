<?php
$input = file_get_contents('../inputs/day-8.txt');
$lines = explode("\n", $input);

$antennas = [];
$antinodes = [];

$maxAxisCoordinates = [
    'x' => count(str_split(trim($lines[0]))) - 1,
    'y' => count($lines) - 1,
];

for($yAxisPosition = 0; $yAxisPosition < count($lines); $yAxisPosition++) {
    if($lines[$yAxisPosition] == '') { 
        $maxAxisCoordinates['y'] = $yAxisPosition - 1;
        continue; 
    }

    $lineValues = str_split(trim($lines[$yAxisPosition]));

    foreach($lineValues as $xAxisPosition => $antennaTypeIdentifier) {
        if($antennaTypeIdentifier == '.') { continue; } // symbolizes an empty field

        if(!array_key_exists($antennaTypeIdentifier, $antennas)) {
            $antennas[$antennaTypeIdentifier] = [];
        }

        $antennas[$antennaTypeIdentifier][] = [
            'x' => $xAxisPosition, 
            'y' => $yAxisPosition,
        ];
    }
}

foreach($antennas as $antennaTypeIdentifier => $antennasOfType) {
    if(count($antennasOfType) < 2) { continue; } // need at least 2 antennas to create antinodes

    for($i = 0; $i < count($antennasOfType); $i++) {
        if(!in_array($antennasOfType[$i], $antinodes)) { // antenna places can now be antinodes of their own line as well
            $antinodes[] = $antennasOfType[$i];
        }

        for($j = $i + 1; $j < count($antennasOfType); $j++) {
            $currentPosition = $antennasOfType[$i];
            $coordinateDifference = [
                'x' => $antennasOfType[$i]['x'] - $antennasOfType[$j]['x'],
                'y' => $antennasOfType[$i]['y'] - $antennasOfType[$j]['y'],
            ];

            while(true) { // moving along the line, checking iv we can place an antinode with the given coordinate difference
                $newPosition = [
                    'x' => $currentPosition['x'] + $coordinateDifference['x'],
                    'y' => $currentPosition['y'] + $coordinateDifference['y'],
                ];

                if(
                    $newPosition['x'] < 0 || 
                    $newPosition['x'] > $maxAxisCoordinates['x'] || 
                    $newPosition['y'] < 0 || 
                    $newPosition['y'] > $maxAxisCoordinates['y']
                ) {
                    break;
                }

                if(!in_array($newPosition, $antinodes)) {
                    $antinodes[] = $newPosition;
                }

                $currentPosition = $newPosition;
            }

            $currentPosition = $antennasOfType[$i];

            while(true) { // doning the same as the above loop, but moving in the opposite direction
                $newPosition = [
                    'x' => $currentPosition['x'] - $coordinateDifference['x'],
                    'y' => $currentPosition['y'] - $coordinateDifference['y'],
                ];

                if(
                    $newPosition['x'] < 0 || 
                    $newPosition['x'] > $maxAxisCoordinates['x'] || 
                    $newPosition['y'] < 0 || 
                    $newPosition['y'] > $maxAxisCoordinates['y']
                ) {
                    break;
                }

                if(!in_array($newPosition, $antinodes)) {
                    $antinodes[] = $newPosition;
                }

                $currentPosition = $newPosition;
            }
        }
    }
}

echo '<br />Antinodes amount: '. count($antinodes). '<br />';
?>