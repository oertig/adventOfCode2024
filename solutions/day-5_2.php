<?php
$input = file_get_contents('../inputs/day-5.txt');
$lines = explode("\n", $input);

$pageOrders = [];
$newlySortedPageOrders = [];

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

function isSortedPageOrder(&$pageOrder) {
    global $precedenceConstraints, $trailingConstraints;
    $isValidOrder = true;

    foreach($pageOrder as $currentPageKey => $currentPageNr) {
        if(array_key_exists($currentPageNr, $precedenceConstraints)) {
            foreach($precedenceConstraints[$currentPageNr] as $disallowedBeforePageNr) {
                $firstOccuranceOfDisallowed = array_search($disallowedBeforePageNr, $pageOrder);

                if($firstOccuranceOfDisallowed !== false) {
                    if($firstOccuranceOfDisallowed < $currentPageKey) {
                        // echo 'precedence-constraint violation found: page ' . $disallowedBeforePageNr . ' is not allowed to appear before page ' . $currentPageNr . " [" . implode(',', $pageOrder) . "]" . "<br />";
                        $isValidOrder = false;
                        $pageOrder[$currentPageKey] = $disallowedBeforePageNr;
                        $pageOrder[$firstOccuranceOfDisallowed] = $currentPageNr;
                        // echo 'new precedence order: ' . implode(',', $pageOrder) . "<br />";
                        continue 2;
                    }
                }
            }
        }

        if(!$isValidOrder) {
            // elements get overwritten if both precedence and trailing constraints are violated
            // therefore we just return whenever one of them is violated
            return $isValidOrder;
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
                        $pageOrder[$currentPageKey] = $disallowedAfterPageNr;
                        $pageOrder[$lastOccuranceOfDisallowed] = $currentPageNr;
                        // echo 'new trailing order: ' . implode(',', $pageOrder) . "<br />";
                        continue 2;
                    }
                }
            }
        }
    }

    return $isValidOrder;
}

foreach($pageOrders as $pageOrder) {
    $isSorteedAlready = false;
    $loopCount = 0;

    while(!$isSorteedAlready) {
        $loopCount++;
        $isSorteedAlready = isSortedPageOrder($pageOrder);
    }

    if($loopCount > 1) {
        $newlySortedPageOrders[] = $pageOrder;
    }
}

$totalAmountMiddleValues = 0;

foreach($newlySortedPageOrders as $correctlyOrderedUpdate) {
    $middleElementIndex = floor(count($correctlyOrderedUpdate) / 2);
    $totalAmountMiddleValues += $correctlyOrderedUpdate[$middleElementIndex];
}

echo 'total amount of middle values: ' . $totalAmountMiddleValues . "<br />";
?>