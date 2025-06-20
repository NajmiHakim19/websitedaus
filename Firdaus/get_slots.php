<?php


header('Content-Type: application/json');
require_once "../db_connect.php";


$date = isset($_GET['date']) ? $_GET['date'] : '';
$doctor = isset($_GET['doctor']) ? $_GET['doctor'] : '';

if (empty($date) || empty($doctor)) {
    echo json_encode([]);
    exit();
}

// Fetch booked time slots
$sql = "SELECT time FROM appointment_bookings WHERE date = ? AND doctor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $date, $doctor);
$stmt->execute();
$result = $stmt->get_result();

$bookedSlots = [];
while ($row = $result->fetch_assoc()) {
    $bookedSlots[] = $row['time'];
}

$stmt->close();
$conn->close();

// Return JSON list of booked slots
echo json_encode($bookedSlots);
