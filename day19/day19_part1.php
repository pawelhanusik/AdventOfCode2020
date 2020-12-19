<?php

$input = file_get_contents('input.txt');
$input = explode("\n\n", $input);

$rulesRaw = $input[0];
$messages = $input[1];

$rulesRaw = explode("\n", $rulesRaw);
$messages = explode("\n", $messages);

$rules = [];
foreach($rulesRaw as $r){
    [$id, $ruleStr] = explode(": ", $r);
    $rules[$id] = explode(" ", $ruleStr);
}

//generate rule 0's rexeg
function genRegex($rules, $rid = 0){
    $ret = "";

    for($i = 0; $i < count($rules[$rid]); ++$i){
        $x = $rules[$rid][$i];
        if( is_numeric($x) ){
            $ret .= '(' . genRegex($rules, intval($x) ) . ')';
        }else if( $x[0] == '"' && $x[strlen($x) - 1] == '"' ){
            $ret .= substr($x, 1, strlen($x) - 2) . '';
        }else{
            $ret .= $x . '';
        }
    }

    return $ret;
}

$re = genRegex($rules);
//echo $re . "\n";
//echo "==================\n";
$validMessages = 0;
foreach($messages as $message){
    if(
        preg_match("/^$re\$/", $message)
    ){
        ++$validMessages;
    }
}
echo "Valid messages: $validMessages\n";

?>