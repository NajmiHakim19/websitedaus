<?php
session_start();
$conn = new mysqli("localhost", "root", "", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int) $_POST['appointment_id'];
    $status = $_POST['status'];

    $sql = "UPDATE assign_doctor SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: doctor.php");
exit();
