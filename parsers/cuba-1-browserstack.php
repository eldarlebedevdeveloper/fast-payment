<?php
require_once ('../including/generate-data-cuba.php');
require_once ('../db/global_variables.php');
require_once ('../including/mysql_operations.php');
require_once ('../admin/modules/operatins_links_browserstack_get_link.php');

require_once('vendor/autoload.php');
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition; 
use Facebook\WebDriver\PHPWebDriver_WebDriverWait; 

$global_connect_link_to_browserstack = get_browserstack_link($connection_db_data,$global_db_name_browserstack_links);
$amount = $_POST['amount']; 
$currency =  $_POST['currecy_select'];
$last_id_in_db = get_last_id_from_db($global_main_db_name);
$last_id_in_db++;

function executeTestCase($caps) {
    global $global_connect_link_to_browserstack;
    global $userData; 
    global $amount;
    global $currency;
    global $global_main_db_name;
    global $global_test_message;

    $direction_db_message = $global_test_message . ' Cuba 1';
    add_data_to_db($global_main_db_name,$amount,$currency, $direction_db_message, 'no link', 'Generate link in process', $userData);
    
    $web_driver = RemoteWebDriver::create(
        $global_connect_link_to_browserstack,
        $caps
    ); 

    // =========== Код що парсить стінку до генерації лінка =========
    $web_driver->get("#");

    $donation_amount = $web_driver->findElement(WebDriverBy::name("suma"));
    $donation_amount->sendKeys($userData['donation_amount']);

    if($currency === "EUR") $currency_number = 2;
    if($currency === "USD") $currency_number = 3;

    $cur_item = $web_driver->findElement(WebDriverBy::cssSelector("input[value='".$currency_number."']"));
    $cur_item->click();

    $name = $web_driver->findElement(WebDriverBy::name("nume"));
    $name->sendKeys($userData['name']);
    
    $surname = $web_driver->findElement(WebDriverBy::name("prenume"));
    $surname->sendKeys($userData['surname']);

    $email = $web_driver->findElement(WebDriverBy::name("email"));
    $email->sendKeys($userData['email']);
    
    $send = $web_driver->findElement(WebDriverBy::cssSelector("input[value='Pasul urmator']"));
    $send->click();

    sleep(7);  

    $paymentPageLink = $web_driver->getCurrentUrl();
    $content = $web_driver->getPageSource();
    $linkAndContent = [$paymentPageLink,$content];
    generateResponse($linkAndContent);

    # Setting the status of test as 'passed' or 'failed' based on the condition; if title of the web page starts with 'BrowserStack'
    if (substr($web_driver->getTitle(),0,12) == "BrowserStack"){
        $web_driver->executeScript('browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"passed", "reason": "Yaay! Title matched!"}}' );
    }  else {
        $web_driver->executeScript('browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"passed", "reason": "Oops! Title did not match!"}}');
    }
    $web_driver->quit();
}
$caps = array(
    array(
        'bstack:options' => array(
            "os" => "Windows",
            "osVersion" => "10",
            "browserVersion" => "102.0",
            "buildName" => "BStack Build Number 1",
            "sessionName" => "$global_test_message Client Europe 1 ID:$last_id_in_db",
            "local" => "false",
            "seleniumVersion" => "4.0.0",
        ),
        "browserName" => "Firefox"
    ),
);
foreach ( $caps as $cap ) {
    executeTestCase($cap);
}
function generateResponse($linkAndContent){
    addLinik($linkAndContent[0]);
}
$mainLink;
$textFinishLink;
$textFinishLinkCopy;
function addLinik($paymentLink){
    global $mainLink;
    global $textFinishLink;
    global $textFinishLinkCopy;
    global $last_id_in_db; 
    global $global_main_db_name; 
    global $global_main_link; 
    $paymentLinkDomain =  substr($paymentLink, 0 , 27); 
    if($paymentLinkDomain == '#'){
        $mainLink = $paymentLink; 
        $textFinishLink = 'Open link';
        $textFinishLinkCopy = 'Copy link';
            update_data_in_db($global_main_db_name,$last_id_in_db, 'Success:Link generated', $mainLink);
        
    }else{
        $mainLink = $global_main_link;
        $textFinishLink = 'Try again';
        $textFinishLinkCopy = 'Try again';
        update_data_in_db($global_main_db_name,$last_id_in_db, 'Error: Link did not generate', 'no link');
    }

} 
?>  
<link rel="stylesheet" href="../front_end/parser.css">
<div class="ready_link">
    <div class="ready_link_container">    
        <a href="<?php echo $mainLink; ?>" class="open_link"><?php echo $textFinishLink; ?></a>
        <?php if($textFinishLinkCopy == 'Copy link'){ ?>
            <a href="#" data-readylink="<?php echo $mainLink; ?>" class="copy_link"><?php echo $textFinishLinkCopy; ?></a>
        <?php } ?>
    </div>
    <a href="<?php echo $global_main_link; ?>" class="back_link">back</a>
 </div>
 <script src="../front_end/copy_link.js"></script>