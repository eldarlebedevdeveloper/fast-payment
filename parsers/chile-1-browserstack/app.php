<?php 
include '../../../including/send_data_to_crm.php';
include '../../../../db/global_variables.php';
include '../../../../admin/modules/operatins_links_browserstack_get_link.php';
include '../../../front_end/card_form.php'; 
include '../../../../including/functions.php';
include '../../../parsers/api-europe-1-browserstack.php';
include '../../../parsers/api-europe-1-card-browserstack.php'; 
include '../../../../db/email_domains.php';
include '../../../including/api_mysql_operations.php';
include '../../../including/UTIP_find_lead_email.php';
$names_json = file_get_contents('../../../../db/different_names.json');
$decoded_names_json = json_decode($names_json, true);
$countries_json = file_get_contents('../../../../db/countries.json');
$decode_countries_json = json_decode($countries_json, true);
$global_connect_link_to_browserstack = get_browserstack_link($connection_db_data,$global_db_name_browserstack_links);
$global_main_db_name_api = $global_main_db_name_api;
 
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

$brand = 'SimpleTrading'; 
$direction = 'Europe 1';  
$clientRoomData = $_POST['clientroomdata'];
$clientRoomEncodeData = get_object_vars(json_decode($clientRoomData));
$clientRoomEncodeData = get_object_vars($clientRoomEncodeData['Pay']);

if($clientRoomEncodeData){
    $payer_account_id = $clientRoomEncodeData['account_id'];
    $payer_user_id = $clientRoomEncodeData['user_id'];
    $donation_amount = $clientRoomEncodeData['value'];
    $payer_currency = $clientRoomEncodeData['currency_id'];
    $all_tragers_room_data = $clientRoomEncodeData;   
}

if($_POST['number']){
    $cardData =  $_POST; 
}

$userData = [
    'donation_amount' => $donation_amount,
    'name' => $name,
    'surname' => $surname,
    'email' => $email,
    'currency' => $payer_currency
];


if($donation_amount){
    $id_operation = get_last_id_from_db($global_main_db_name_api);
    $directionMessageForParserCreationLink = $global_test_message. $direction .', Creation Link, '.$brand .' , ID:'.$id_operation. ', AMOUNT:'.$donation_amount. $payer_currency ;

    session_start();
    $_SESSION["session_payer_account_id"] =  $payer_account_id;
    $_SESSION["session_payer_user_id"] =  $payer_user_id;
    $_SESSION["session_donation_amount"] = $donation_amount; 
    $_SESSION["session_payer_currency"] = $payer_currency; 
    $_SESSION["session_id_operation"] = $id_operation; 

    $parserClientDataResponse = api_europe_1_browserstack($userData,$global_connect_link_to_browserstack,$directionMessageForParserCreationLink);
     
    if($parserClientDataResponse[1] == 'Success'){ 
        $html_form = html_form($parserClientDataResponse[0], $global_main_link, 'simpletrading/e-1/app.php'); 
        echo $html_form; 
    }elseif($parserClientDataResponse[1] == 'Error'){ 
        echo '<h2 class="error">Error generate link<h2 class="title_parsers">';
    }     
}  
elseif($cardData['number']){ 

    session_start();
    $session_payer_account_id = $_SESSION['session_payer_account_id'];
    $session_payer_user_id = $_SESSION['session_payer_user_id'];
    $session_donation_amount = $_SESSION['session_donation_amount'];
    $session_payer_currency = $_SESSION['session_payer_currency'];
    $session_id_operation = $_SESSION['session_id_operation'];

    $directionMessageForParserCard = $global_test_message . $direction . ',Card Data, '. $brand .' , ID:'.$session_id_operation. ', AMOUNT:'.$session_donation_amount. $session_payer_currency;
    $parserCardResponse = api_europe_1_parse_card_browserstack($cardData,$global_connect_link_to_browserstack,$directionMessageForParserCard);

    if($parserCardResponse[0] == 'Success'){ 
        session_unset();  
        session_destroy();  
    
        send_data_to_crm($session_payer_account_id,$session_donation_amount,'kutiy6tryL4fNx8','#','54891247','admin@simpletrading.biz','nKC05dWLD3dHGzd','FastPayment' . $direction);
        update_data_in_db($global_main_db_name_api,$session_id_operation, 'Success: Payment successful',false);

        echo '<h2 class="title_parsers">ID:'.$session_id_operation.' ,Payment successful</h2>';
        
    }elseif($parserCardResponse[0] == 'Failed'){ 
        session_unset(); 
        session_destroy();  
  
        update_data_in_db($global_main_db_name_api,$session_id_operation, 'Error: The payment was not sent',false);

        echo '<h2 class="title_parsers">ID:'.$session_id_operation.' ,The payment was not sent</h2>';
        echo '<p class="text_parsers"><strong>We regret!</strong> Your order was not successfully completed. The answer from the bank is: Registration failed.
        Check with the issuing bank whether you are using a 3DSecure registered bank card or whether Internet use is permitted</p>';
    }elseif($parserCardResponse[0] == 'Unknown error'){
        session_unset();
        session_destroy();
       
        update_data_in_db($global_main_db_name_api,$session_id_operation, 'Error: Unknown payment error',false);
        
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
    <link rel="stylesheet" type="text/css" href="<?php echo $global_main_link ?>/api/front_end/css/payment_form.css">
</head> 