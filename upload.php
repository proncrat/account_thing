<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "Nuh uh";
    exit;
}
require_once "config.php";
$id = $_SESSION["id"];

$target_dir = "../img/users/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$target_file = $target_dir.$id.".".$imageFileType;

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    echo "fake image";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  unlink($target_file);
}

/*
//Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}
*/

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
  echo "Wrong file type";
  $uploadOk = 0;
}

if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "worked";
  } else {
    echo "didnt work";
    exit;
  }
}

$sql = "UPDATE users SET image = '$target_file' WHERE id = $id ";

if ($link->query($sql) === TRUE) {
    echo "Worked";
} else {
    echo "Error updating record: " . $link->error;
}
$link->close();