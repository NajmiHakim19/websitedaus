<?php
// Database connection
require_once "../db_connect.php";
session_start();
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "Guest";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="nav-container">
    <div class="logo">Hi <?php echo htmlspecialchars($firstname); ?></div>
</div>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php">Logout</a></li>
                <li><a href="patient.php" class="active">Home</a></li>
                <li><a href="Firdaus/booking.php">Booking Appointment</a></li>
                <li><a href="mybooking.php">Your Appointment</a></li>
                <li><a href="aboutForPatient.php">About Us</a></li>
                <li><a href="informationHubForPatient.php">Information Hub</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section id="home" class="hero">
        <h1>patient Module</h1>
        <p>Hi
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