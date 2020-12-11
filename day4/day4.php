<?php

function isValid($passport){

    //echo "==============\n";

    $f = array();
    $tmp = explode("\n", $passport);
    //var_dump($tmp);
    foreach($tmp as $x){
        foreach(explode(" ", $x) as $toAdd){
            $f[] = $toAdd;
        }
    }
    //var_dump($f);
    //echo "***\n";

    $fields = array();
    foreach($f as $field){
        $tmpArr = explode(":", $field);
        
        //var_dump($tmpArr);
        if(count($tmpArr) >= 2){
            $fields[$tmpArr[0]] = $tmpArr[1];
        }
    }

    //var_dump($fields);
    //echo isset($fields['byr']) . " | " . isset($fields['iyr']) . " | " .     isset($fields['eyr']) . " | " .     isset($fields['hgt']) . " | " .     isset($fields['hct']) . " | " .     isset($fields['ecl']) . " | " .     isset($fields['pid']) . "\n\n\n&\n";

    return (
        isset(
            $fields['byr'],
            $fields['iyr'],
            $fields['eyr'],
            $fields['hgt'],
            $fields['hcl'],
            $fields['ecl'],
            $fields['pid']
        )
    );
}

function isValid2($passport){

    //echo "==============\n";

    $f = array();
    $tmp = explode("\n", $passport);
    //var_dump($tmp);
    foreach($tmp as $x){
        foreach(explode(" ", $x) as $toAdd){
            $f[] = $toAdd;
        }
    }
    //var_dump($f);
    //echo "***\n";

    $fields = array();
    foreach($f as $field){
        $tmpArr = explode(":", $field);
        
        //var_dump($tmpArr);
        if(count($tmpArr) >= 2){
            $fields[$tmpArr[0]] = $tmpArr[1];
        }
    }

    var_dump($fields);
    //echo isset($fields['byr']) . " | " . isset($fields['iyr']) . " | " .     isset($fields['eyr']) . " | " .     isset($fields['hgt']) . " | " .     isset($fields['hct']) . " | " .     isset($fields['ecl']) . " | " .     isset($fields['pid']) . "\n\n\n&\n";

    echo "|";
    if(!isset(
        $fields['byr'],
        $fields['iyr'],
        $fields['eyr'],
        $fields['hgt'],
        $fields['hcl'],
        $fields['ecl'],
        $fields['pid']
    )){
        return false;
    }


    if(
        !( strlen($fields['byr']) == 4 && intval($fields['byr']) >= 1920 && intval($fields['byr']) <= 2002 )
    ){
        return false;
    }

    
    
    if(
        !( strlen($fields['iyr']) == 4 && intval($fields['iyr']) >= 2010 && intval($fields['iyr']) <= 2020 )
    ){
        return false;
    }

    if(
        !(strlen($fields['eyr']) == 4 && intval($fields['eyr']) >= 2020 && intval($fields['eyr']) <= 2030)
    ){
        return false;
    }
    
    //echo $fields['hgt'] . "\n";
    if(strpos($fields['hgt'], "cm") > 0){
        //cm
        if(
            !( intval($fields['hgt']) >= 150 && intval($fields['hgt']) <= 193 )
        ){
            return false;
        }
    }else if(strpos($fields['hgt'], "in") > 0){
        //in
        if(
            !( intval($fields['hgt']) >= 59 && intval($fields['hgt']) <= 76 )
        ){
            return false;
        }
    }else{
        return false;
    }
    
    if(
        !( $fields['hcl'][0] == '#' && strlen($fields['hcl']) == 7 )
    ){
        return false;
    }
    
    if(
        !(
            $fields['ecl'] == "amb"
            || $fields['ecl'] == "blu"
            || $fields['ecl'] == "brn"
            || $fields['ecl'] == "gry"
            || $fields['ecl'] == "grn"
            || $fields['ecl'] == "hzl"
            || $fields['ecl'] == "oth"
        )
    ){
        return false;
    }
    
    if(
        !( strlen($fields['pid']) == 9 )
    ){
        return false;
    }
    return true;

}

$input = file_get_contents('input.txt');

$passports = explode("\n\n", $input);

$valid = 0;
$valid2 = 0;
foreach($passports as $passport){
    if(isValid($passport)){
        ++$valid;
    }
    if(isValid2($passport)){
        ++$valid2;
    }
}
echo "Valid passports1: " . $valid . "\n";
echo "Valid passports2: " . $valid2 . "\n";

?>