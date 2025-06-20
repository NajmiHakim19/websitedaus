<?php
// Database connection
require_once "db_connect.php";


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
    <title></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="nav-container">
        <div class="logo">CANCER INFORMATION AND SUPPORT</div>
        <nav>
            <ul class="nav-links">
                <li><a href="Ariff/login.php">Login</a></li>
                <li><a href="Ariff/register.php">Register</a></li>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="informationHub.php">Information Hub</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section id="home" class="hero">
        <h1>Welcome to CANCER INFORMATION AND SUPPORT</h1>
        <p>Hi, </p>
        <?php if (!empty($submission_message)): ?>
            <p class="submission-message"><?php echo $submission_message; ?></p>
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