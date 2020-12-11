<?php

class Queue extends ArrayObject {
    
    private $data = [];
    
    function push($element) {
        $this->data[] = $element;
    }
    function pop(){
        return array_shift($this->data);
    }

    function offsetGet($index){
        return $this->data[$index];
    }
}

function isValid(&$numbers, $nextNumber){
    for($a = 0; $a < 25; ++$a){
        for($b = 0; $b < 25; ++$b){
            if($a == $b){
                continue;
            }

            if($numbers[$a] + $numbers[$b] == $nextNumber){
                return true;
            }
        }
    }
    
    return false;
}

function findContiguousList(&$numbers, $requestedSum){
    for($i = 0; $i <= count($numbers); ++$i){
        $sum = 0;
        $ret = [];
        for($j = $i; $j <= count($numbers) && $sum < $requestedSum; ++$j){
            $sum += $numbers[$j];
            $ret[] = $numbers[$j];
            if($sum == $requestedSum){
                return $ret;
            }
        }
    }
    return false;
}

$input = file_get_contents('input.txt');

$input = explode("\n", $input);
for($i = 0; $i < count($input); ++$i){
    $input[$i] = intval($input[$i]);
}

$allNumbers = [];
$numbers = new Queue();

for($i = 0; $i < 25; ++$i){
    $numbers->push($input[$i]);
    $allNumbers[] = $input[$i];
}

$badNumber = null;
for($i = 25; $i < count($input); ++$i){
    if(isValid($numbers, $input[$i])){
        $numbers->pop();
        $numbers->push($input[$i]);
        $allNumbers[] = $input[$i];
    }else{
        $badNumber = $input[$i];
        break;
    }
}
echo "Bad number: " . $badNumber . "\n";

$contiguousList = findContiguousList($allNumbers, $badNumber);
$min = min($contiguousList);
$max = max($contiguousList);
echo "Weakness: " . ($min + $max) . "\n";

?>