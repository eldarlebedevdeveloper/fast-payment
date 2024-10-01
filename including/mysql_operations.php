<?php
$connection_db_data = array(
  "dbServername" => "lax024.hawkhost.com",
  "dbUsername" => "quarksol_stas",
  "dbPassword" => "ZI3gZq{wVkg6",
  "dbName" => "quarksol_applications"
  );
$conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);

//Визначення останнього id у базі данних
function get_last_id_from_db($db_name){
  global $connection_db_data;
  $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);

  $selectquery="SELECT id FROM $db_name ORDER BY id DESC LIMIT 1";
  $result = $conn->query($selectquery);
  $row = $result->fetch_assoc();
  return $row['id'];
  $conn->close(); 
} 


 // Додавання у базу данних
function add_data_to_db($db_name,$amount,$currency,$direction,$generate_link,$status_operation,$user_data){

    global $connection_db_data;
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    $user_data = json_encode($user_data); 
    $date = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR']; 
    $sql = "INSERT INTO $db_name (direction, amount,currency, ip, generate_link, status_operation, generate_date,	user_data)
            VALUES ('$direction', '$amount', '$currency', '$ip', '$generate_link', '$status_operation', '$date', '$user_data')"; 

    $conn->query($sql);
    $conn->close(); 
}

// Додавання у базу данних
function update_data_in_db($db_name,$id_operation,$status_operation,$link){
  global $connection_db_data;
  $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);

  $sql = "UPDATE $db_name SET status_operation='$status_operation', generate_link='$link' WHERE id='$id_operation'";

  $conn->query($sql);
  $conn->close();
}