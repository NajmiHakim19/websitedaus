<?php
$host = "localhost";
$db_user = "root";  // or "root" depending on environment
$db_pass = "";  // empty string "" for root usually
$db_name = "canceriinfoandsupport";

// Create connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
