<?php
class XmasChecker {
    private $xmas = [];

    public function __construct($xmas) {
        $this->xmas = $xmas;
    }

    public function isForwards($x, $y) {
        if(array_key_exists($x + 3, $this->xmas)) {
            if(
                $this->xmas[$x+1][$y] === 'M' &&
                $this->xmas[$x+2][$y] === 'A' &&
                $this->xmas[$x+3][$y] === 'S'
                ) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isBackwards($x, $y) {
        if(array_key_exists($x - 3, $this->xmas)) {
            if(
                $this->xmas[$x-1][$y] === 'M' &&
                $this->xmas[$x-2][$y] === 'A' &&
                $this->xmas[$x-3][$y] === 'S'
                ) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isUpwards($x, $y) {
        if(array_key_exists($y - 3, $this->xmas[$x])) {
            if(
                $this->xmas[$x][$y-1] === 'M' &&
                $this->xmas[$x][$y-2] === 'A' &&
                $this->xmas[$x][$y-3] === 'S'
                ) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isDownwards($x, $y) {
        if(array_key_exists($y + 3, $this->xmas[$x])) {
            if(
                $this->xmas[$x][$y+1] === 'M' &&
                $this->xmas[$x][$y+2] === 'A' &&
                $this->xmas[$x][$y+3] === 'S'
                ) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isDiagonalForwardUp($x, $y) {
        if(array_key_exists($x + 3, $this->xmas) && array_key_exists($y - 3, $this->xmas[$x + 3])) {
            if(
                $this->xmas[$x+1][$y-1] === 'M' &&
                $this->xmas[$x+2][$y-2] === 'A' &&
                $this->xmas[$x+3][$y-3] === 'S'
                ) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isDiagonalForwardDown($x, $y) {
        if(array_key_exists($x + 3, $this->xmas) && array_key_exists($y + 3, $this->xmas[$x + 3])) {
            if(
                $this->xmas[$x+1][$y+1] === 'M' &&
                $this->xmas[$x+2][$y+2] === 'A' &&
                $this->xmas[$x+3][$y+3] === 'S'
                ) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isDiagonalBackwardsUp($x, $y) {
        if(array_key_exists($x - 3, $this->xmas) && array_key_exists($y - 3, $this->xmas[$x - 3])) {
            if(
                $this->xmas[$x-1][$y-1] === 'M' &&
                $this->xmas[$x-2][$y-2] === 'A' &&
                $this->xmas[$x-3][$y-3] === 'S'
                ) {
                return true;
            }
        }
    
        return false;
    }
    
    public function isDiagonalBackwardsDown($x, $y) {
        if(array_key_exists($x - 3, $this->xmas) && array_key_exists($y + 3, $this->xmas[$x - 3])) {
            if(
                $this->xmas[$x-1][$y+1] === 'M' &&
                $this->xmas[$x-2][$y+2] === 'A' &&
                $this->xmas[$x-3][$y+3] === 'S'
                ) {
                return true;
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
        if($element === 'X') {
            $amountOccurances += (int) $xmasChecker->isForwards($lineNr, $elementNr);
            $amountOccurances += (int) $xmasChecker->isBackwards($lineNr, $elementNr);
            $amountOccurances += (int) $xmasChecker->isUpwards($lineNr, $elementNr);
            $amountOccurances += (int) $xmasChecker->isDownwards($lineNr, $elementNr);
            $amountOccurances += (int) $xmasChecker->isDiagonalForwardUp($lineNr, $elementNr);
            $amountOccurances += (int) $xmasChecker->isDiagonalForwardDown($lineNr, $elementNr);
            $amountOccurances += (int) $xmasChecker->isDiagonalBackwardsUp($lineNr, $elementNr);
            $amountOccurances += (int) $xmasChecker->isDiagonalBackwardsDown($lineNr, $elementNr);
        }
    }
}

echo "\nAmount of occurances: $amountOccurances\n";
?>