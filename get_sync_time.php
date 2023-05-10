<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "Local";
    exit;
}

require_once "config.php";
date_default_timezone_set("Pacific/Auckland");
 
$id = $_SESSION["id"];
$current_time = date('y-m-d H:i:s');

$sql = "SELECT sync_time FROM users WHERE id = $id ";

$result = $link->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $sync_time = $row["sync_time"];
  }
} else {
  echo "0 results";
}

$link->close();

$to_time = strtotime($current_time);
$from_time = strtotime($sync_time);
$min_from_sync = round(abs($to_time - $from_time) / 60);
if($min_from_sync == 0){
    echo "Just now";
}elseif($min_from_sync <=60){
  echo $min_from_sync. " minutes ago";
}else{
    $timestamp = date("Y.m.d\\TH:i", strtotime($sync_time));
    $today = new DateTime("today");
    $match_date = DateTime::createFromFormat( "Y.m.d\\TH:i", $timestamp );
    $match_date->setTime( 0, 0, 0 );
    $diff = $today->diff( $match_date );
    $diffDays = (integer)$diff->format( "%R%a" );
    switch( $diffDays ) {
        case 0:
            echo "Today ".date("h:i a",strtotime($sync_time));
            break;
        case -1:
            echo "Yesterday ".date("h:i a",strtotime($sync_time));
            break;
        default:
            echo date("d/m/Y",strtotime($sync_time));
    }
}