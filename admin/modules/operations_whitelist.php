<?php
function add_ip_to_db($connection_db_data,$db_name,$ip,$brand){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    $sql = "SELECT ip FROM $db_name WHERE ip='$ip'"; 
    $result = $conn->query($sql); 
    if($result->fetch_assoc() !== NULL){
        return 'This ip already exist';
    }elseif($ip == ''){
        return 'The IP field is empty'; 
    }else{
        $date = date('Y-m-d H:i:s');
        $sql_2 = "INSERT INTO $db_name (ip, brand, date_adding) VALUES ('$ip', '$brand', '$date')"; 
    
         if ($conn->query($sql_2) === TRUE) {
              return "New records created successfully";
        } else {
              return "Error: " . $sql_2 . "<br>" . $conn->error;
        }
    }
    $conn->close(); 
}

function delete_ip_from_db($connection_db_data,$db_name,$ip){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
        $sql = "DELETE FROM $db_name WHERE ip='$ip'"; 

        if ($conn->query($sql) === TRUE) { 
            return "Record deleted successfully";  
        }else {
            return "Error deleting record: " . $conn->error;
        }
    $conn->close(); 
}

function show_ips_table($connection_db_data,$db_name,$global_main_link){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    $sql = "SELECT id, ip, brand, date_adding FROM $db_name ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo ' <div class="table">
            <ul class="row table_title">
                <li class="col"><p>id</p></li>
                <li class="col"><p>ip</p></li> 
                <li class="col"><p>brand</p></li>
                <li class="col"><p>date_adding</p></li>
                <li class="col"><p></p></li>
            </ul>';
            while($row = $result->fetch_assoc()) {
                echo '
                <ul class="row ">
                    <li class="col"><p>'.$row["id"].'</p></li>
                    <li class="col"><p>'.$row["ip"].'</p></li>
                    <li class="col"><p>'.$row["brand"].'</p></li>
                    <li class="col"><p>'.$row["date_adding"].'</p></li>
                    <li class="col table_delete_item">
                        <form action"'. $global_main_link.'/admin/index.php?page=whitelist" method="post" class="table_delete_form">
                            <input type="text" name="table_delete_item_whitelist" value="'.$row["ip"].'">
                            <input type="submit" name="table_delete_button_whitelist" value="+">
                        </form>
                    </li>
                </ul>';
            }
        echo '</div>';

    } else {
         echo "0 results";
    }
    $conn->close();
}

function get_ips_array($connection_db_data,$db_name){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    $sql = "SELECT ip FROM $db_name";
    $result = $conn->query($sql);
    $arrya_ips = [];
    if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($arrya_ips,$row["ip"]);
            }
    } else {
        $arrya_ips = [];
    }
    $conn->close();
    return $arrya_ips;
}