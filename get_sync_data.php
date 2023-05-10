<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "Nuh uh";
    exit;
}

require_once "config.php";

$id = $_SESSION["id"];

$sql = "SELECT data FROM users WHERE id = $id ";

$result = $link->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo $row["data"];
  }
} else {
  echo "0 results";
}

$link->close();