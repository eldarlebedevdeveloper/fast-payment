<?php
function show_api_tables($connection_db_data,$global_main_db_name_api,$search_email,$brand){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if($search_email === false){
        if($brand === 'all'){
            $sql = "SELECT id, user_id, account_id, email, direction, brand, amount, currency, ip, generate_link, status_operation, generate_date, additional_information FROM $global_main_db_name_api ORDER BY id DESC";
        }else{
            $sql = "SELECT id, user_id, account_id, email, direction, brand, amount, currency, ip, generate_link, status_operation, generate_date, additional_information FROM $global_main_db_name_api WHERE brand='$brand' ORDER BY id DESC";
        }
    }else{
        $sql = "SELECT id, user_id, account_id, email, direction, brand, amount, currency, ip, generate_link, status_operation, generate_date, additional_information FROM $global_main_db_name_api WHERE email='$search_email' ORDER BY id DESC";
    }

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) { 
        echo ' 
            <div class="table">
                <ul class="row table_title">
                    <li class="col"><p>id</p></li>
                    <li class="col"><p>user_id</p></li>
                    <li class="col"><p>account_id</p></li>
                    <li class="col"><p>email</p></li>
                    <li class="col"><p>direction</p></li>
                    <li class="col"><p>brand</p></li>
                    <li class="col"><p>amount</p></li> 
                    <li class="col"><p>currency</p></li>  
                    <li class="col"><p>ip</p></li>
                    <li class="col"><p>generate_link</p></li>
                    <li class="col"><p>status_operation</p></li>
                    <li class="col"><p>generate_date</p></li>
                    <li class="col"><p>additional_information</p></li>
                </ul>';
                while($row = mysqli_fetch_assoc($result)) {
                    echo ' 
                    <ul class="row"> 
                        <li class="col"><p>'.$row["id"].'</p></li>
                        <li class="col"><p>'.$row["user_id"].'</p></li>
                        <li class="col"><p>'.$row["account_id"].'</p></li>
                        <li class="col"><p>'.$row["email"].'</p></li>
                        <li class="col"><p>'.$row["direction"].'</p></li>
                        <li class="col"><p>'.$row["brand"].'</p></li>
                        <li class="col"><p>'.$row["amount"].'</p></li>
                        <li class="col"><p>'.$row["currency"].'</p></li>  
                        <li class="col"><p>'.$row["ip"].'</p></li>
                        <li class="col"><p>'.$row["generate_link"].'</p></li>
                        <li class="col"><p>'.$row["status_operation"].'</p></li>
                        <li class="col"><p>'.$row["generate_date"].'</p></li>
                        <li class="col"><p>'.$row["additional_information"].'</p></li>
                    </ul>';
                }
            echo '</div>';
    } else {
    echo "0 results";  
    }
    mysqli_close($conn);
}