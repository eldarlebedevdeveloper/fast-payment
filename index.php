<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="front_end/preloader.css">    
    <link rel="stylesheet" href="front_end/parser.css">    
    <title>Fast payment</title>
</head>
<body>
    <section class='payment'> 
        <h1>Fast payment</h1> 
        <form action="" method="post" >
            <div class='select'>
                <div class="select_header"></div>
                <div class="select_body">
                    <div data-value='parsers/europe-1-browserstack.php' name='Europe-1' data-currecy='EUR,USD' class="option o-choose" >Europe-1</div>
                    <div data-value='parsers/europe-2-browserstack.php' name='Europe-2' data-currecy='LEI' class="option" >Europe-2</div>
                    <div data-value='parsers/arabic-1-browserstack.php' name='Arabic-1' data-currecy='USD' class="option" >Arabic-1</div>
                    <div data-value='parsers/arabic-2-browserstack.php' name='Arabic-2' data-currecy='USD' class="option" >Arabic-2</div>
                    <div data-value='parsers/chile-1-browserstack.php' name='Chile-1' data-currecy='CLP' class="option" >Chile-1</div>
                </div>
                <select name="country"> 
                    <option value="Europe-1" selected></option>
                    <option value="Europe-2"></option> 
                    <option value="Arabic-1"></option> 
                    <option value="Arabic-2"></option>
                    <option value="Chile-1"></option>
                </select> 
            </div>
            <div class='currecy'>
                <div class="currecy_header"></div>
                <div class="currecy_body"></div>
                <select name="currecy_select" class="currecy_select"></select>
            </div>
            <div class='amount'>
                <input type="number" name="amount" placeholder="Amount">
                <div class="amount_currency"></div>
            </div>
            <input type="submit" name="pay" value="Generate" disable>
        </form>
    </section>
    <div class="container_preloader">
        <div class="preloader_spinner"></div>
        <p>Processing can last up to 2 minutes</p>
    </div> 
    <script src="front_end/parser.js"></script>
</body> 
</html>
<?php  
    include 'db/global_variables.php';
    include 'admin/modules/operations_whitelist.php';
    include_once "including/whitelist.php";
    $whitelist = get_ips_array($connection_db_data, $global_db_name_whitelist);
    whitelist($whitelist,$global_main_link);   
 