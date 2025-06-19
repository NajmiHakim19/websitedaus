<?php
session_start();

// Debug: Show POST data
echo "<pre>DEBUG: POST Data\n";
print_r($_POST);
echo "</pre>";

// Database connection
$conn = new mysqli("localhost", "web40", "web40", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor = trim($_POST['doctor']);
    $appointment_id = (int) $_POST['appointment_id'];

    if (!empty($doctor) && $appointment_id > 0) {
        $sql = "
            INSERT INTO assign_doctor (appointment_id, doctor_name)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE 
                doctor_name = VALUES(doctor_name),
                assigned_at = CURRENT_TIMESTAMP
        ";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $appointment_id, $doctor);
            if ($stmt->execute()) {
                echo "<pre>DEBUG: Insert or update success.</pre>";
                header("Location: viewappointment.php?message=Doctor assigned or updated");
                exit();
            } else {
                echo "<pre>DEBUG: Execute error: " . $stmt->error . "</pre>";
            }
            $stmt->close();
        } else {
            echo "<pre>DEBUG: Prepare failed: " . $conn->error . "</pre>";
        }
    } else {
        echo "<pre>DEBUG: Invalid input. Doctor: '$doctor', ID: $appointment_id</pre>";
    }
}

$conn->close();
?>
