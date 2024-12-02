<?php
$input = file_get_contents('../inputs/day-2.txt');
$lines = explode("\n", $input);

$amountSafeReports = 0;

function isStepValid($isIncrease, $currentLevel, $nextLevel) {
    return (
        ( $currentLevel != $nextLevel ) &&
        ( abs($currentLevel - $nextLevel) <= 3 ) &&
        (
            ( $isIncrease && ($currentLevel < $nextLevel) ) ||
            ( !$isIncrease && ($currentLevel > $nextLevel) )
        )
    );
}

foreach ($lines as $report) {
    if ($report == '') {
        continue;
    }

    $levels = array_map('intval', explode(" ", $report));
    $isIncrease = ($levels[0] < $levels[1]);
    $isValid = true;

    for ($i = 0; $i < count($levels) - 1; $i++) {
        if (!isStepValid($isIncrease, $levels[$i], $levels[$i + 1])) {
            $isValid = false;
            break;
        }
    }

    if($isValid) {
        $amountSafeReports++;
    }
}

echo "\nAmount of safe reports: $amountSafeReports\n";
?>