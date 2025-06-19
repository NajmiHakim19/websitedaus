<?php
session_start();
require_once "db_connect.php";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Debug: Show session info
echo "<pre>DEBUG: SESSION\n";
print_r($_SESSION);
echo "</pre>";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointment data
$appointments = [];
$result = $conn->query("SELECT * FROM appointment_bookings ORDER BY id DESC");

if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
    
    // Debug: show fetched appointments
    echo "<pre>DEBUG: Appointments Fetched\n";
    print_r($appointments);
    echo "</pre>";
} else {
    echo "<pre>DEBUG: No appointments found in the database.</pre>";
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
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
        <div class="logo">Hi <?php echo htmlspecialchars($username) ?></div>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php">Logout</a></li>
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
        <tr>
            <form action="assign_doctor.php" method="POST" class="doctor-form">
                <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                <td><?php echo htmlspecialchars($appointment['fullname']); ?></td>
                <td><?php echo htmlspecialchars($appointment['icnumber']); ?></td>
                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                <td><?php echo htmlspecialchars($appointment['concern']); ?></td>
                
                <td>
                <select name="doctor" required>
                    <option value="">-- Select Doctor --</option>
                    <option value="Dr. Firdaus" <?php if ($appointment['doctor'] === 'Dr. Firdaus') echo 'selected'; ?>>Dr. Firdaus</option>
                </select>
                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                    <button type="submit" class="save-button">Save</button>
                </td>
            </form>
        </tr>
    <?php endforeach; ?>
</tbody>

</table>

        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>

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
