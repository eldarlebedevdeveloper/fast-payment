<?php
include '../db/email_domains.php';
$names_json = file_get_contents('../db/different_names.json');
$decoded_names_json = json_decode($names_json, true);
$countries_json = file_get_contents('../db/countries.json');
$decode_countries_json = json_decode($countries_json, true);