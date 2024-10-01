<?php
function show_client_tables($connection_db_data,$global_main_db_name_api){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } 
    $sql = "SELECT id, direction, amount, currency, ip, generate_link, status_operation, generate_date, user_data FROM $global_main_db_name_api ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '
            <div class="table">
                <ul class="row table_title">
                    <li class="col"><p>id</p></li>
                    <li class="col"><p>direction</p></li>
                    <li class="col"><p>amount</p></li>
                    <li class="col"><p>currency</p></li>
                    <li class="col"><p>ip</p></li>
                    <li class="col"><p>generate_link</p></li>  
                    <li class="col"><p>status_operation</p></li>
                    <li class="col"><p>generate_date</p></li>
                    <li class="col"><p>user_data</p></li>
                </ul>';
                while($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <ul class="row">
                        <li class="col"><p>'.$row["id"].'</p></li>
                        <li class="col"><p>'.$row["direction"].'</p></li>
                        <li class="col"><p>'.$row["amount"].'</p></li>
                        <li class="col"><p>'.$row["currency"].'</p></li>
                        <li class="col"><p>'.$row["ip"].'</p></li>
                        <li class="col "><p>'.$row["generate_link"].'</p></li>  
                        <li class="col"><p>'.$row["status_operation"].'</p></li>
                        <li class="col"><p>'.$row["generate_date"].'</p></li>
                        <li class="col"><p>'.$row["user_data"].'</p></li>
                    </ul>';
                }
            echo '</div>';
    } else {
    echo "0 results";  
    }
    mysqli_close($conn);
}