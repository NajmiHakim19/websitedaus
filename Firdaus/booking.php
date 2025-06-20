<?php
session_start();

require_once "../db_connect.php";
$firstname = $_SESSION['firstname'] ?? "Guest";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
        $prefill_phonenumber = htmlspecialchars($user['phonenumber']);
    }
}

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

    $sql = "INSERT INTO appointment_bookings (doctor, concern, date, time, salutation, fullname, icnumber, phone)
            VALUES ('$doctor', '$concern', '$date', '$time', '$salutation', '$fullname', '$icnumber', '$phone')";

    if ($conn->query($sql) === TRUE) {
        $submission_message = "Your appointment request has been received. Our team will contact you to confirm the date and time.";
    } else {
        $submission_message = "Error: " . $conn->error;
    }
}

function generateTimeSlots($start = "09:00", $end = "17:00", $interval = 60) {
    $slots = [];
    $startTime = strtotime($start);
    $endTime = strtotime($end);
    while ($startTime < $endTime) {
        $slots[] = date("H:i", $startTime);
        $startTime = strtotime("+{$interval} minutes", $startTime);
    }
    return $slots;
}
$allSlots = generateTimeSlots();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Appointment</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .time-slots-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .time-slot {
            padding: 10px 20px;
            border: 2px solid #007bff;
            border-radius: 30px;
            background-color: #fff;
            color: #007bff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .time-slot:hover {
            background-color: #007bff;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .time-slot.selected {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .time-slot.disabled {
            background-color: #e9ecef;
            color: #999;
            border-color: #ccc;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
<header class="nav-container">
    <div class="logo">Hi <?php echo htmlspecialchars($firstname); ?></div>
    <nav>
        <ul class="nav-links">
            <li><a href="../Ariff/logout.php">Logout</a></li>
            <li><a href="../Ariff/patient.php">Home</a></li>
            <li><a href="booking.php" class="active">Booking Appointment</a></li>
            <li><a href="mybooking.php">Your Appointment</a></li>
            <li><a href="../Nik/aboutForPatient.php">About us</a></li>
            <li><a href="../Nik/InformationHubForPatient.php">Information Hub</a></li>
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
            <select name="doctor" required>
                <option value="Dr. Firdaus">Dr. Firdaus</option>
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
            <label>Select Time Slot:</label>
            <div class="time-slots-container" id="timeSlots">
                <?php foreach ($allSlots as $slot): ?>
                    <button type="button" class="time-slot" data-time="<?php echo $slot; ?>"><?php echo date("h:i A", strtotime($slot)); ?></button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="time" id="selectedTime" required>
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
            <input type="text" name="phone" required value="<?php echo $prefill_phonenumber; ?>">
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
    <p>&copy; 2025 CANCER INFORMATION AND SUPPORT. BY, Group 2: TECHNO.</p>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const timeButtons = document.querySelectorAll(".time-slot");
    const selectedTimeInput = document.getElementById("selectedTime");

    timeButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            if (!btn.classList.contains("disabled")) {
                timeButtons.forEach(b => b.classList.remove("selected"));
                btn.classList.add("selected");
                selectedTimeInput.value = btn.getAttribute("data-time");
            }
        });
    });
});
</script>
</body>
</html>
