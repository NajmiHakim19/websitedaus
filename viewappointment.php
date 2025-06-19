<?php
session_start();

$conn = new mysqli("localhost", "web40", "web40", "daus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor = trim($_POST['doctor']);
    $appointment_id = (int) $_POST['appointment_id'];

    if (!empty($doctor) && $appointment_id > 0) {
        // ✅ Insert or update doctor assignment
        $sql = "
            INSERT INTO assign_doctor (appointment_id, doctor_name)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE 
                doctor_name = VALUES(doctor_name),
                assigned_at = CURRENT_TIMESTAMP
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $appointment_id, $doctor);

        if ($stmt->execute()) {
            header("Location: index.php?message=Doctor assigned or updated");
            exit();
        } else {
            echo "Execution error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid data. Please try again.";
    }
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
        <div class="logo">MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR</div>
        <nav>
            <ul class="nav-links">
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
        <p>Hi, I'm MUHAMMAD FIRDAUS BIN MD SHAHRUNNAHAR, UTM Student learning to become web developer creating responsive and user-friendly websites.</p>

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
            <form action="assign_doctor.php" method="POST">
                <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                <td><?php echo htmlspecialchars($appointment['fullname']); ?></td>
                <td><?php echo htmlspecialchars($appointment['icnumber']); ?></td>
                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                <td><?php echo htmlspecialchars($appointment['concern']); ?></td>
                <td>
                    <select name="doctor" required>
                        <option value="">-- Select Doctor --</option>
                        <option value="Dr. Aisyah">Dr. Aisyah</option>
                        <option value="Dr. Firdaus">Dr. Firdaus</option>
                        <!-- Add more doctors as needed -->
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

        <a href="#projects" class="cta-button">View My Work</a>
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
