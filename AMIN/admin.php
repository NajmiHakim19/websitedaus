<?php
session_start();
require_once "../db_connect.php";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Debug: Show session info
echo "<pre>DEBUG: SESSION\n";
print_r($_SESSION);
echo "</pre>";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointment data with assigned doctor and status
$sql = "
    SELECT ab.*, ad.doctor_name, ad.status
    FROM appointment_bookings ab
    LEFT JOIN assign_doctor ad ON ab.id = ad.appointment_id
    ORDER BY ab.id DESC
";

$result = $conn->query($sql);
$appointments = [];
if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
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
            <li><a href="../Ariff/logout.php">Logout</a></li>
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
                    <th>Phone</th>
                    <th>Purpose</th>
                    <th>Assigned Doctor</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                            <td><?php echo htmlspecialchars($appointment['phone'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($appointment['concern']); ?></td>
                            <td>
                                <select name="doctor" required>
                                    <option value="">-- Select Doctor --</option>
                                    <option value="Dr. Firdaus" <?php if ($appointment['doctor_name'] === 'Dr. Firdaus') echo 'selected'; ?>>Dr. Firdaus</option>
                                </select>
                            </td>
                            <td><?php echo htmlspecialchars($appointment['status'] ?? 'Pending'); ?></td>
                            <td>
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                <button type="submit" class="save-button">Save</button>
                        </form>
                        <form method="POST" action="delete_appointment.php" onsubmit="return confirm('Are you sure to delete this record?');" style="display:inline;">
    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
    <button type="submit" name="delete">Delete</button>
</form>
                            </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</section>

<footer>
    <p>&copy; 2025 CANCER INFORMATION AND SUPPORT. BY, A24CS5031.</p>
    <div class="social-links">
        <a href="https://github.com/leecinsiak" target="_blank" aria-label="GitHub Profile">GitHub LINK</a>
    </div>
</footer>
<script src="../script.js"></script>
</body>
</html>
