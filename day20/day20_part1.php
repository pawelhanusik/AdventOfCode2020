<?php

class Tile {
    public $id;
    public $borders = ['', '', '', '', '', '', '', '']; //order: TOP, RIGHT, BOTTOM, LEFT, INV_TOP, INV_RIGHT, INV_BOTTOM, INV_LEFT
    public $outerBorders = 0;

    public function __construct($tileStr){
        $tileStr = explode("\n", $tileStr, 2);
        $this->id = intval( explode(" ", explode(":", $tileStr[0])[0])[1] );

        $lines = explode("\n", $tileStr[1]);
        for($y = 0; $y < count($lines); ++$y){
            for($x = 0; $x < strlen($lines[$y]); ++$x){
                
                if( $y == 0 ){
                    //top border
                    $this->borders[0] .= $lines[$y][$x];
                }else if( $y == count($lines) - 1 ){
                    //bottom border
                    $this->borders[2] .= $lines[$y][$x];
                }

                if( $x == 0 ){
                    //left border
                    $this->borders[3] .= $lines[$y][$x];
                }else if( $x == strlen($lines[$y]) - 1 ){
                    //right border
                    $this->borders[1] .= $lines[$y][$x];
                }

            }
        }

        for($i = 0; $i < 4; ++$i){
            $b = '';
            for($j = strlen($this->borders[$i]) - 1; $j >= 0; --$j){
                $b .= $this->borders[$i][$j];
            }
            $this->borders[$i+4] = $b;
        }
    }
}

$input = file_get_contents('input.txt');
$input = explode("\n\n", $input);

$tiles = [];
foreach($input as $t){
    $tiles[] = new Tile($t);
}

$countOfSimilarBorders = [];
foreach($tiles as $tile){
    foreach($tile->borders as $b){
        if(!key_exists($b, $countOfSimilarBorders)){
            $countOfSimilarBorders[$b] = 0;
        }
        ++$countOfSimilarBorders[$b];
    }
}

foreach($tiles as $tile){
    foreach($tile->borders as $b){
        if($countOfSimilarBorders[$b] === 1){
            ++$tile->outerBorders;
        }
    }
}

$mul = 1;
foreach($tiles as $tile){
    if($tile->outerBorders >= 4){
        echo $tile->id . "\n";
        $mul *= $tile->id;
    }
}
echo "----------\n";
echo "Corner IDs multiplied: $mul\n";

?>