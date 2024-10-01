<?php
function create_api_tables($connection_db_data,$global_main_db_name_api){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);

    // Check connection 
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT id, user_id, account_id, direction, amount, currency, ip, generate_link, status_operation, generate_date, additional_information FROM $global_main_db_name_api ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '
            <table>
                <tr>
                    <th>id</th>
                    <th>user_id</th>
                    <th>account_id</th>
                    <th>direction</th>
                    <th>amount</th>
                    <th>currency</th>  
                    <th>ip</th>
                    <th>generate_link</th>
                    <th>status_operation</th>
                    <th>generate_date</th>
                    <th>additional_information</th>
                </tr>';

                while($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <tr>
                        <th>'.$row["id"].'</th>
                        <th>'.$row["user_id"].'</th>
                        <th>'.$row["account_id"].'</th>
                        <th>'.$row["direction"].'</th>
                        <th>'.$row["amount"].'</th>
                        <th>'.$row["currency"].'</th>  
                        <th>'.$row["ip"].'</th>
                        <th>'.$row["generate_link"].'</th>
                        <th>'.$row["status_operation"].'</th>
                        <th>'.$row["generate_date"].'</th>
                        <th>'.$row["additional_information"].'</th>
                    </tr>';
                }

            echo '</table>';
    } else {
    echo "0 results";  
    }
    mysqli_close($conn);
}