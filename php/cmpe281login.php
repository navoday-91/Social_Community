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

$dbpath = "54.183.103.17";
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysqli_connect($dbpath, "root", "redhat", "cmpe281");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
    echo('connection to db failed');
    echo($connection);
}
echo("Connected successfully \n");
$db = mysqli_select_db($connection, "cmpe281");
$community = $_POST['community'];
$query = mysqli_query($connection, "select * from community_details where comm_name = '$community';");
// SQL query to fetch information of registerd users and finds user match.
$rows = mysqli_num_rows($query);
// To protect MySQL injection for Security purpose
if ($rows == 1) {
    while ($user = $query->fetch_assoc()) {
        $commpath = $user["lb_ip"];
        $community = $user["comm_name"];
        
    }
}
$_SESSION['community']=$community; 
$_SESSION['role']=$role; 
mysqli_close($connection); // Closing Connection
echo($commpath);
header("location: $commpath/index.php"); // Redirecting To Community Page

}
}
?>