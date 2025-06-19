<?php
session_start();
require_once "db_connect.php";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int) $_POST['appointment_id'];

    $sql = "DELETE FROM assign_doctor WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: doctor.php");
exit();
