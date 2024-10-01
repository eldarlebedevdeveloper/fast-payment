<?php
function add_browserstack_link_to_db($connection_db_data,$db_name,$link){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    $sql = "SELECT link FROM $db_name WHERE link='$link'"; 
    $result = $conn->query($sql); 

    if($result->fetch_assoc() !== NULL){
        return 'This link already exist';
    }elseif($link == ''){

        return 'The link field is empty'; 
    }else{
        $date = date('Y-m-d H:i:s');
        $sql_2 = "INSERT INTO $db_name (link, date_adding)
                VALUES ('$link', '$date')"; 
        $conn->query($sql_2);
        $conn->close(); 
    }
}   

function delete_browserstack_link_from_db($connection_db_data,$db_name,$id){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
        $sql = "DELETE FROM $db_name WHERE id='$id'"; 
        if ($conn->query($sql) === TRUE) { 
            return "Record deleted successfully";  
        }else {
            return "Error deleting record: " . $conn->error;
        }
    $conn->close(); 
} 
 
function get_browserstack_link_from_db($connection_db_data,$db_name,$global_main_link){
    $conn = new mysqli($connection_db_data['dbServername'],$connection_db_data['dbUsername'],$connection_db_data['dbPassword'],$connection_db_data['dbName']);
    $sql = "SELECT id, link, date_adding FROM $db_name ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    // output data of each row
    echo '
    <div class="table">
        <ul class="row table_title">
            <li class="col"><p>id</p></li>
            <li class="col"><p>link</p></li>
            <li class="col"><p>date adding</p></li>
            <li class="col"></p></li>
        </ul>';
        while($row = $result->fetch_assoc()) {
            echo '
                <ul class="row">
                    <li class="col"><p> '. $row["id"] .' </p></li>
                    <li class="col"><p>'. $row["link"] .'</p></li>
                    <li class="col"><p>'. $row["date_adding"] .'</p></li>
                    <li class="col table_delete_item">
                        <form action"'. $global_main_link.'/admin/index.php?page=browserstack" method="post" class="table_delete_form">
                            <input type="text" name="table_delete_item_whitelist" value="'.$row["id"].'">
                            <input type="submit" name="table_delete_button_browserstack" value="+">
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