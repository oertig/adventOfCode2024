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
    $operators = ['+', '*', '||'];
    $queue = [[$operands[0]]];

    for ($i = 1; $i < count($operands); $i++) {
        $tempQueue = [];

        while(!empty($queue)) {
            $currentExpression = array_shift($queue);

            foreach($operators as $operator) {
                $newExpression = [];
                foreach($currentExpression as $element) {
                    $newExpression[] = $element;
                }
                
                $newExpression[] = $operator;
                $newExpression[] = $operands[$i];

                if ($i == count($operands) - 1) {
                    // If this is the last operand, evaluate the expression
                    if (evaluateExpression($newExpression) == $wantedResult) {
                        return true;
                    }
                } else {
                    // Otherwise, add the new expression to the queue
                    $tempQueue[] = $newExpression;
                }
            }
        }

        $queue = $tempQueue;
    }

    return false;
}

function evaluateExpression($expression) {
    $result = $expression[0];

    for ($i = 1; $i < count($expression); $i += 2) {
        $operator = $expression[$i];
        $operand = $expression[$i + 1];

        if ($operator == '+') {
            $result += $operand;
        } elseif ($operator == '*') {
            $result *= $operand;
        } elseif ($operator == '||') {
            $result = intval(strval($result) . strval($operand));
        }
    }

    return $result;
}

foreach($equations as $equation) {
    if(isSolveableEquation($equation['result'], $equation['operands'])) {
        // echo '<br />Equation: ' . implode(' ', $equation['operands']) . ' = ' . $equation['result'];
        $sumOfSolveableEquations += $equation['result'];
    }
}

echo "<br />Sum of solveable equations: $sumOfSolveableEquations<br />";
?>