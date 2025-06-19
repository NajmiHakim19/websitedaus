<?php
session_start();

// Example: Assume NRIC is stored in session after login
if (!isset($_SESSION['icnumber'])) {
    // Redirect if not logged in
    header("Location: login.php");
    exit();
}

$currentUserNric = $_SESSION['icnumber'];

// Database connection
$conn = new mysqli("localhost", "web40", "web40", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get only this user's appointments
$appointments = [];
$stmt = $conn->prepare("SELECT * FROM appointment_bookings WHERE icnumber = ? ORDER BY id DESC");
$stmt->bind_param("s", $currentUserNric);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
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
        <div class="logo">MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="booking.php" class="active">Booking Appointment</a></li>
                <li><a href="mybooking.php" class="active">Your Appointment</a></li>
                <li><a href="about.php">About Me</a></li>
                <li><a href="projects.php">Projects</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section id="home" class="hero">
        <h1>Admin Dashboard</h1>

        <!-- Table displaying appointments -->
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
            <th>Assign Doctor</th> <!-- New column -->
        </tr>
    </thead>
    <tbody>
<?php foreach ($appointments as $appointment): ?>
    <?php
        // For each appointment, fetch assigned doctor
        $doctorName = '-';
        $appointmentId = $appointment['id'];

        $conn = new mysqli("localhost", "web40", "web40", "daus");
        if (!$conn->connect_error) {
            $stmt = $conn->prepare("SELECT doctor_name FROM assign_doctor WHERE appointment_id = ? ORDER BY id DESC LIMIT 1");
            $stmt->bind_param("i", $appointmentId);
            $stmt->execute();
            $stmt->bind_result($doctorResult);
            if ($stmt->fetch()) {
                $doctorName = $doctorResult;
            }
            $stmt->close();
            $conn->close();
        }
    ?>
    <tr>
        <form action="assign_doctor.php" method="POST">
            <td><?php echo htmlspecialchars($appointment['id']); ?></td>
            <td><?php echo htmlspecialchars($appointment['fullname']); ?></td>
            <td><?php echo htmlspecialchars($appointment['icnumber']); ?></td>
            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
            <td><?php echo htmlspecialchars($appointment['time']); ?></td>
            <td><?php echo htmlspecialchars($appointment['concern']); ?></td>
            <td><?php echo htmlspecialchars($doctorName); ?></td>
        </form>
    </tr>
<?php endforeach; ?>
</tbody>


</table>

        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>

        <a href="#projects" class="cta-button">View My Work</a>
        <?php if (!empty($submission_message)): ?>
            <p class="submission-message"><?php echo $submission_message; ?></p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR. A24CS5031.</p>
        <div class="social-links">
            <a href="https://github.com/leecinsiak" target="_blank" aria-label="GitHub Profile">GitHub LINK</a>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
