<?php

$input = file_get_contents('input.txt');

$input = explode("\n\n", $input);
$totalCount = 0;
foreach($input as $group){
    $group = explode("\n", $group);

    $answers = [];
    for($c = 'a'; $c <= 'z'; ++$c){
        $answers[$c] = false;
    }

    foreach($group as $person){
        for($i = 0; $i < strlen($person); ++$i){
            $question = $person[$i];
            $answers[$question] = true;
        }
    }

    $counter = 0;
    for($c = 'a'; $c <= 'z'; ++$c){
        if($answers[$c]){
            ++$counter;
        }
    }

    $totalCount += $counter;
}

echo $totalCount . "\n";

//PART TWO

$totalCount2 = 0;
foreach($input as $group){
    $group = explode("\n", $group);

    $answers = [];
    for($c = 'a'; $c <= 'z'; ++$c){
        $answers[$c] = 0;
    }

    foreach($group as $person){
        for($i = 0; $i < strlen($person); ++$i){
            $question = $person[$i];
            $answers[$question] += 1;
        }
    }

    $counter = 0;
    for($c = 'a'; $c <= 'z'; ++$c){
        if($answers[$c] == count($group)){
            ++$counter;
        }
    }

    $totalCount2 += $counter;
}

echo $totalCount2 . "\n";


?>