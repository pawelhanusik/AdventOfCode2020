<?php

function getSeatID($seat){
    $row = 0;
    $col = 0;

    for($i = 0; $i < 7; ++$i){
        $row <<= 1;
        $row |= ($seat[$i]=='B') ? 1 : 0;
    }
    for($i = 7; $i < 10; ++$i){
        $col <<= 1;
        $col |= ($seat[$i]=='R') ? 1 : 0;
    }
    
    return $row * 8 + $col;
}

$input = file_get_contents('input.txt');

$input = explode("\n", $input);

$highestSeatID = 0;
$seatIDs = [];
foreach($input as $seat){
    $seatID = getSeatID($seat);
    $highestSeatID = max($highestSeatID, $seatID);

    $seatIDs[] = $seatID;
}

echo 'Highest seat ID: ' . $highestSeatID . "\n";

sort($seatIDs);
for($i = 1; $i < count($seatIDs); ++$i){
    if($seatIDs[$i] != $seatIDs[$i-1] + 1){
        for($j = $seatIDs[$i-1] + 1; $j < $seatIDs[$i]; ++$j){
            echo "seat " . $j . " is free\n";
        }
    }
}





?>