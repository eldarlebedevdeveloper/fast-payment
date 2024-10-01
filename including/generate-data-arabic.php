<?php
include 'db_include.php';
include 'connect_mysql_db.php'; 
include 'functions.php'; 
 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['amount'])){
        $donation_amount = $_POST['amount'];
    }else{
        echo 'Go back and click the Pay button <br>';
    } 
}else{
    echo 'Send please POST request <br/>'; 
}

$SaudiArabiaLatin = $decoded_names_json[49];
$TurkeyLatin = $decoded_names_json[57];
$arabic = [
    $SaudiArabiaLatin, $TurkeyLatin
];  
 
$SaudiArabiaCities = $decode_countries_json['SaudiArabiaWithAddress'];
$TurkeyCities = $decode_countries_json['TurkeyWithAddres'];
$cities = [
    $SaudiArabiaCities,$TurkeyCities
];

$amount = $_POST['amount']; 
$person = createRandomString($arabic ,true); 
$name = $person[0];
$surname = $person[1];
$locationNameLastname = $person[2];
$email = randomEmail($name,$surname,$email_domains);
$country = $_POST['country']; 
$location = createRandomCityAndAddress($cities,$locationNameLastname);  
$city = $location[0];
$address = $location[1];
$phone = randomPhoneNumber('+961', 7);  

$userData = [
    'donation_amount' => $donation_amount,
    'name' => $name,
    'surname' => $surname,
    'email' => $email,
    'locationNameLastname' => $locationNameLastname,
    'city' => $city ,
    'address' => $address,
    'phone' => $phone ,
    'amount' => $amount
];

$dataForDB = [
    'connection' => $connMYSQL,
    'donation_amount' => $donation_amount,
    'country' => $country
];