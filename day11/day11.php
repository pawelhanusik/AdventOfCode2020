<?php

/*for($r = -1, $c = +1; $c < 10; --$r, ++$c) echo "$r $c \n";
++$r; --$c;
echo "XXXX $r $c \n";
die();*/
/*phpinfo();
echo "AAA";
die();*/

function nrOfOccupiedSeatsAdjacentTo(&$seats, $row, $col){
    $ret = 0;

    if($seats[$row-1][$col-1] == '#')
        ++$ret;
    if($seats[$row-1][$col] == '#')
        ++$ret;
    if($seats[$row-1][$col+1] == '#')
        ++$ret;
    if($seats[$row][$col-1] == '#')
        ++$ret;
    if($seats[$row][$col+1] == '#')
        ++$ret;
    if($seats[$row+1][$col-1] == '#')
        ++$ret;
    if($seats[$row+1][$col] == '#')
        ++$ret;
    if($seats[$row+1][$col+1] == '#')
        ++$ret;

    return $ret;
}
function step(&$seats){
    $ret = $seats;

    for($row = 1; $row < count($seats) - 1; ++$row){
        for($col = 1; $col < count($seats[$row]) - 1; ++$col){
            $occupiedSeats = nrOfOccupiedSeatsAdjacentTo($seats, $row, $col);
            
            if($seats[$row][$col] == 'L' && $occupiedSeats == 0){
                $ret[$row][$col] = '#';
            }else if($seats[$row][$col] == '#' && $occupiedSeats >= 4){
                $ret[$row][$col] = 'L';
            }
        }
    }

    return $ret;
}
function nrOfOccupiedSeatsAdjacentTo2(&$seats, $row, $col){
    $ret = 0;

    for($r = -1, $c = -1; $seats[$row+$r][$col+$c] == '.'; --$r, --$c);// ++$r; ++$c;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;
    for($r = -1, $c = 0; $seats[$row+$r][$col+$c] == '.'; --$r); //++$r;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;
    for($r = -1, $c = +1; $seats[$row+$r][$col+$c] == '.'; --$r, ++$c); //++$r; --$c;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;
    
    for($r = 0, $c = -1; $seats[$row+$r][$col+$c] == '.'; --$c); //++$c;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;
    for($r = 0, $c = +1; $seats[$row+$r][$col+$c] == '.'; ++$c);// --$c;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;

    for($r = +1, $c = -1; $seats[$row+$r][$col+$c] == '.'; ++$r, --$c); //--$r; ++$c;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;
    for($r = +1, $c = 0; $seats[$row+$r][$col+$c] == '.'; ++$r); //--$r;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;
    for($r = +1, $c = +1; $seats[$row+$r][$col+$c] == '.'; ++$r, ++$c);// --$r; --$c;
    if($seats[$row+$r][$col+$c] == '#')
        ++$ret;
    
    return $ret;
}
function step2(&$seats){
    $ret = $seats;

    for($row = 1; $row < count($seats) - 1; ++$row){
        for($col = 1; $col < count($seats[$row]) - 1; ++$col){
            $occupiedSeats = nrOfOccupiedSeatsAdjacentTo2($seats, $row, $col);
            
            if($seats[$row][$col] == 'L' && $occupiedSeats == 0){
                $ret[$row][$col] = '#';
            }else if($seats[$row][$col] == '#' && $occupiedSeats >= 5){
                $ret[$row][$col] = 'L';
            }
        }
    }

    return $ret;
}
function seats2string(&$seats){
    $str = '';
    $str .= "==========\n";
    for($row = 1; $row < count($seats) - 1; ++$row){
        for($col = 1; $col < count($seats[$row]) - 1; ++$col){
            $str .= $seats[$row][$col];
        }
        $str .= "\n";
    }
    $str .= "==========\n";
    echo $str;
}
function stats(&$seats){
    $ret = array(
        '.' => 0,
        'L' => 0,
        '#' => 0
    );

    for($row = 1; $row < count($seats) - 1; ++$row){
        for($col = 1; $col < count($seats[$row]) - 1; ++$col){
            ++$ret[$seats[$row][$col]];
        }
    }

    return $ret;
}
$input = file_get_contents('input.txt');

$input = explode("\n", $input);

$seats = [];
$emptyRow = [];
for($i = 0; $i < strlen($input[0]) + 2; ++$i){
    $emptyRow[] = ' ';
}
$seats[] = $emptyRow;
foreach($input as $strRow){
    $row = [];
    $row[] = ' ';
    for($i = 0; $i < strlen($strRow); ++$i){
        $row[] = $strRow[$i];
    }
    $row[] = ' ';
    $seats[] = $row;
}
$seats[] = $emptyRow;

for($stepID = 0; ; ++$stepID){
    $tmp = step($seats);
    if($tmp == $seats){
        break;
    }else{
        $seats = $tmp;
    }
}

echo "OccupiedSeats: " . stats($seats)['#'] . "\n";

//PART TWO
$seats = [];
$emptyRow = [];
for($i = 0; $i < strlen($input[0]) + 2; ++$i){
    $emptyRow[] = ' ';
}
$seats[] = $emptyRow;
foreach($input as $strRow){
    $row = [];
    $row[] = ' ';
    for($i = 0; $i < strlen($strRow); ++$i){
        $row[] = $strRow[$i];
    }
    $row[] = ' ';
    $seats[] = $row;
}
$seats[] = $emptyRow;
/*echo "No=" . nrOfOccupiedSeatsAdjacentTo2($seats, 2, 2) . "\n";
die();*/

//==============================================================


//echo seats2string($seats);
for($stepID = 0; ; ++$stepID){
    $tmp = step2($seats);
    if($tmp == $seats){
        break;
    }else{
        $seats = $tmp;
    }
    //echo seats2string($seats);
}

echo "OccupiedSeats: " . stats($seats)['#'] . "\n";

?>