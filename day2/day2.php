<?php

function isCorrect($countFrom, $countTo, $char, $passwd){
    $realCount = 0;
    for($i = 0; $i < strlen($passwd); ++$i){
        if($passwd[$i] == $char){
            ++$realCount;
        }
    }

    return(
        $realCount >= $countFrom
        && $realCount <= $countTo
    );
}
function isCorrect2($posA, $posB, $char, $passwd){
    $realCount = 0;

    $posA -= 1;
    $posB -= 1;

    if($posA < strlen($passwd) && $passwd[$posA] == $char){
        ++$realCount;
    }
    if($posB < strlen($passwd)&& $passwd[$posB] == $char){
        ++$realCount;
    }

    return ($realCount == 1);
}

$input = file_get_contents('input.txt');
$input = explode("\n", $input);

$correct = 0;
$correct2 = 0;

foreach($input as $line){
    if(strlen($line) <= 0){
        continue;
    }
    $tmp = explode(" ", $line);
    $nrs = explode("-", $tmp[0]);
    $char = $tmp[1];
    $passwd = $tmp[2];

    $countFrom = intval($nrs[0]);
    $countTo = intval($nrs[1]);
    $char = $char[0];

    /*echo "a=$countFrom\n";
    echo "b=$countTo\n";
    echo "c=$char\n";
    echo "P=$passwd\n";*/

    if(isCorrect($countFrom, $countTo, $char, $passwd)){
        ++$correct;
    }
    if(isCorrect2($countFrom, $countTo, $char, $passwd)){
        ++$correct2;
    }
}

echo "Correct passwds1: " . $correct . "\n";
echo "Correct passwds2: " . $correct2 . "\n";

?>