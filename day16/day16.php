<?php

class Range {
    private int $from;
    private int $to;
    public function __construct(int $from, int $to){
        $this->from = $from;
        $this->to = $to;
    }

    public static function fromRangeString(string $rangeStr){
        $tmp = explode("-", $rangeStr);
        return new Range(intval($tmp[0]), intval($tmp[1]));
    }

    public function inRange($value){
        return ($value >= $this->from && $value <= $this->to);
    }
}
class Field {
    public string $name;
    private Range $range1;
    private Range $range2;

    public function __construct(string $name, string $range1, string $range2){
        $this->name = $name;
        $this->range1 = Range::fromRangeString($range1);
        $this->range2 = Range::fromRangeString($range2);
    }

    public function isValueValid($value){
        return (
            $this->range1->inRange($value)
            || $this->range2->inRange($value)
        );
    }
}
class Ticket {
    private array $values;

    public function __construct(array $values){
        $this->values = $values;
    }

    public static function fromLineString(string $line){
        $tmpArr = [];
        foreach(explode(",", $line) as $val){
            $tmpArr[] = intval($val);
        }
        return new Ticket($tmpArr);
    }

    public function getField($fieldID){
        return $this->values[$fieldID];
    }
    
    public function isFieldValid($fieldID, &$fields){
        $value = $this->values[$fieldID];
        
        $isValid = false;
        foreach($fields as $field){
            if($field->isValueValid($value)){
                return true;
            }
        }
        return false;
    }
    public function isValid(&$fields){
        for($i = 0; $i < count($this->values); ++$i){
            if(!$this->isFieldValid($i, $fields)){
                return false;
            }
        }
        return true;
    }
    public function scanningError(&$fields){
        $ret = 0;
        for($i = 0; $i < count($this->values); ++$i){
            if(!$this->isFieldValid($i, $fields)){
                $ret += $this->values[$i];
            }
        }
        return $ret;
    }

    public function numberOfFields(){
        return count($this->values);
    }
}

//==================

$input = file_get_contents('input.txt');
function processInput($input){
    $input = explode("\n\n", $input);
    $input[0] = explode("\n", $input[0]);
    $input[2] = explode("\n", $input[2]);

    //FIELDS
    foreach($input[0] as $fieldLine){
        $tmp = explode(": ", $fieldLine);
        $name = $tmp[0];
        $tmp = explode(" or ", $tmp[1]);
        $range1 = $tmp[0];
        $range2 = $tmp[1];

        $fields[] = new Field($name, $range1, $range2);
    }

    //MY TICKET
    $myTicket = Ticket::fromLineString($input[1]);

    //OTHER TICKETS
    foreach($input[2] as $ticketLine){
        $otherTickets[] = Ticket::fromLineString($ticketLine);
    }

    return [$fields, $myTicket, $otherTickets];
}

[$fields, $myTicket, $otherTickets] = processInput($input);

$ticketScanningErrorRate = 0;
foreach($otherTickets as $ticket){
    $ticketScanningErrorRate += $ticket->scanningError($fields);
}
echo "ticketScanningErrorRate: $ticketScanningErrorRate\n";


//PART TWO
/*function printPossibleFieldsFor($possibleFieldsFor, $startFieldID=0){
    for($i = $startFieldID; $i < count($possibleFieldsFor); ++$i){
        echo "FOR FIELD $i:\n";
        foreach($possibleFieldsFor[$i] as $f){
            echo "\t$f->name\n";
        }
    }
}
//printPossibleFieldsFor($possibleFieldsFor);
*/

//remove invalid tickets
for($i = 0; $i < count($otherTickets); ){
    if(!$otherTickets[$i]->isValid($fields)){
        array_splice($otherTickets, $i, 1);
    }else{
        ++$i;
    }
}

//let assume all fields are valid for everything
$possibleFieldsFor = [];
$allFields = [];
foreach($fields as $f){
    $allFields[] = $f;
}
for($i = 0; $i < $myTicket->numberOfFields(); ++$i){
    $possibleFieldsFor[$i] = $allFields;
}

//remove invalid assumptions
for($fieldID = 0; $fieldID < $myTicket->numberOfFields(); ++$fieldID){
    foreach($otherTickets as $t){
        for($i = 0; $i < count($possibleFieldsFor[$fieldID]); ){
            $fieldsToTest = [ $possibleFieldsFor[$fieldID][$i] ];
            if( !$t->isFieldValid($fieldID, $fieldsToTest) ){
                //echo "FID=$fieldID\tNAME=" . $fieldsToTest[0]->name . "\tOtherTicket value: " . $t->values[$fieldID] . "\tISVALID=" . $t->isValid($fields) . "\n";
                array_splice($possibleFieldsFor[$fieldID], $i, 1);
            }else{
                ++$i;
            }
        }
    }
}


$finalFieldsMapping = [];
do{
    $field2remove = null;
    //find field to remove
    for($fieldID = 0; $fieldID < $myTicket->numberOfFields(); ++$fieldID){
        if(count($possibleFieldsFor[$fieldID]) == 1){
            $field2remove = $possibleFieldsFor[$fieldID][0];
            //echo "FieldID=$fieldID is " . $field2remove->name . "\n";
            $finalFieldsMapping[$fieldID] = $field2remove;
            break;
        }
    }
    //echo "Field to remove: " . $field2remove->name . "\n";
    //remove it
    if($field2remove != null){
        for($fieldID = 0; $fieldID < $myTicket->numberOfFields(); ++$fieldID){
            for($j = 0; $j < count($possibleFieldsFor[$fieldID]); ++$j){
                if( $possibleFieldsFor[$fieldID][$j] == $field2remove ){
                    array_splice($possibleFieldsFor[$fieldID], $j, 1);
                    break;
                }
            }
        }   
    }
}while($field2remove != null);

//multiply all fields on my ticket starting with 'departure'
$mul = 1;
foreach($finalFieldsMapping as $fieldID => $field){
    if( strpos($field->name, "departure") === 0 ){
        $mul *= $myTicket->getField($fieldID);
    }
}
echo "Mul: $mul\n";

?>