<?php

class Masker{
    public static function mask($value, $mask){
        $ret = "";
    
        $lenDiff = strlen($mask) - strlen($value);
        for($i = 0; $i < strlen($mask); ++$i){
            if($mask[$i] == 'X'){
                if($i < $lenDiff){
                    $ret .= '0';
                }else{
                    $ret .= $value[$i - $lenDiff];
                }
            }else{
                $ret .= $mask[$i];
            }
        }
    
        return $ret;
    }
    //=========================================
    private static function add($c, &$ret){
        for($i = 0; $i < count($ret); ++$i){
            $ret[$i] .= $c;
        }
    }
    private static function addAny(&$ret){
        $count = count($ret);
        for($i = 0; $i < $count; ++$i){
            $ret[] = $ret[$i];
        }
        for($i = 0; $i < $count; ++$i){
            $ret[$i] .= '0';
        }
        for($i = $count; $i < count($ret); ++$i){
            $ret[$i] .= '1';
        }
    }
    public static function mask_addr($value, $mask){
        $ret = [];
        $ret[] = "";
    
        $lenDiff = strlen($mask) - strlen($value);
        for($i = 0; $i < strlen($mask); ++$i){
            if($mask[$i] == '0'){
                if($i < $lenDiff){
                    Masker::add('0', $ret);
                }else{
                    Masker::add($value[$i - $lenDiff], $ret);
                }
            }else if($mask[$i] == '1'){
                Masker::add('1', $ret);
            }else{
                Masker::addAny($ret);
            }
        }
    
        return $ret;
    }    
}

$input = file_get_contents('input.txt');
$input = explode("\n", $input);

$mem = [];
$mask = "";

foreach($input as $line){
    $instruction = explode(" = ", $line)[0];
    $param = explode(" = ", $line)[1];
    if($instruction == "mask"){
        $mask = $param;
    }else{
        $offset = explode("[", $instruction, 2)[1];
        $offset = explode("]", $offset, 2)[0];

        $toWrite = decbin($param);
        $toWrite = Masker::mask($toWrite, $mask);
        $toWrite = bindec($toWrite);
        $mem[$offset] = $toWrite;
    }
}

$sum = 0;
foreach($mem as $m){
    $sum += $m;
}
echo "Sum: $sum\n";

//PART TWO
$mem = [];
$mask = "";

foreach($input as $line){
    $instruction = explode(" = ", $line)[0];
    $param = explode(" = ", $line)[1];
    if($instruction == "mask"){
        $mask = $param;
    }else{
        $offset = explode("[", $instruction, 2)[1];
        $offset = explode("]", $offset, 2)[0];

        $offset = decbin($offset);
        $offsets = Masker::mask_addr($offset, $mask);
        foreach($offsets as $off){
            $off = bindec($off);
            $mem[$off] = $param;
        }
    }
}

$sum = 0;
foreach($mem as $m){
    $sum += $m;
}
echo "Sum: $sum\n";

?>