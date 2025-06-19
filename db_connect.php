<?php
$host = "localhost";
$db_user = "web40";  // or "root" depending on environment
$db_pass = "web40";  // empty string "" for root usually
$db_name = "daus";

// Create connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
