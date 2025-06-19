<?php
session_start();
echo "<pre>Logged in as: " . $_SESSION['username'] . "</pre>";


// Check if doctor is logged in
if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctorName = $_SESSION['username'];
 // assuming this matches doctor_name in assign_doctor table

// Database connection
$conn = new mysqli("localhost", "web40", "web40", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointments assigned to this doctor
$sql = "
    SELECT ab.id, ab.fullname, ab.icnumber, ab.date, ab.time, ab.concern, ad.assigned_at
    FROM assign_doctor ad
    JOIN appointment_bookings ab ON ad.appointment_id = ab.id
    WHERE ad.username = ?
    ORDER BY ab.date DESC, ab.time ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctorName);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
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
    <title>doctor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="nav-container">
        <div class="logo">MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="about.php">About Me</a></li>
                <li><a href="projects.php">Projects</a></li>
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section id="home" class="hero">
        <h1>Doctor</h1>
        <p>Hi, I'm MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR, UTM Student learning to become web developer creating responsive and user-friendly websites.</p>

        <h2>Appointment Details</h2>
<?php if (!empty($appointments)): ?>
    <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>NRIC</th>
                <th>Date</th>
                <th>Time</th>
                <th>Purpose</th>
                <th>Assigned At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $app): ?>
                <tr>
                    <td><?php echo htmlspecialchars($app['id']); ?></td>
                    <td><?php echo htmlspecialchars($app['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($app['icnumber']); ?></td>
                    <td><?php echo htmlspecialchars($app['date']); ?></td>
                    <td><?php echo htmlspecialchars($app['time']); ?></td>
                    <td><?php echo htmlspecialchars($app['concern']); ?></td>
                    <td><?php echo htmlspecialchars($app['assigned_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No appointments assigned to you.</p>
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