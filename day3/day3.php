<?php

$terrain = file_get_contents('input.txt');
$terrain = explode("\n", $terrain);

$height = count($terrain);

function getXY($x, $y){

    global $terrain;

    $width = strlen($terrain[$y]);

    return $terrain[$y][$x % $width];
}

$slopes = [
    ['x' => 1, 'y' => 1],
    ['x' => 3, 'y' => 1],
    ['x' => 5, 'y' => 1],
    ['x' => 7, 'y' => 1],
    ['x' => 1, 'y' => 2]
];

$result = 1;
foreach($slopes as $slope){
    $x = 0;
    $y = 0;
    $trees = 0;
    while( $y < $height ){
        $res = getXY($x, $y);
        if($res == '#'){
            ++$trees;
        }

        $x += $slope['x'];
        $y += $slope['y'];
    }

    $result *= $trees;
    echo "Trees: " . $trees . "\n";
}
echo "Result: " . $result . "\n";

?>