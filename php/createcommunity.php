<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['Create'])) {
    if (empty($_POST['community_name'])) {
        if (empty($_POST['community_name'])) {
            $error = "Community Name can't be blank";
            echo($error);
            $_SESSION['error1'] = $error;
            header("location: ../createcomm.php"); // Redirecting back
            }
        
}
else
{
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
    $connection = mysqli_connect("localhost", "root", "redhat");
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
        echo('connection to db failed');
        echo($connection);
    }
    echo("Connected successfully \n");
    // To protect MySQL injection for Security purpose
    $commname = ($_POST['community_name']);
    // Selecting Database
    $db = mysqli_select_db($connection, "cmpe281");
    // SQL query to fetch information of registerd users and finds user match.
    $query = mysqli_query($connection, "select * from community_details where comm_name='$commname';");
    $rows = mysqli_num_rows($query);
    echo("Number of username rows = ".$rows);
    if ($rows == 0) {
        $result = shell_exec('/usr/bin/python /var/www/html/testscript.py &' . $commname);
        echo($result);
    }
    else {
        $error = "Community name is occupied, try another!";
        $_SESSION['error1'] = $error;
        header("location: ../createcomm.php"); // Redirecting To Registration Page
        }
    mysqli_close($connection); // Closing Connection
}
}
?>