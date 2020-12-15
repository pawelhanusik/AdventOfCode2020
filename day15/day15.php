<?php
ini_set('memory_limit', '-1');

$cache = [];
$lastNumber = -1;

$input = "11,0,1,10,5,19";
$numbers = explode(",", $input);
for($i = 0; $i < count($numbers); ++$i){
    $lastNumber = intval($numbers[$i]);
    $cache[$lastNumber] = $i+1;
}

for($i = count($numbers); $i < 2020; ++$i){
    $tmp = 0;
    if(array_key_exists($lastNumber, $cache)){
        $tmp = $i - $cache[$lastNumber];
    }
    $cache[$lastNumber] = $i;
    
    $lastNumber = $tmp;
}
echo "2020th\t" . $lastNumber . "\n";

for(; $i < 30000000; ++$i){
    $tmp = 0;
    if(array_key_exists($lastNumber, $cache)){
        $tmp = $i - $cache[$lastNumber];
    }
    $cache[$lastNumber] = $i;
    
    $lastNumber = $tmp;
}
echo "30000000th\t" . $lastNumber . "\n";

?>