<?php
function createRandomString($arr){
    $rand_arr = rand(0, (count($arr) - 1));
    if($rand_arr % 2){
        $rand_num_male = rand(0,count($arr[$rand_arr]['male'])-1);
        $name = $arr[$rand_arr]['male'][$rand_num_male];
    }else{
        $rand_num_female = rand(0,count($arr[$rand_arr]['female'])-1);
        $name = $arr[$rand_arr]['female'][$rand_num_female];
    }
    $rand_num_surename = rand(0,count($arr[$rand_arr]['surnames'])-1);
    $surname = $arr[$rand_arr]['surnames'][$rand_num_surename];
    $person = [$name, $surname,$rand_arr];

    return $person;
}

function randomEmail($names,$surname,$domains){
    $cr_sub = strtolower($names . $surname);
    $domain_num = rand(0,count($domains)-1);
    $domain = $domains[$domain_num];

    $email =  $cr_sub . $domain ;
    return $email; 
}

function randomPhoneNumber($country_code,$numbersLimit){
    $random_number;
    for($i = 0;$i < $numbersLimit; $i++){
        $random_number .= rand(0, 9);
    }

    $pnone_number = strval($country_code . $random_number);
    return $pnone_number;
}

function findLinkOrString($result, $findString,$startCutPosition,$stringLength){
    $aror = strpos($result, $findString);
    $startCut = $aror + $startCutPosition;
    $linik = substr($result, $startCut , $stringLength);
    return $linik;
} 

function createRandomCityAndAddress($cities,$locationNameLastname){
    $arrCountry = $cities[$locationNameLastname];
    $rand_num = rand(0, (count($arrCountry) - 1));
    $rand_city = $arrCountry[$rand_num][0]; 
    $rand_address = $arrCountry[$rand_num][1]; 
    $location = [$rand_city,$rand_address];

    return $location; 
}