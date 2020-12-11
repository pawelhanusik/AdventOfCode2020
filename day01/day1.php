<?php

$input = file_get_contents('input.txt');
$input = explode("\n", $input);


echo "a, b\n";
foreach($input as $a){
    foreach($input as $b){
        $a = intval($a);
        $b = intval($b);

        if($a + $b == 2020){
            echo "a=$a\nb=$b\n";
            echo "a * b = " . ($a * $b) . "\n";
        }
    }
}
echo "===============\n";
//===
echo "a, b, c\n";
foreach($input as $a){
    foreach($input as $b){
        foreach($input as $c){
            $a = intval($a);
            $b = intval($b);
            $c = intval($c);

            if(
                $a + $b + $c == 2020
                && $a != 0
                && $b != 0
                && $c != 0
            ){
                echo "a=$a\nb=$b\nc=$c\n";
                echo "a * b = " . ($a * $b * $c) . "\n";
            }
        }
    }
}
?>
