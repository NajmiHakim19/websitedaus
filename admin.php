<?php
// Database connection
//$conn = new mysqli("localhost", "root", "", "daus");
$conn = new mysqli("localhost", "root", "", "daus");
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";


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
    <title>Guest</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="nav-container">
    <div class="logo">Hi <?php echo htmlspecialchars($username) ?></div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="viewappointment.php">View Appointment</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section id="home" class="hero">
        <h1>Admin Dashboard</h1>
        <p>Hi, I'm MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR, UTM Student learning to become web developer creating responsive and user-friendly websites.</p>

        
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