<?php
session_start();
require_once "../db_connect.php";

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int) $_POST['appointment_id'];

    // First, delete from assign_doctor
    $stmt1 = $conn->prepare("DELETE FROM assign_doctor WHERE appointment_id = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $stmt1->close();

    // Then, delete from appointment_bookings
    $stmt2 = $conn->prepare("DELETE FROM appointment_bookings WHERE id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $stmt2->close();
}

$conn->close();
header("Location: admin.php");
exit();
