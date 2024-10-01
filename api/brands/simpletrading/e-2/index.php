<?php 
include '../../../including/send_data_to_crm.php';
include '../../../../db/connec_link_to_browserstack.php';
include '../../../front_end/card_form.php'; 
include '../../../../including/functions.php';
include '../../../parsers/api-europe-2-browserstack.php';
include '../../../parsers/api-europe-2-card-browserstack.php'; 
include '../../../../db/email_domains.php';
include '../../../including/api_mysql_operations.php';
$names_json = file_get_contents('../../../../db/different_names.json');
$decoded_names_json = json_decode($names_json, true); 
$countries_json = file_get_contents('../../../../db/countries.json');
$decode_countries_json = json_decode($countries_json, true);

$connect_link_to_browserstack = $connect_link_to_browserstack;

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
 
if($_POST['Pay']){
    $payer_account_id = $_POST['Pay']['account_id'];
    $payer_user_id = $_POST['Pay']['user_id'];
    $donation_amount = $_POST['Pay']['value'];
    $all_tragers_room_data = $_POST['Pay'];
}

if($_POST['number']){
    $cardData =  $_POST; 
}

$userData = [
    'donation_amount' => $donation_amount,
    'name' => $name,
    'surname' => $surname,
    'email' => $email,
];

if($donation_amount){
    add_data_to_db('fast_payment_api_test',$payer_account_id,$payer_user_id,$donation_amount,'TEST Europe 2, SimpleTrading','no link', 'Generate link in process', $all_tragers_room_data);
    $id_operation = get_last_id_from_db('fast_payment_api_test');
    $directionMessageForParserCreationLink = 'TEST Europe 2, Creation Link, SimpleTrading , ID:'.$id_operation;

    session_start();
    $_SESSION["session_payer_account_id"] =  $payer_account_id;
    $_SESSION["session_payer_user_id"] =  $payer_user_id;
    $_SESSION["session_donation_amount"] = $donation_amount;
    $_SESSION["id_operation"] = $id_operation; 

    $parserClientDataResponse = api_europe_2_browserstack($userData,$connect_link_to_browserstack,$directionMessageForParserCreationLink);

    if($parserClientDataResponse[1] == 'Success'){ 
        update_data_in_db('fast_payment_api_test',$id_operation, 'Success: Link was generated', $parserClientDataResponse[0]);

        $html_form = html_form($parserClientDataResponse[0], 'simpletrading/e-2/'); 
        echo $html_form; 
        
    }elseif($parserClientDataResponse[1] == 'Error'){ 
        update_data_in_db('fast_payment_api_test',$id_operation, 'Error: Error generate link','no link');
        echo '<h2 class="error">Error generate link<h2 class="title_parsers">';
    }   
} 
elseif($cardData['number']){
    session_start();
    $session_payer_account_id = $_SESSION['session_payer_account_id'];
    $session_payer_user_id = $_SESSION['session_payer_user_id'];
    $session_donation_amount = $_SESSION['session_donation_amount'];
    $session_id_operation = $_SESSION['id_operation'];
    $directionMessageForParserCard = 'TEST Europe 2  ,Card Data, SimpleTrading , ID:'.$session_id_operation;
    $parserCardResponse = api_europe_2_parse_card_browserstack($cardData,$connect_link_to_browserstack,$directionMessageForParserCard);

    if($parserCardResponse[0] == 'Success'){
        session_unset(); 
        session_destroy();
    
        send_data_to_crm($session_payer_account_id,$session_donation_amount,'kutiy6tryL4fNx8','#','54891247','admin@simpletrading.biz','nKC05dWLD3dHGzd','FastPayment Europe 2');
        update_data_in_db('fast_payment_api_test',$session_id_operation, 'Success: Payment successful',false);
        
        echo '<h2 class="title_parsers">ID:'.$session_id_operation.' ,Payment successful</h2>';
        
    }elseif($parserCardResponse[0] == 'Failed'){ 
        session_unset(); 
        session_destroy();
     
        update_data_in_db('fast_payment_api_test',$session_id_operation, 'Error: The payment was not sent',false);

        echo '<h2 class="title_parsers">ID:'.$session_id_operation.' ,The payment was not sent</h2>';
        echo '<p class="text_parsers"><strong>We regret!</strong> Your order was not successfully completed. The answer from the bank is: Registration failed.
        Check with the issuing bank whether you are using a 3DSecure registered bank card or whether Internet use is permitted</p>';
        
    }elseif($parserCardResponse[0] == 'Unknown error'){
        session_unset();
        session_destroy();
       
        update_data_in_db('fast_payment_api_test',$session_id_operation, 'Error: Unknown payment error',false);
       
        echo '<h2 class="title_parsers">ID:'.$session_id_operation.' ,Unknown error</h2>';
    } 
}else{
    echo '<h2 class="title_parsers">Use this link only from the trading account</h2>';
}
?>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://quarksolution.org/fast-payment/api/front_end/css/payment_form.css">
</head>