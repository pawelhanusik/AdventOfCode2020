<?php

function doesBagContains(&$bags, $bag, $color="shiny gold"){
    //var_dump($bag);
    //var_dump($bags[$bag]);
    if(in_array($color, $bags[$bag]['colors'])){
        return true;
    }else{
        foreach($bags[$bag]['colors'] as $bagInside){
            if(doesBagContains($bags, $bagInside, $color)){
                return true;
            }
        }
        return false;
    }
}

$input = file_get_contents('input.txt');

$input = explode("\n", $input);

$bags = [];
foreach($input as $bag){

    $color = explode(" bags", $bag)[0];
    $contains = [];
    $containsCounts = [];

    $tmp = explode("contain ", $bag)[1];
    foreach(explode(", ", $tmp) as $bagInside){
        $bagInside = explode(" bag", $bagInside)[0];
        $bagInsideCount = intval( substr( $bagInside, 0, strpos($bagInside, " ") ) );
        $bagInside = substr( $bagInside, strpos($bagInside, " ")+1 ); 
        
        if(strpos($bagInside, "other") === false){
            $contains[] = $bagInside;
            $containsCounts[] = $bagInsideCount;
        }
    }

    $bags[$color]['colors'] = $contains;
    $bags[$color]['counts'] = $containsCounts;
}

$counter = 0;
foreach($bags as $color => $contains){
    if(doesBagContains($bags, $color)){
        ++$counter;
    }
}
echo $counter . "\n";

//PART TWO
function howManyBagsInside(&$bags, $bag="shiny gold"){
    $ret = 0;

    foreach($bags[$bag]['colors'] as $i => $bagInside){
        $count = $bags[$bag]['counts'][$i];
        $ret += $count + howManyBagsInside($bags, $bagInside) * $count;
    }

    return $ret;
}
echo howManyBagsInside($bags) . "\n";
?>