
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
    <meta name="description" content="About MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR, a web developer with a passion for creating responsive websites.">

    <!--PAGE TITLE-->
    <title>About Us- For patient</title>

    <!--CSS FILE LINK-->
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <!--HEADER-->
    <header>
        <nav class="nav-container">
            <div class="logo">Hi <?php echo htmlspecialchars($firstname); ?></div>
            <ul class="nav-links">
            <li><a href="logout.php">Logout</a></li>
                <li><a href="../Ariff/patient.php">Home</a></li>
                <li><a href="../Ariff/booking.php">Booking Appointment</a></li>
                <li><a href="mybooking.php">Your Appointment</a></li>
                <li><a href="aboutForPatient.php"class="active">About Us</a></li>
                <li><a href="informationHubForPatient.php">Information Hub</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <!--ABOUT ME CONTENT -->
    <main>
    <section class="about">
            <h1>About Us</h1>
            <p>We are....</p>
            
        </section>
    </main>

    <!--FOOTER-->
    <footer>
    <p>&copy; 2025 CANCER INFORMATION AND SUPPORT. BY, Group 2: TECHNO.</p>
    <script src="../script.js"></script>
</body>
</html>