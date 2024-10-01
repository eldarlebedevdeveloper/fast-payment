<?php
// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, $data);
fclose($myfile);
