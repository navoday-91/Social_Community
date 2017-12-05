<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['Login'])) {
if (empty($_POST['user_username']) || empty($_POST['user_password'])) {
$error = "Username or Password is invalid";
echo($error);
$_SESSION['error'] = $error;
header("location: ../index.php"); // Redirecting back
}
else
{
if (isset($_POST['Login'])){
if(($_POST['user_username'] == 'admin') && ($_POST['user_password'] == 'redhat@123')){
$_SESSION['role']='admin';
header("location: ../client.php");
}
else {
$error = "Username or Password is invalid";
$_SESSION['error'] = $error;
echo($error);
header("location: ../index.php"); // Redirecting To Login Page
}
}
}
}
?>