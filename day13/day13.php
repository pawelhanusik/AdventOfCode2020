<?php

function gcd($a, $b){
    if($b == 0)
        return $a;
    return gcd($b, $a % $b);
}
function areCoprimeIntegers($a, $b){
    return gcd($a, $b) == 1;
}
function isPossible($data){
    for($i = 0; $i < count($data); ++$i){
        for($j = 0; $j < count($data); ++$j){
            if($i == $j){
                continue;
            }

            if( !areCoprimeIntegers($data[$i][1], $data[$j][1]) ){
                return false;
            }
        }
    }

    return true;
}
function chineseRemainderTheorem($data){
    /*
    DATA
    [a, b] => x = a mod b
    [c, d] => x = c mod d
    */
    
    $a = $data[0][0];
    $m = $data[0][1];
    for($line = 1; $line < count($data); ++$line){
        $a2 = $data[$line][0];
        $m2 = $data[$line][1];

        $i = 0;
        while( ($a + $m * $i) % $m2 != $a2 ) ++$i;

        $a = ($a + $m * $i);
        $m = $m * $m2;
    }

    return $a;
}

$input = file_get_contents("input.txt");
$input = explode("\n", $input);

$earliestTimestamp = $input[0];
$buses = [];
$posInQueue = 0;
foreach( explode(",", $input[1]) as $ID){
    if($ID != 'x'){
        $buses[$posInQueue] = $ID;
    }
    ++$posInQueue;
}

//PART ONE
$waitTime = [];
foreach($buses as $bus){
    $waitTime[$bus] = ($bus - ($earliestTimestamp % $bus)) % $bus;
}

$minBus = array_key_first($waitTime);
foreach($waitTime as $bus => $wt){
    if($wt < $waitTime[$minBus]){
        $minBus = $bus;
    }
}
echo "Result: " . ($minBus * $waitTime[$minBus]) . "\n";

//PART TWO
$data = [];
foreach($buses as $posInQueue => $bus){
    $a = ($bus - $posInQueue) % $bus;
    while($a < 0) $a += $bus;
    $data[] = [
        $a,
        $bus
    ];
}


echo "Earliest timestamp: ";
if(isPossible($data)){
    echo chineseRemainderTheorem($data);
}else{
    echo "finding  it is not possible ;( - x doesn't cut out numbers which aren't coprime integers with eath others!";
}
echo "\n";

?>