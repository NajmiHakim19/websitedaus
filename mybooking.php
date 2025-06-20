<?php
session_start();

$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "Guest";

if (!isset($_SESSION['icnumber'])) {
    header("Location: login.php");
    exit();
}

$currentUserNric = $_SESSION['icnumber'];

require_once "db_connect.php";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get appointments for the current user
$appointments = [];
$stmt = $conn->prepare("SELECT * FROM appointment_bookings WHERE icnumber = ? ORDER BY id DESC");
$stmt->bind_param("s", $currentUserNric);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}

// Prepare doctor assignments in one go for performance
$doctorMap = [];
if (!empty($appointments)) {
    $ids = implode(',', array_column($appointments, 'id'));
    $sql = "SELECT appointment_id, doctor_name FROM assign_doctor WHERE appointment_id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $doctorMap[$row['appointment_id']] = $row['doctor_name'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header class="nav-container">
        <div class="logo">Hi <?php echo htmlspecialchars($firstname); ?></div>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php">Logout</a></li>
                <li><a href="patient.php">Home</a></li>
                <li><a href="booking.php">Booking Appointment</a></li>
                <li><a href="mybooking.php" class="active">Your Appointment</a></li>
                <li><a href="aboutForPatient.php">About Us</a></li>
                <li><a href="InformationHub.php">Information Hub</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section id="home" class="hero">
        <h1>Patient Dashboard</h1>
        <p>Below are your list of appointments:</p>

        <?php if (!empty($appointments)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>NRIC</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Purpose</th>
                        <th>Phone No</th>
                        <th>Assign Doctor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['icnumber']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['concern']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['phone']); ?></td>
                            <td><?php echo htmlspecialchars($doctorMap[$appointment['id']] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 CANCER INFORMATION AND SUPPORT. BY, A24CS5031.</p>
        <div class="social-links">
            <a href="https://github.com/leecinsiak" target="_blank" aria-label="GitHub Profile">GitHub LINK</a>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
