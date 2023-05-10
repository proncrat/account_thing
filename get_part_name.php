<?php

date_default_timezone_set("Pacific/Auckland");

$current_time = date('y-m-d H:i:s');
$formated_time = date("H",strtotime($current_time));
if ($formated_time >= 4 && $formated_time < 12) {
    $response_text =  "Good Morning ";
}elseif ($formated_time >= 12 && $formated_time < 17){
    $response_text =  "Good Afternoon ";
}elseif ($formated_time >= 17 && $formated_time < 21){
    $response_text =  "Good Evening ";
}elseif ($formated_time >= 21){
    $response_text =  "Good Night ";
}else{
    $response_text =  "Hi there ";
}

$response = array("time"=>$current_time,"time_text"=>$response_text);
header("Content-Type: application/json");
echo json_encode($response);