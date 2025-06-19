<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "web40", "web40", "daus");
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data for prefill
$prefill_fullname = "";
$prefill_icnumber = "";
if (isset($_SESSION['username'])) {
    $username = $conn->real_escape_string($_SESSION['username']);
    $user_query = "SELECT * FROM users WHERE username='$username'";
    $user_result = $conn->query($user_query);
    if ($user_result && $user_result->num_rows === 1) {
        $user = $user_result->fetch_assoc();
        $prefill_fullname = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
        $prefill_icnumber = htmlspecialchars($user['icnumber']);
    }
}

// Handle appointment booking submission
$submission_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor = $conn->real_escape_string($_POST['doctor']);
    $concern = $conn->real_escape_string($_POST['concern']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $salutation = $conn->real_escape_string($_POST['salutation']);
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $icnumber = $conn->real_escape_string($_POST['icnumber']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $specialty = $conn->real_escape_string($_POST['specialty'] ?? '');

    $sql = "INSERT INTO appointment_bookings (doctor, specialty, concern, date, time, salutation, fullname, icnumber, phone)
            VALUES ('$doctor', '$specialty', '$concern', '$date', '$time', '$salutation', '$fullname', '$icnumber', '$phone')";

    if ($conn->query($sql) === TRUE) {
        $submission_message = "Your appointment request has been received. Our team will contact you to confirm the date and time.";
    } else {
        $submission_message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Appointment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="nav-container">
<div class="logo">
    <div class="logo">Hi <?php echo htmlspecialchars($username) ?></div>
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="booking.php" class="active">Booking Appointment</a></li>
            <li><a href="about.php">About Me</a></li>
            <li><a href="projects.php">Projects</a></li>
        </ul>
        <div class="hamburger">☰</div>
    </nav>
</header>

<section id="booking" class="hero">
    <h1>Appointment Booking</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="contact-form">
        <h2>1. Appointment Details</h2>
        <div class="form-group">
            <label>Preferred Doctor:</label>
            <select name="doctor">
                <option value="2">Dr. Firdaus</option>
            </select>
        </div>
        <div class="form-group">
            <label>Medical Concern/Request:</label>
            <textarea name="concern" required></textarea>
        </div>

        <h2>2. Select Your Date</h2>
        <div class="form-group">
            <label>Appointment Date:</label>
            <input type="date" name="date" required>
        </div>

        <h2>3. Select Your Time</h2>
        <div class="form-group">
            <input type="time" name="time" required>
        </div>

        <h2>4. Patient's Details</h2>
        <div class="form-group">
            <label>Salutation:</label>
            <select name="salutation" required>
                <option value="">Please Select</option>
                <option value="Mr">Mr</option>
                <option value="Ms">Ms</option>
                <option value="Mrs">Mrs</option>
                <option value="Dr">Dr</option>
            </select>
        </div>
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="fullname" required value="<?php echo $prefill_fullname; ?>">
        </div>
        <div class="form-group">
            <label>NRIC / Passport No:</label>
            <input type="text" name="icnumber" required value="<?php echo $prefill_icnumber; ?>">
        </div>
        <div class="form-group">
            <label>Phone Number:</label>
            <input type="text" name="phone" placeholder="+60" required>
        </div>

        <p class="note">Kindly note this is not a confirmed appointment. Our Customer Service team will contact you to finalize your appointment date & time based on doctor's availability.</p>

        <div class="form-group">
            <button type="submit" class="cta-button">Submit</button>
        </div>
    </form>

    <?php if (!empty($submission_message)): ?>
        <p class="submission-message"><?php echo $submission_message; ?></p>
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
