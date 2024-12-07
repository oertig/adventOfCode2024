<?php
$input = file_get_contents('../inputs/day-7.txt');
$lines = explode("\n", $input);
$sumOfSolveableEquations = 0;
$equations = [];

foreach($lines as $line) {
    if($line == '') { continue; }

    $parts = explode(': ', trim($line));

    $equations[] = [
        'result' => (int)$parts[0],
        'operands' => array_map('intval', explode(' ', $parts[1])),
    ];
}

function isSolveableEquation($wantedResult, &$operands) {
    if(count($operands) == 2) {
        return (
            $operands[0] + $operands[1] == $wantedResult ||
            $operands[0] * $operands[1] == $wantedResult
        );
    }

    $lastElement = array_pop($operands);

    return (
        isSolveableEquation( ($wantedResult - $lastElement), $operands ) ||
        (
            $wantedResult % $lastElement == 0 &&
            isSolveableEquation( ($wantedResult / $lastElement), $operands )
        )
    );

    return false;
}

foreach($equations as $equation) {
    $minValue = 0;
    $maxValue = 1;

    foreach($equation['operands'] as $operand) {
        $minValue += $operand;
        $maxValue *= $operand;
    }

    if($equation['result'] < $minValue || $equation['result'] > $maxValue) {
        // echo 'equation exceeds bounds: ' . implode(' ', $equation['operands']) . ' = ' . $equation['result'] . "<br />";
        continue;
    } else if(isSolveableEquation($equation['result'], $equation['operands'])) {
        // echo 'equation is solveable: ' . implode(' ', $equation['operands']) . ' = ' . $equation['result'] . "<br />";
        $sumOfSolveableEquations += $equation['result'];
    } else {
        // echo 'equation is not solveable: ' . implode(' ', $equation['operands']) . ' = ' . $equation['result'] . "<br />";
    }
}

echo "<br />Sum of solveable equations: $sumOfSolveableEquations<br />";
?>