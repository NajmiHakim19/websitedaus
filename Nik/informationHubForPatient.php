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
    <meta name="description" content="Projects by MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR, showcasing web development work with HTML, CSS, and JavaScript.">

    <!--PAGE TITLE-->
    <title>Information Hub - Patient</title>

    <!--CSS FILE LINK-->
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <!--HEADER-->
    <header>
        <nav class="nav-container">
        <div class="logo">Hi <?php echo htmlspecialchars($firstname); ?></div>
            <ul class="nav-links">
                <li><a href="../Ariff/logout.php">Logout</a></li>
                <li><a href="../Ariff/patient.php">Home</a></li>
                <li><a href="../Firdaus/booking.php">Booking Appointment</a></li>
                <li><a href="../Firdaus/mybooking.php">Your Appointment</a></li>
                <li><a href="aboutForPatient.php">About Us</a></li>
                <li><a href="informationHubForPatient.php" class="active">Information Hub</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

     <!--PROJECT CONTENT -->
    <main>
        <section class="projects">
            <h1>Information Hub</h1>
        </section>
    </main>

    <!--FOOTER-->
    <footer>
    <p>&copy; 2025 CANCER INFORMATION AND SUPPORT. BY, Group 2: TECHNO.</p>
    </footer>
    <script src="../script.js"></script>
</body>
</html>
