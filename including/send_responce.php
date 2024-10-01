<?php
function send_responce($brandDataAray){
    $data = [
        'status' => 0,
        'account_id' => $brandDataAray['account_id'],
        'amount' => $brandDataAray['amount'],
    ];
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $brandDataAray['callback_link']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);
    }
}