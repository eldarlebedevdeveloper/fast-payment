<?php
    include('../db/global_variables.php');
    include('modules/show_client_tables.php');
    include('modules/show_api_tables.php');
    include('modules/operatins_links_browserstack.php');
    include('modules/operations_whitelist.php');
    include('modules/authorization.php');

    if(isset($_POST["whitelist_ip_send"])){
    $response_add_whitelist = add_ip_to_db($connection_db_data,$global_db_name_whitelist, $_POST["whitelist_ip"],$_POST["whitelist_brand"] );
    } 
    if(isset($_POST["table_delete_button_whitelist"])){
        $response_delete_whitelist = delete_ip_from_db($connection_db_data,$global_db_name_whitelist, $_POST["table_delete_item_whitelist"]);
    }
    if(isset($_POST["browsertack_link_send"])){
        $response_add_browserstack =  add_browserstack_link_to_db($connection_db_data,$global_db_name_browserstack_links, $_POST["browsertack_link"] );
    }
    if(isset($_POST["table_delete_button_browserstack"])){
        $response_delete_browserstack = delete_browserstack_link_from_db($connection_db_data,$global_db_name_browserstack_links, $_POST["table_delete_item_whitelist"]);
    }  
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Fast Payment</title>

    <link rel="stylesheet" href="../front_end/parser.css">
    <link rel="stylesheet" href="../front_end/admin.css">
</head>

<?php
    /*
    ** Define a couple of functions for
    ** starting and ending an HTML document
    */
    function startPage()
    {
        print("<body>\n");
    }

    function endPage()
    {
        print("</body>\n");
    } 
    /* 
    ** test for username/password
    */
    if( ( isset($_SERVER['PHP_AUTH_USER'] ) && ( $_SERVER['PHP_AUTH_USER'] == $global_admin_login ) ) AND
      ( isset($_SERVER['PHP_AUTH_PW'] ) && ( $_SERVER['PHP_AUTH_PW'] == $global_admin_password )) )
    {
        startPage();

?>
    <div class="admin_panel">
        <header class="admin_panel_header">
            <ul>
                <li data-sectionlink="client_statististics"><a href="?page=statistic_client">FastPament</a></li>
                <li data-sectionlink="api_statististics"><a href="?page=statistic_api">FastPament API</a></li>
                <li data-sectionlink="admin_panel_whitelist"><a href="?page=whitelist">Whitelist</a></li>
                <li data-sectionlink="admin_panel_browsersteck"><a href="?page=browserstack">Browserstack</a></li>
            </ul>
        </header>
        <div class="admin_panel_body">
        <?php if($_GET['page'] === 'statistic_client' or !isset($_GET['page'])) { ?>
            <div class="client_statististics" data-sectionname="client_statististics">
                <?php  show_client_tables($connection_db_data,$global_main_db_name); ?>
                <ul class="pagination">
                    <li class="pagination_item pagination_active_item" data-pagination="20">20</li>
                    <li class="pagination_item" data-pagination="50">50</li>
                    <li class="pagination_item" data-pagination="100">100</li>
                    <li class="pagination_item" data-pagination="200">200</li>
                    <li class="pagination_item" data-pagination="all">all</li>
                </ul>
            </div>
        <?php } elseif($_GET['page'] === 'statistic_api') { 
            $brand = $_POST['brands'];
        ?>
            <div class="api_statististics" data-sectionname="api_statististics"> 
                <div class="navigation_panel">
                    <div class="chose_brand">
                        <form action="<?php echo $global_main_link; ?>/admin/index.php?page=statistic_api" method="post" class="api_select">
                            <select name="brands" required='TESTSimpleTrading'>
                                <option value="all">all</option>
                                <option value="TESTSimpleTrading" >TESTSimpleTrading</option>
                            </select>
                            <input type="submit" name="chose_brande_submit" value="choose">
                        </form> 
                        <p><?php echo $brand; ?></p>
                    </div>
                    <div class="find_user">
                        <form action="<?php echo $global_main_link; ?>/admin/index.php?page=statistic_api" method="post">
                            <input type="text" name="search_by_email" placeholder="email">
                            <input type="submit" name="search_by_email_submit" value="search">
                        </form>
                    </div>
                </div>  
                <?php 
                    if(isset($_POST["chose_brande_submit"]) and $brand !== ''){
                        show_api_tables($connection_db_data,$global_main_db_name_api,false,$brand); 
                    }elseif(isset($_POST["search_by_email_submit"])){
                        $user_email = $_POST['search_by_email'];
                        show_api_tables($connection_db_data,$global_main_db_name_api, $user_email , false); 
                    }else{
                        show_api_tables($connection_db_data,$global_main_db_name_api,false, 'all'); 
                    }
                ?>
                <ul class="pagination">
                    <li class="pagination_item pagination_active_item" data-pagination="20">20</li>
                    <li class="pagination_item" data-pagination="50">50</li>
                    <li class="pagination_item" data-pagination="100">100</li>
                    <li class="pagination_item" data-pagination="200">200</li>
                    <li class="pagination_item" data-pagination="all">all</li>
                </ul>
            </div>
        <?php } elseif($_GET['page'] === 'whitelist') { ?>
            <div class="admin_panel_whitelist" data-sectionname="admin_panel_whitelist">
                <div class="whitelist_form">
                    <form action="<?php echo $global_main_link?>/admin/index.php?page=whitelist" method="post">
                        <input type="text" name="whitelist_ip" placeholder="IP" value="">
                        <input type="text" name="whitelist_brand" placeholder="Brand" value="">
                        <input type="submit" name="whitelist_ip_send" value="Add" class="button">
                    </form>
                    <p><?php echo $response_add_whitelist; ?></p>
                    <p><?php echo $response_delete_whitelist; ?></p>
                </div>
                <div class="whitelist_statistics ">
                    <?php
                        show_ips_table($connection_db_data, $global_db_name_whitelist,$global_main_link); 
                    ?>
                    <ul class="pagination">
                        <li class="pagination_item pagination_active_item" data-pagination="20">20</li>
                        <li class="pagination_item" data-pagination="50">50</li>
                        <li class="pagination_item" data-pagination="100">100</li>
                        <li class="pagination_item" data-pagination="200">200</li>
                        <li class="pagination_item" data-pagination="all">all</li>
                    </ul>
                </div> 
            </div>  
        <?php } elseif($_GET['page'] === 'browserstack') { ?>
            <div class="admin_panel_browsersteck" data-sectionname="admin_panel_browsersteck"> 
                <div class="browsersteck_form">
                    <form action="<?php echo $global_main_link?>/admin/index.php?page=browserstack" method="post">
                        <input type="text" name="browsertack_link" placeholder="Add new link" >
                        <input type="submit" name="browsertack_link_send" value="Add" class="button">
                    </form>
                    <p><?php echo $response_add_browserstack; ?></p>
                    <p><?php echo $response_delete_browserstack; ?></p>
                </div>
                <?php
                    get_browserstack_link_from_db($connection_db_data,$global_db_name_browserstack_links,$$global_main_link); 
                ?>
                    <ul class="pagination">
                        <li class="pagination_item pagination_active_item" data-pagination="20">20</li>
                        <li class="pagination_item" data-pagination="50">50</li>
                        <li class="pagination_item" data-pagination="100">100</li>
                        <li class="pagination_item" data-pagination="200">200</li>
                        <li class="pagination_item" data-pagination="all">all</li>
                    </ul>  
            </div>
        <?php } ?>
        
        </div>
    </div>  
<script src="../front_end/admin.js"></script>  
<script src="../front_end/parser.js"></script>   
<?php
        endPage();
    } 
    else
    {
        //Send headers to cause a browser to request
        //username and password from user
        header("WWW-Authenticate: " .
            "Basic realm=\"Leon's Protected Area\"");
        header("HTTP/1.0 401 Unauthorized");

        //Show failure text, which browsers usually
        //show only after several failed attempts
        print("This page is protected by HTTP " .
            "Authentication.<br>\nUse <b>leon</b> " .
            "for the username, and <b>secret</b> " .
            "for the password.<br>\n");
    }

?>
</html>