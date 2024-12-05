<?php
$input = file_get_contents('../inputs/day-5.txt');
$lines = explode("\n", $input);

$pageOrders = [];
$correctlyOrderedUpdates = [];

/**
 * Each value represents an array of page numbers that are not allowed to appear before the page number of the key.
 * 
 * @var array<array<int>>
 */
$precedenceConstraints = [];

/**
 * Each value represents an array of page numbers that are not allowed to appear after the page number of the key.
 * 
 * @var array<array<int>>
 */
$trailingConstraints = [];

foreach($lines as $line) {
    switch (true) {
        case (strpos($line, '|') !== false) :
            list($leadingPage, $trailingPage) = array_map('intval', explode('|', $line));

            $precedenceConstraints[$leadingPage] ?? null;
            $precedenceConstraints[$leadingPage][] = $trailingPage;

            $trailingConstraints[$trailingPage] ?? null;
            $trailingConstraints[$trailingPage][] = $leadingPage;

            break;

        case (strpos($line, ',') !== false) :
            $pageOrders[] = array_map('intval', explode(',', $line));
            break;

        default :
            break;
    }
}

foreach($pageOrders as $pageOrder) {
    $isValidOrder = true;

    foreach($pageOrder as $currentPageKey => $currentPageNr) {
        if(array_key_exists($currentPageNr, $precedenceConstraints)) {
            foreach($precedenceConstraints[$currentPageNr] as $disallowedBeforePageNr) {
                $firstOccuranceOfDisallowed = array_search($disallowedBeforePageNr, $pageOrder);

                if($firstOccuranceOfDisallowed !== false) {
                    if($firstOccuranceOfDisallowed < $currentPageKey) {
                        // echo 'precedence-constraint violation found: page ' . $disallowedBeforePageNr . ' is not allowed to appear before page ' . $currentPageNr . " [" . implode(',', $pageOrder) . "]" . "<br />";
                        $isValidOrder = false;
                        continue 2;
                    }
                }
            }
        }

        if(array_key_exists($currentPageNr, $trailingConstraints)) {
            $pageOrderLength = count($pageOrder);
            $reversePageOrder = array_reverse($pageOrder);

            foreach($trailingConstraints[$currentPageNr] as $disallowedAfterPageNr) {
                $lastOccuranceOfDisallowedReversed = array_search($disallowedAfterPageNr, $reversePageOrder);

                if($lastOccuranceOfDisallowedReversed !== false) { // the potentially violation page number exists within the order
                    $lastOccuranceOfDisallowed = $pageOrderLength - 1 - $lastOccuranceOfDisallowedReversed;

                    if( $lastOccuranceOfDisallowed > $currentPageKey ) { // the potentially violation page is after the current page (actually violating the rules)
                        // echo 'trailing-constraint violation found: page ' . $disallowedAfterPageNr . ' is not allowed to appear after page ' . $currentPageNr . " [" . implode(',', $pageOrder) . "]" . "<br />";
                        $isValidOrder = false;
                        continue 2;
                    }
                }
            }
        }
    }

    if($isValidOrder) {
        // echo 'valid order found: ' . implode(',', $pageOrder) . "<br />";
        $correctlyOrderedUpdates[] = $pageOrder;
    } else {
        // echo 'invalid order found: ' . implode(',', $pageOrder) . "<br />";
    }
}

$totalAmountMiddleValues = 0;

foreach($correctlyOrderedUpdates as $correctlyOrderedUpdate) {
    $middleElementIndex = floor(count($correctlyOrderedUpdate) / 2);
    $totalAmountMiddleValues += $correctlyOrderedUpdate[$middleElementIndex];
}

echo 'total amount of middle values: ' . $totalAmountMiddleValues . "<br />";
?>