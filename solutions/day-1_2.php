<?php
$input = file_get_contents('../inputs/day-1.txt');
$lines = explode("\n", $input);

$firstColumnCounts = [];
$secondColumnCounts = [];
$totalAmount = 0;

foreach($lines as $line) {
    if($line == '') {
        continue;
    }

    list($column1, $column2) = array_map('trim', explode("   ", $line));
    
    if(!array_key_exists($column1, $firstColumnCounts)) {
        $firstColumnCounts[$column1] = 0;
    }

    $firstColumnCounts[$column1]++;

    if(!array_key_exists($column2, $secondColumnCounts)) {
        $secondColumnCounts[$column2] = 0;
    }

    $secondColumnCounts[$column2]++;
}

// echo '<pre>';
// print_r($firstColumnCounts);
// print_r($secondColumnCounts);
// die;

foreach($firstColumnCounts as $firstValue => $firstValueCount) {
    // echo 'line amount: ' . $firstValue * $firstValueCount * ($secondColumnCounts[$firstValue] ?? 0) . "<br />";
    $totalAmount += $firstValue * $firstValueCount * ($secondColumnCounts[$firstValue] ?? 0);
}

echo "<br />Total amount: $totalAmount<br />";
?>