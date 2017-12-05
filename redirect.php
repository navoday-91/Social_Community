<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
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
mysqli_close($connection); // Closing Connection
$error = "Comm Path".$commpath.",  Communty".$community;
$_SESSION['error'] = $error;
echo("Comm Path".$commpath."Communty".$community);
header("location: ../index.php"); // Redirecting To Community Page
?>