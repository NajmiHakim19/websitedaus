<?php

// Database connection
$conn = new mysqli("localhost", "root", "", "daus"); // uses "users" DB

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (login or register)
$login_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If registering a new guest
    if (isset($_POST['register'])) {
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $icnumber = $conn->real_escape_string($_POST['icnumber']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        $userType = "patient"; // by default

        // Debug info
        echo "<pre>REGISTER DEBUG:\n";
        echo "Name: $firstname $lastname\n";
        echo "IC: $icnumber\n";
        echo "Username: $username\n";
        echo "Password: $password\n";
        echo "</pre>";

        // Check if username already exists
        $check = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($check->num_rows > 0) {
            $login_message = "Username already taken.";
        } else {
            $sql = "INSERT INTO users (firstname, lastname, icnumber, username, password, userType)
                    VALUES ('$firstname', '$lastname', '$icnumber', '$username', '$password', '$userType')";

            if ($conn->query($sql) === TRUE) {
                $login_message = "Registration successful! You may now log in.";
            } else {
                $login_message = "Registration failed: " . $conn->error;
            }
        }

    // If logging in
    } else {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        // Debug: Show input values
        echo "<pre>LOGIN DEBUG:\n";
        echo "Entered Username: $username\n";
        echo "Entered Password: $password\n";
        echo "</pre>";

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

        // Debug: Show the SQL query
        echo "<pre>SQL Query: $sql\n</pre>";

        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $_SESSION['username'] = $row['username'];
            $_SESSION['userType'] = $row['userType'];

            // Debug: Show retrieved values
            echo "<pre>";
            echo "Retrieved Username: " . $row['username'] . "\n";
            echo "Retrieved UserType: " . $row['userType'] . "\n";
            echo "</pre>";

            // Redirect based on userType
            if ($row['userType'] === 'patient') {
                header("Location: guest.php");
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
            echo "<pre>DEBUG: No matching user found in database.</pre>";
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - guest</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="nav-container">
        <div class="logo">MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="index.php" class="active">Login</a></li>
                <li><a href="register.php" class="active">Register</a></li>
                <li><a href="about.php">About Me</a></li>
                <li><a href="projects.php">Projects</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section class="hero">
        <h1>Register</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="contact-form">
    <h2>Register as a New Patient</h2>

    <div class="form-group">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required>
    </div>

    <div class="form-group">
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required>
    </div>

    <div class="form-group">
        <label for="icnumber">IC Number:</label>
        <input type="text" id="icnumber" name="icnumber" pattern="\d{12}" title="Enter 12-digit IC number" required>
    </div>

    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit" name="register" class="cta-button">Register</button>
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
