<?php
session_start();
echo "<pre>Logged in as: " . $_SESSION['username'] . "</pre>";
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "Guest";

// Check if doctor is logged in
if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'doctor') {
    header("Location: ../Ariff/login.php");
    exit();
}

// Assuming doctor_name in DB is stored as: "Dr. Firdaus", etc.
$doctorUsername = $_SESSION['username'];
$doctorNameMap = [
    'daus'   => 'Dr. Firdaus'
];
$doctorName = $doctorNameMap[$doctorUsername] ?? '';

// Database connection
require_once "../db_connect.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch appointments assigned to this doctor
$sql = "
    SELECT ab.id, ab.fullname, ab.icnumber, ab.date, ab.time, ab.concern, ad.assigned_at, ad.status
    FROM assign_doctor ad
    JOIN appointment_bookings ab ON ad.appointment_id = ab.id
    WHERE ad.doctor_name = ?
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
    <title>Doctor Dashboard</title>
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
        <div class="logo">Hi <?php echo htmlspecialchars($doctorName) ?></div>
        <nav>
            <ul class="nav-links">
                <li><a href="../Ariff/logout.php">Logout</a></li>
                

        
            </ul>
            <div class="hamburger">☰</div>
        </nav>
    </header>

    <section id="home" class="hero">
        <h1>Doctor Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($doctorName); ?>. Below are your assigned appointments.</p>

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
                        <th>Phone No</th>
                        <th>Status</th>
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
                            <?php
                             $phone = '';
                             $conn2 = new mysqli("localhost", "root", "", "canceriinfoandsupport");
                             if (!$conn2->connect_error) {
                             $stmt2 = $conn2->prepare("SELECT phone FROM appointment_bookings WHERE id = ?");
                             $stmt2->bind_param("i", $app['id']);
                             $stmt2->execute();
                             $stmt2->bind_result($fetchedPhone);
                             if ($stmt2->fetch()) {
                              $phone = $fetchedPhone;
                                                      }
                                     $stmt2->close();
                                     $conn2->close();
                                                                            }
?>
<td><?php echo htmlspecialchars($phone ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($app['status']); ?></td>
                            <td>
                                <form method="POST" action="update_status.php" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?php echo $app['id']; ?>">
                                    <select name="status">
                                        <option value="Pending">Pending</option>
                                        <option value="Done">Done</option>
                                    </select>
                                    <button type="submit" name="update">Update</button>
                                </form>
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments assigned to you.</p>
        <?php endif; ?>
    </section>

    <footer>
    <p>&copy; 2025 CANCER INFORMATION AND SUPPORT. BY, Group 2: TECHNO.</p>
    </footer>
    <script src="../script.js"></script>
</body>
</html>
