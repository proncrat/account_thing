<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "No Sync?";
    exit;
}

require_once "config.php";
date_default_timezone_set("Pacific/Auckland");
 
$id = $_SESSION["id"];

$sql = "SELECT * FROM users WHERE id = $id ";

$result = $link->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $returned_id = $row["id"];
    $returned_username= $row["username"];
    $returned_created_at= $row["created_at"];
    $returned_email= $row["email"];
    $image= $row["image"];
  }
} else {
  echo "0 results";
}

$link->close();

$response = array("id"=>$returned_id,"username"=>$returned_username,"created_at"=>date("Y.m.d", strtotime($returned_created_at)),"email"=>$returned_email,"image"=>$image);
header("Content-Type: application/json");
echo json_encode($response);