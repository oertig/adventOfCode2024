<?php
$input = trim(file_get_contents('../inputs/day-13.txt'));
$lines = explode("\n", $input);
$machines = [];
$totalTokenCost = 0;
$tokenCosts = [
    'a' => 3,
    'b' => 1
];

for($i = 0; $i < count($lines); $i += 4) {
    preg_match_all('/\d+/', $lines[$i], $matchesA);
    preg_match_all('/\d+/', $lines[$i + 1], $matchesB);
    preg_match_all('/\d+/', $lines[$i + 2], $matchesPrize);

    $machines[] = [
        'a' => [
            'x' => (int) $matchesA[0][0],
            'y' => (int) $matchesA[0][1],
        ],
        'b' => [
            'x' => (int) $matchesB[0][0],
            'y' => (int) $matchesB[0][1],
        ],
        'prize' => [
            'x' => (int) $matchesPrize[0][0],
            'y' => (int) $matchesPrize[0][1],
        ],
    ];
}

// echo '<pre>';
// print_r($machines);
// die;

/**
 * Only for systems with (a,b,c) and 2 equations
 * 
 * @throws Exception
 */
function solveLinearEquationSystem($x1, $y1, $z1, $x2, $y2, $z2) {
    $maxButtonPress = 100;

    for ($a = 0; $a <= $maxButtonPress; $a++) {
        for ($b = 0; $b <= $maxButtonPress; $b++) {
            if ($x1 * $a + $x2 * $b == $z1 && $y1 * $a + $y2 * $b == $z2) {
                return [
                    'a' => $a,
                    'b' => $b,
                ];
            }
        }
    }

    throw new Exception('No solution found');
}

foreach($machines as $machineNumber => $machine) {
    $tokenCost = 0;

    try {
        list('a' => $aButtonPressCount, 'b' => $bButtonPressCount) = solveLinearEquationSystem(
            $machine['a']['x'],
            $machine['a']['y'],
            $machine['prize']['x'],
            $machine['b']['x'],
            $machine['b']['y'],
            $machine['prize']['y'],
        );

        $tokenCost = $aButtonPressCount * $tokenCosts['a'] + $bButtonPressCount * $tokenCosts['b'];

        echo "Machine $machineNumber: A button press count: " . $aButtonPressCount . ", B button press count: " . $bButtonPressCount . "<br />";
        echo "Machine $machineNumber: Total token cost: " . $tokenCost . "<br />";
    } catch(Exception $e) {
        echo "No solution for machine number $machineNumber<br />";
        continue;
    } finally {
        $totalTokenCost += $tokenCost;
    }
}

echo "<br />Total token cost: " . $totalTokenCost . "<br />";
?>