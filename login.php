<?php
session_start(); // Start session to store login info


// Database connection
require_once "db_connect.php";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login submission
$login_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    

    // Debug: Show input values
    echo "<pre>";
    echo "DEBUG INFO:\n";
    echo "Entered Username: " . $username . "\n";
    echo "Entered Password: " . $password . "\n";
    echo "</pre>";

    // Query to check credentials
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

    // Debug: Show the SQL query
    echo "<pre>";
    echo "SQL Query: $sql\n";
    echo "</pre>";

    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['userType'] = $row['userType'];
        $_SESSION['icnumber'] = $row['icnumber'];          
        $_SESSION['fullname'] = $row['fullname'];     
        $_SESSION['username'] = $row['username'];
        $_SESSION['firstname'] = $row['firstname'];
        $_SESSION['lastname'] = $row['lastname'];   



        // Debug: Show retrieved values
        echo "<pre>";
        echo "Retrieved Username: " . $row['username'] . "\n";
        echo "Retrieved UserType: " . $row['userType'] . "\n";
        echo "</pre>";

        // Redirect based on userType
        if ($row['userType'] === 'patient') {
            header("Location: patient.php");
            exit();
        } elseif ($row['userType'] === 'doctor') {
            header("Location: doctor.php");
            exit();
        } elseif ($row['userType'] === 'admin') {
            header("Location: admin.php");
            exit();
        } else {
            $login_message = "Unknown user type.";
        }
    } else {
        $login_message = "Invalid username or password.";

        // Extra debug if result is empty
        echo "<pre>DEBUG: No matching user found in database.</pre>";
    }
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="nav-container">
        <div class="logo">MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR</div>
        <nav>
            <ul class="nav-links">
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="informationHub.php">information Hub</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section class="hero">
        <h1>Login</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="contact-form">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="cta-button">Login</button>
</form>

        <?php if (!empty($login_message)): ?>
            <p class="submission-message"><?php echo $login_message; ?></p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR. A24CS5031.</p>
        <div class="social-links">
            <a href="https://github.com/leecinsiak" target="_blank">GitHub LINK</a>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
