<?php
function whitelist($whitelist,$main_link){
    function isAllowed($ip,$whitelist){
        if(in_array($ip, $whitelist)) { 
            return true;  
        }
        foreach($whitelist as $i){
            $wildcardPos = strpos($i, "*");
            // Check if the ip has a wildcard
            if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
                return true;
            }
        }    
        return false;
    }
    if (!isAllowed($_SERVER['REMOTE_ADDR'],$whitelist)) {
        header('Location: '.$main_link.'/error.php');
    }
} 