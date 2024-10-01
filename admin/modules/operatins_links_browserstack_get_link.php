<?php
function get_browserstack_link($connection_db_data,$db_name){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    $sql = "SELECT link FROM $db_name ORDER BY id DESC";
    $result = $conn->query($sql);
    $last = $result->fetch_assoc();

    $conn->close();
    return $last["link"];
}  