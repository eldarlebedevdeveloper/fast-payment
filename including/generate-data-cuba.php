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
 
$England = $decoded_names_json[19];  
$Estonia = $decoded_names_json[20];
$Finland = $decoded_names_json[21];
$France = $decoded_names_json[22];
$Germany = $decoded_names_json[24];
$europe = [
    $England, $Estonia, $Finland , $France, $Germany
];

$person = createRandomString($europe ,true);
$name = $person[0];
$surname = $person[1];
$email = randomEmail($name,$surname,$email_domains);
$country = $_POST['country'];

$userData = [
    'donation_amount' => $donation_amount,
    'name' => $name,
    'surname' => $surname,
    'email' => $email,
];
$dataForDB = [
    'connection' => $connMYSQL,
    'donation_amount' => $donation_amount,
    'country' => $country
];