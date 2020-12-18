<?php

/**
 * Solution only to part one.
 * Tried to do it 'normal' way.
 * In part two i decided to have some 'fun' and did it with eval() in day18.php
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
function solve($stack){
    $a = null;
    $b = null;
    $op = null;
    
    $openingBracketI = -1;
    $skipBrackets = 0;
    for($i = 0; $i < count($stack); ++$i){
        
        if($openingBracketI >= 0){
            //opening bracket found - look for closing one
            if($stack[$i] == '('){
                ++$skipBrackets;
            }else if($stack[$i] == ')'){
                if($skipBrackets-- <= 0){
                    $closingBracketI = $i;

                    $bracketValue = solve( array_slice($stack, $openingBracketI + 1, $closingBracketI - $openingBracketI - 1) );
                    $stack = array_merge(
                        array_slice($stack, 0, $openingBracketI),
                        [$bracketValue],
                        array_slice($stack, $closingBracketI + 1)
                    );

                    $i = $openingBracketI - 1;
                    $openingBracketI = -1;
                    $skipBrackets = 0;
                }
            }
        }else{
            //opening bracket not yet found - search for it & do math
            if($stack[$i] == '('){
                $openingBracketI = $i;
            }else if($stack[$i] == ')'){
                
            }else if( is_int($stack[$i]) ){
                if($a === null){
                    $a = $stack[$i];
                }else if($b === null){
                    $b = $stack[$i];
                }
            }else if(
                $stack[$i] == '+'
                || $stack[$i] == '*'
            ){
                if($op === null){
                    $op = $stack[$i];
                }
            }else{
                echo "WARNING: unknown operaion: " . $stack[$i] . "\n";
            }
    
            if(
                $op !== null
                && $a !== null
                && $b !== null
            ){
                $res = null;
                if($op == '+'){
                    $res = $a + $b;
                }else{
                    $res = $a * $b;
                }
                
                //echo "$a $op $b = $res | i=$i\n";
                return solve( array_merge([$res], array_slice($stack, $i+1)) );
            }
        }
    }
    return $stack[0];
}
function solveLine($line){
    return solve( string2stack($line) );
}

//================================================================

$input = file_get_contents('input.txt');
$input = explode("\n", $input);

$sum = 0;
foreach($input as $line){
    $sum += solveLine($line);
}
echo "Sum: $sum\n";

?>