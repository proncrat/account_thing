<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "Sign in";
    exit;
}
require_once "config.php";
$id = $_SESSION["id"];

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$pfp = $_POST['pfp'];
echo $pfp;

if (empty($password)){
    $sql = "UPDATE users SET username = '$username' , email = '$email' WHERE id = $id ";
}else{
    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET username = '$username' , password = '$hash_password' , email = '$email' WHERE id = $id ";
}

if ($link->query($sql) === TRUE) {
    echo "Worked";
} else {
    echo "Error updating record: " . $link->error;
}
$link->close();
?>