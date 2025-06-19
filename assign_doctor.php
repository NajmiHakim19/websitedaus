<?php
session_start();

$conn = new mysqli("localhost", "web40", "web40", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor = trim($_POST['doctor']);
    $appointment_id = (int) $_POST['appointment_id'];
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';

    // Debug
    echo "<pre>DEBUG POST:\n";
    print_r($_POST);
    echo "</pre>";

    if (!empty($doctor) && !empty($username) && $appointment_id > 0) {
        $sql = "
            INSERT INTO assign_doctor (appointment_id, doctor_name, username)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                doctor_name = VALUES(doctor_name),
                username = VALUES(username),
                assigned_at = CURRENT_TIMESTAMP
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $appointment_id, $doctor, $username);

        if ($stmt->execute()) {
            header("Location: viewappointment.php?success=1");
            exit();
        } else {
            echo "Execution error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Missing or invalid data.";
    }
}

$conn->close();
?>
