<?php

/**
 * "Funny" solution to both part one and part two.
 */

function string2stack($line){
    $stack = [];
    $numberStr = '';
    for($i = 0; $i < strlen($line); ++$i){
        $c = $line[$i];
        
        if($c == ' '){

        }else if(
            $c == '+'
            || $c == '*'
            || $c == '('
            || $c == ')'
        ){
            $stack[] = $c;
        }else{
            $numberStr .= $c;
            if(
                $i + 1 >= strlen($line)
                || $line[$i + 1] < '0'
                || $line[$i + 1] > '9'
            ){
                $stack[] = intval($numberStr);
                $numberStr = '';
            }
        }
    }
    return $stack;
}

function modify($stack, $primaryOperators){
    for($i = 0; $i < count($stack); ++$i){

        if( in_array($stack[$i], $primaryOperators) ){
            $bracketsToEscape = 0;
            $addedBracket = false;
            for($j = $i - 1; $j >= 0; --$j){
                if(
                    is_int($stack[$j])
                    || $stack[$j] == '('
                ){
                    if( $stack[$j] == '(' ){
                        --$bracketsToEscape;
                    }
                    if( $bracketsToEscape <= 0 ){
                        array_splice($stack, $j, 0, ['(']);
                        $addedBracket = true;
                        break;
                    }
                }else if($stack[$j] == ')'){
                    ++$bracketsToEscape;
                }
            }
            if(!$addedBracket){
                $stack = array_merge(['('], $stack);
            }
            
            
            ++$i;


            $bracketsToEscape = 0;
            $addedFinalBracket = false;
            for($j = $i + 1; $j < count($stack); ++$j){
                if(
                    is_int($stack[$j])
                    || $stack[$j] == ')'
                ){
                    if($stack[$j] == ')'){
                        --$bracketsToEscape;
                    }
                    if( $bracketsToEscape <= 0 ){
                        array_splice($stack, $j + 1, 0, [')']);
                        $addedFinalBracket = true;
                        break;
                    }
                }else if($stack[$j] == '('){
                    ++$bracketsToEscape;
                }
            }
            if(!$addedFinalBracket){
                $stack[] = ')';
            }
        }

    }
    
    return $stack;
}
function stack2string($stack){
    $ret = '';
    foreach($stack as $x){
        $ret .= $x . ' ';
    }
    return $ret;
}
function funSolve($line, $primaryOperators){
    $stack = string2stack($line);
    $stack = modify($stack, $primaryOperators);
    $str = stack2string($stack);
    
    eval('$ret = ' . $str . ';');
    return $ret;
}


$input = file_get_contents('input.txt');
$input = explode("\n", $input);

$sum = 0;
foreach($input as $line){
    $sum += funSolve($line, ['+', '*']);
}
echo "Sum for part one: $sum\n";

$sum = 0;
foreach($input as $line){
    $sum += funSolve($line, ['+']);
}
echo "Sum for part two: $sum\n";

?>