<?php
session_start();

$conn = new mysqli("localhost", "web40", "web40", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor = trim($_POST['doctor']);
    $appointment_id = (int) $_POST['appointment_id'];

    if (!empty($doctor) && $appointment_id > 0) {
        // ✅ Insert or update doctor assignment
        $sql = "
            INSERT INTO assign_doctor (appointment_id, doctor_name)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE 
                doctor_name = VALUES(doctor_name),
                assigned_at = CURRENT_TIMESTAMP
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $appointment_id, $doctor);

        if ($stmt->execute()) {
            header("Location: index.php?message=Doctor assigned or updated");
            exit();
        } else {
            echo "Execution error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid data. Please try again.";
    }
}

$conn->close();
?>
