<?php

$acc = 0;

$input = file_get_contents('input.txt');

$input = explode("\n", $input);
$wasExecuted = [];
for($ip = 0; $ip < count($input); ++$ip){
    $wasExecuted[$ip] = false;
}

$ip = 0;
while(true){
    if($wasExecuted[$ip]){
        break;
    }
    $line = $input[$ip];
    $ins = explode(" ", $line)[0];
    $arg = intval( explode(" ", $line)[1] );

    if($ins == "acc"){
        $acc += $arg;
    }else if($ins == "jmp"){
        $ip += $arg;
        continue;
    }

    $wasExecuted[$ip] = true;
    ++$ip;
}

echo "acc: " . $acc . "\n";

//PART TWO
function isCorrect(&$input){
    $wasExecuted = [];
    for($ip = 0; $ip < count($input); ++$ip){
        $wasExecuted[$ip] = false;
    }
    
    $acc = 0;
    $ip = 0;
    while(true){
        if($ip >= count($input)){
            return $acc;
        }
        if($wasExecuted[$ip]){
            return false;
        }
        $line = $input[$ip];
        $ins = explode(" ", $line)[0];
        $arg = intval( explode(" ", $line)[1] );
    
        if($ins == "acc"){
            $acc += $arg;
        }else if($ins == "jmp"){
            $ip += $arg;
            continue;
        }
    
        $wasExecuted[$ip] = true;
        ++$ip;
    }
    
}

//try changeing something
function change(&$input, $insNo){
    $line = $input[$insNo];
    $ins = explode(" ", $line)[0];
    $arg = explode(" ", $line)[1];
    if($ins == "jmp"){
        $input[$insNo] = "nop" . " " . $arg;
    }else if($ins == "nop"){
        $input[$insNo] = "jmp" . " " . $arg;
    }
}
for($i = 0; $i < count($input); ++$i){
    change($input, $i);
    if(isCorrect($input) != false){
        echo "Change: " . $i . "\t" . $input[$i] . "\n";
        echo "Acc: " . isCorrect($input) . "\n";
        break;
    }
    change($input, $i);
}

?>