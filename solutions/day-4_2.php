<?php
class XmasChecker {
    private $xmas = [];

    public function __construct($xmas) {
        $this->xmas = $xmas;
    }

    public function isCrossMas($x, $y) {
        if ($this->xmas[$x][$y] === 'A') {
            if (
                array_key_exists($x + 1, $this->xmas) &&
                array_key_exists($x - 1, $this->xmas) &&
                array_key_exists($y + 1, $this->xmas[$x]) &&
                array_key_exists($y - 1, $this->xmas[$x])
            ) {

                if(
                    // in order: top left, top right, bottom left, bottom right
                    (
                        $this->xmas[$x-1][$y-1] === 'M' &&
                        $this->xmas[$x+1][$y-1] === 'M' &&
                        $this->xmas[$x-1][$y+1] === 'S' &&
                        $this->xmas[$x+1][$y+1] === 'S'
                    ) ||
                    (
                        $this->xmas[$x-1][$y-1] === 'M' &&
                        $this->xmas[$x+1][$y-1] === 'S' &&
                        $this->xmas[$x-1][$y+1] === 'M' &&
                        $this->xmas[$x+1][$y+1] === 'S'
                    ) ||
                    (
                        $this->xmas[$x-1][$y-1] === 'S' &&
                        $this->xmas[$x+1][$y-1] === 'S' &&
                        $this->xmas[$x-1][$y+1] === 'M' &&
                        $this->xmas[$x+1][$y+1] === 'M'
                    ) ||
                    (
                        $this->xmas[$x-1][$y-1] === 'S' &&
                        $this->xmas[$x+1][$y-1] === 'M' &&
                        $this->xmas[$x-1][$y+1] === 'S' &&
                        $this->xmas[$x+1][$y+1] === 'M'
                    )
                ) {
                    return true;
                }
            }
        }
    
        return false;
    }
}

$input = file_get_contents('../inputs/day-4.txt');
$lines = explode("\n", $input);
$xmas = [];
$amountOccurances = 0;

for ($lineNr = 0; $lineNr < count($lines); $lineNr++) {
    if ($lines[$lineNr] == '') { continue; }
    $xmas[$lineNr] = str_split($lines[$lineNr]);
}

$xmasChecker = new XmasChecker($xmas);

foreach($xmas as $lineNr => $elementsInLine) {
    foreach($elementsInLine as $elementNr => $element) {
        $amountOccurances += (int) $xmasChecker->isCrossMas($lineNr, $elementNr);
    }
}

echo "\nAmount of occurances: $amountOccurances\n";
?>