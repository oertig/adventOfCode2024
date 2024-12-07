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

function isSolveableEquation(&$wantedResult, &$operands) {
    $operators = ['+', '*'];
    $permutations = [];
    $queue = [strval($operands[0])];

    for ($i = 1; $i < count($operands); $i++) {
        $tempQueue = [];

        while(!empty($queue)) {
            $currentPermutation = array_shift($queue);

            foreach($operators as $operator) {
                $newQueue = '(' . $currentPermutation . ' ' . $operator . ' ' . strval($operands[$i]) . ')';
                $tempQueue[] = $newQueue;
            }
        }

        $queue = $tempQueue;
    }

    foreach($queue as $permutation) {
        if( eval("return $permutation;") == $wantedResult ) {
            return true;
        }
    }

    return false;
}

foreach($equations as $equation) {
    if(isSolveableEquation($equation['result'], $equation['operands'])) {
        $sumOfSolveableEquations += $equation['result'];
    }
}

echo "<br />Sum of solveable equations: $sumOfSolveableEquations<br />";
?>