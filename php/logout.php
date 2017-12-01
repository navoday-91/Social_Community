<?php
session_start(); // Starting Session
session_destroy();
header("location: ../index.php"); // Redirecting To home Page
?>