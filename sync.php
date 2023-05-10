<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "No Sync?";
    exit;
}

require_once "config.php";
date_default_timezone_set("Pacific/Auckland");


$id = $_SESSION["id"];
$current_time = date('y-m-d H:i:s');
$sync_data = $_POST["sync"];

$sql = "UPDATE users SET sync_time = '$current_time' , data = '$sync_data' WHERE id = $id ";

if ($link->query($sql) === TRUE) {
    echo "Worked";
} else {
    echo "Error updating record: " . $link->error;
}

$link->close();



?>