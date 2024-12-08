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

// echo '<pre>';
// print_r($antennas);
// die;

$filteredOutAntinodes = [];

foreach($antennas as $antennaTypeIdentifier => $antennasOfType) {
    if(count($antennasOfType) < 2) { continue; } // need at least 2 antennas to create antinodes

    for($i = 0; $i < count($antennasOfType); $i++) {
        for($j = $i + 1; $j < count($antennasOfType); $j++) {
            $tempAntinodes = [];

            $coordinateDifference = [
                'x' => $antennasOfType[$i]['x'] - $antennasOfType[$j]['x'],
                'y' => $antennasOfType[$i]['y'] - $antennasOfType[$j]['y'],
            ];
            
            $tempAntinodes[] = [
                'x' => $antennasOfType[$i]['x'] + $coordinateDifference['x'],
                'y' => $antennasOfType[$i]['y'] + $coordinateDifference['y'],
            ];

            $tempAntinodes[] = [
                'x' => $antennasOfType[$i]['x'] - $coordinateDifference['x'],
                'y' => $antennasOfType[$i]['y'] - $coordinateDifference['y'],
            ];

            $tempAntinodes[] = [
                'x' => $antennasOfType[$j]['x'] + $coordinateDifference['x'],
                'y' => $antennasOfType[$j]['y'] + $coordinateDifference['y'],
            ];

            $tempAntinodes[] = [
                'x' => $antennasOfType[$j]['x'] - $coordinateDifference['x'],
                'y' => $antennasOfType[$j]['y'] - $coordinateDifference['y'],
            ];

            foreach($tempAntinodes as $tempAntinode) {
                if( // antinode is inbetween the two antennas (not allowed)
                    $tempAntinode['x'] >= min($antennasOfType[$i]['x'], $antennasOfType[$j]['x']) &&
                    $tempAntinode['x'] <= max($antennasOfType[$i]['x'], $antennasOfType[$j]['x']) &&
                    $tempAntinode['y'] >= min($antennasOfType[$i]['y'], $antennasOfType[$j]['y']) &&
                    $tempAntinode['y'] <= max($antennasOfType[$i]['y'], $antennasOfType[$j]['y'])
                ) {
                    // $filteredOutAntinodes[] = [
                    //     'x' => $tempAntinode['x'],
                    //     'y' => $tempAntinode['y'],
                    //     'reason' => 'inside antennas',
                    // ];
                } else if( // antinode is in bounds
                    $tempAntinode['x'] >= 0 &&
                    $tempAntinode['x'] <= $maxAxisCoordinates['x'] &&
                    $tempAntinode['y'] >= 0 &&
                    $tempAntinode['y'] <= $maxAxisCoordinates['y']
                ) {
                    if(!in_array($tempAntinode, $antinodes)) {
                        $antinodes[] = $tempAntinode;
                    } else { // in bounds, but position already found
                        // $filteredOutAntinodes[] = [
                        //     'x' => $tempAntinode['x'],
                        //     'y' => $tempAntinode['y'],
                        //     'reason' => 'duplicate',
                        // ];
                    }
                } else { // antinode is out of bounds
                    // $filteredOutAntinodes[] = [
                    //     'x' => $tempAntinode['x'],
                    //     'y' => $tempAntinode['y'],
                    //     'reason' => 'out of bounds',
                    // ];
                }
            }
        }
    }
}

echo '<br />Antinodes amount: '. count($antinodes). '<br />';
// echo '<pre>';
// print_r($antinodes);
// echo '<hr />';
// print_r($filteredOutAntinodes);
?>