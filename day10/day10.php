<?php

$input = file_get_contents('input.txt');

$input = explode("\n", $input);

$builtInJoltage = max($input) + 3;

$input[] = 0;
$input[] = $builtInJoltage;
sort($input);

$diffs = array(
    1 => 0,
    2 => 0,
    3 => 0
);
for($i = 1; $i < count($input); ++$i){
    $diff = $input[$i] - $input[$i-1];
    ++$diffs[$diff];
}

echo "Res=" . ($diffs[1] * $diffs[3]) . "\n";

//PART TWO
$cache = [];
function howManyPossibilities(&$input, $index = 0){
    global $cache;
    $possibilitiesSoFar = 0;

    if($index == count($input)-1){
        $possibilitiesSoFar = 1;
    }
    //$cur = $input[$index];
    //echo "A$index\n";
    for($ni = $index+1; $ni < count($input) && $input[$ni] - $input[$index] <= 3; ++$ni){
        if(in_array($ni, $cache)){
            $tmp = $cache[$ni];
            $possibilitiesSoFar += $tmp;
        }else{
            $tmp = howManyPossibilities($input, $ni);
            $possibilitiesSoFar += $tmp;
            $cache[$ni] = $possibilitiesSoFar;
        }
    }
    
    //echo "$ni\n";

    return $possibilitiesSoFar;
}

$possibilities = howManyPossibilities($input);
echo "Possibilities: " . $possibilities . "\n";

?>