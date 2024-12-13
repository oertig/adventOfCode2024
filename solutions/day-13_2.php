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
function solveLinearEquationSystem($ax, $ay, $bx, $by, $px, $py) {
    $positionOffset = 10000000000000;
    $px += $positionOffset;
    $py += $positionOffset;

    $determinant = $ax * $by - $ay * $bx;
    $a = ($px * $by - $py * $bx) / $determinant;
    $b = ($ax * $py - $ay * $px) / $determinant;

    if(
        (is_int($a) && is_int($b)) &&
        ($ax * $a + $bx * $b) == $px &&
        ($ay * $a + $by * $b) == $py
    ) {
        return [
            'a' => $a,
            'b' => $b,
        ];
    }

    throw new Exception('No solution found');
}

foreach($machines as $machineNumber => $machine) {
    $tokenCost = 0;

    try {
        list('a' => $aButtonPressCount, 'b' => $bButtonPressCount) = solveLinearEquationSystem(
            $machine['a']['x'],
            $machine['a']['y'],
            $machine['b']['x'],
            $machine['b']['y'],
            $machine['prize']['x'],
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