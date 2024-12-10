<?php
$input = trim(file_get_contents('../inputs/day-9.txt'));
$inputs = str_split($input);
$filesOnDisk = [];

for ($i = 0; $i < count($inputs); $i += 2) {
    $filesOnDisk[] = [
        'fileIdentifier' => $i / 2,
        'lengthOfBlock' => intval($inputs[$i]),
        'followingFreeSpace' => intval($inputs[$i + 1] ?? 0),
        'hasBeenMoved' => false,
    ];
}

$amountFilesOnDisk = count($filesOnDisk);

for($i = $amountFilesOnDisk - 1; $i > 0; $i--) { // iterate over all files from right to left (except first one from the left)
    if($filesOnDisk[$i]['hasBeenMoved']) {
        continue; // skip files that have already been moved
    }

    for($j = 0; $j < $i; $j++) { // iterate over files from left to right until the file from the outer loop is encountered
        if($filesOnDisk[$i]['lengthOfBlock'] <= $filesOnDisk[$j]['followingFreeSpace']) { // found a space to move the file into
            // adjust free-space values before/after affected files
            $filesOnDisk[$i - 1]['followingFreeSpace'] += ($filesOnDisk[$i]['lengthOfBlock'] + $filesOnDisk[$i]['followingFreeSpace']); // adjust length of free space on file before the now removed file
            $filesOnDisk[$i]['followingFreeSpace'] = $filesOnDisk[$j]['followingFreeSpace'] - $filesOnDisk[$i]['lengthOfBlock']; // the free space on the moved file is the free space of the new file before it minus this files length
            $filesOnDisk[$j]['followingFreeSpace'] = 0; // since we insert the file right after the other one, that file won't have any free space anymore

            $filesOnDisk = array_merge(
                array_slice($filesOnDisk, 0, $j + 1),
                array(array(
                    'fileIdentifier' => $filesOnDisk[$i]['fileIdentifier'],
                    'lengthOfBlock' => $filesOnDisk[$i]['lengthOfBlock'],
                    'followingFreeSpace' => $filesOnDisk[$i]['followingFreeSpace'],
                    'hasBeenMoved' => true,
                )),
                array_slice($filesOnDisk, $j + 1)
            );

            unset($filesOnDisk[$i + 1]); // remove the file from the end of the disk ($i + 1 because a new element was just added)
            $i++; // adjust the index to account for the new element (the now last element hasn't been checked yet)
            break; // don't need to look further once the file has been moved
        }
    }
}

// echo '<pre>';
// print_r($filesOnDisk);
// die;

// calculating the final checksum of the file system
$checksum = 0;
$passedBlockLength = 0;

foreach($filesOnDisk as $fileInfo) {
    for($i = 0; $i < $fileInfo['lengthOfBlock']; $i++) {
        $checksum += ($passedBlockLength * $fileInfo['fileIdentifier']);
        $passedBlockLength++;
    }

    $passedBlockLength += $fileInfo['followingFreeSpace'];
}

echo "<br />Checksum: $checksum<br />";
?>