<?php
session_start();
require_once "db_connect.php";

// Debug: Show POST data
echo "<pre>DEBUG: POST Data\n";
print_r($_POST);
echo "</pre>";

// Database connection
//$conn = new mysqli("localhost", "root", "", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor = trim($_POST['doctor']);
    $appointment_id = (int) $_POST['appointment_id'];

    if (!empty($doctor) && $appointment_id > 0) {
        // Insert/update into assign_doctor table
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
                echo "<pre>DEBUG: Insert or update success in assign_doctor table.</pre>";

                // ✅ ALSO update doctor in appointment_bookings
                $updateSql = "UPDATE appointment_bookings SET doctor = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                if ($updateStmt) {
                    $updateStmt->bind_param("si", $doctor, $appointment_id);
                    if ($updateStmt->execute()) {
                        echo "<pre>DEBUG: Doctor name also updated in appointment_bookings table.</pre>";
                    } else {
                        echo "<pre>DEBUG: Execute error on appointment_bookings update: " . $updateStmt->error . "</pre>";
                    }
                    $updateStmt->close();
                } else {
                    echo "<pre>DEBUG: Prepare error on appointment_bookings update: " . $conn->error . "</pre>";
                }

                header("Location: admin.php?assigned=success");
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
