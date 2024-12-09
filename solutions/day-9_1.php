<?php
$input = trim(file_get_contents('../inputs/day-9.txt'));
$inputs = str_split($input);

$filesOnDisk = [];
$formattedFileSystemDisk = [];

for ($i = 0; $i < count($inputs); $i += 2) {
    $filesOnDisk[] = [
        'fileIdentifier' => $i / 2,
        'lengthOfBlock' => intval($inputs[$i]),
        'followingFreeSpace' => intval($inputs[$i + 1] ?? 0),
    ];
}

/*
loop over each file in the queue
create temporary queue for files from the end that can be added into the empty space
fill temporary queue with as many files and fragments as possible
    - remove fully processed files from the main queue
    - update the length of any fragmented files (should be either 0 or 1)
update free space of current element (should mainly be 0) // only calcualate later n if it is needed
add current element and temporary queue to the formatted file system disk
*/

while(!empty($filesOnDisk)) {
    $currentFile = array_shift($filesOnDisk);
    $tempQueue = [];

    if(empty($filesOnDisk)) {
        // we are processing the last element
        $formattedFileSystemDisk[] = $currentFile;
    } else {
        // there are still more elements after the current element
        while($currentFile['followingFreeSpace'] > 0) { // as long as there is still free space after the current element
            $lastFile = array_pop($filesOnDisk);
    
            if($lastFile['lengthOfBlock'] <= $currentFile['followingFreeSpace']) { // if the last file fit in completely
                $tempQueue[] = [
                    'fileIdentifier' => $lastFile['fileIdentifier'],
                    'lengthOfBlock' => $lastFile['lengthOfBlock'],
                ];
                $currentFile['followingFreeSpace'] -= $lastFile['lengthOfBlock'];
            } else { // if the last file doesn't fit in completely
                $tempQueue[] = [
                    'fileIdentifier' => $lastFile['fileIdentifier'],
                    'lengthOfBlock' => $currentFile['followingFreeSpace'],
                ];
                $lastFile['lengthOfBlock'] = $lastFile['lengthOfBlock'] - $currentFile['followingFreeSpace']; // reduce the length of the last file by what could be put into the free space
                $currentFile['followingFreeSpace'] = 0; // no more free space at current element
                $filesOnDisk[] = $lastFile; // need to readd the last file to the end of the queue (after removing the free space)
            }
        }
    
        $formattedFileSystemDisk[] = $currentFile;
    
        foreach($tempQueue as $tempFile) {
            $formattedFileSystemDisk[] = $tempFile;
        }
    }
}

// calculating the final checksum of the file system
$checksum = 0;
$passedBlockLength = 0;

foreach($formattedFileSystemDisk as $file) {
    for($i = 0; $i < $file['lengthOfBlock']; $i++) {
        $checksum += (
            ( $passedBlockLength + $i ) * $file['fileIdentifier']
        );
    }

    $passedBlockLength += $file['lengthOfBlock'];
}

echo "<br />Checksum: $checksum<br />";
?>