<?php
$input = file_get_contents('../inputs/day-1.txt');
$lines = explode("\n", $input);
$inputs = [];

foreach($lines as $line) {
    list($column1, $column2) = explode("   ", $line);
    $inputs[] = compact('column1', 'column2');
}

$columns1 = array_column($inputs, 'column1');
$columns2 = array_column($inputs, 'column2');

sort($columns1);
sort($columns2);

foreach($columns1 as $key => $value) {
    $inputs[$key]['column1'] = $value;
    $inputs[$key]['column2'] = $columns2[$key];
    $inputs[$key]['distance'] = $columns2[$key] - $value;
}

// echo '<pre>';
// for($i = 0; $i < 10; $i++) {
//     print_r($inputs[$i]);
// }

$totalDistance = array_sum(array_map('abs', array_column($inputs, 'distance')));
echo "\nTotal distance: $totalDistance\n";
?>