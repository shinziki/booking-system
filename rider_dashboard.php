<?php
session_start();

// Check if rider_id is set to verify the login
if (!isset($_SESSION['rider_id'])) {
    header("Location: rider-login.php");  // Redirect to login if not logged in
    exit();
}

$riderId = $_SESSION['rider_id'];  // Retrieve rider_id from session

// Database connection
$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get rider's name for display on the dashboard
$stmt = $conn->prepare("SELECT rider_name FROM riders WHERE id = ?");
$stmt->bind_param("i", $riderId);
$stmt->execute();
$stmt->bind_result($riderName);
$stmt->fetch();
$stmt->close();

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: rider-login.php");
    exit();
}

// Confirm booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $bookingId = $_POST['booking_id'];
    $stmt = $conn->prepare("UPDATE bookings SET rider_name = ?, status = 'active' WHERE id = ? AND rider_name IS NULL");
    $stmt->bind_param("si", $riderName, $bookingId);
    $stmt->execute();
    $stmt->close();
}

// Mark as arrived
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_arrived'])) {
    $bookingId = $_POST['booking_id'];
    $stmt = $conn->prepare("UPDATE bookings SET arrived_time = NOW(), status = 'arrived' WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rider Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: rgb(60, 59, 59);
            color: white;
        }
        header {
            background-color: #1a1a1a;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
        }
        header form {
            margin: 0;
        }
        header button {
            padding: 8px 15px;
            background-color: #b30000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #2c2c2c;
            height: calc(100vh - 80px);
            padding: 20px;
        }
        .sidebar form button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #444;
            color: white;
            border: none;
            cursor: pointer;
        }
        .sidebar form button:hover {
            background-color: #666;
        }
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #1a1a1a;
        }
        .card {
            background-color: #3a3a3a;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<header>
    <h1>Rider Dashboard</h1>
    <form method="post">
        <button name="logout" type="submit">Logout</button>
    </form>
</header>

<div class="container">
    <div class="sidebar">
        <form method="get">
            <button name="view" value="pending">Pending</button>
            <button name="view" value="confirmed">Confirmed</button>
            <button name="view" value="history">History</button>
        </form>
    </div>

    <div class="main-content">
        <?php
        $view = $_GET['view'] ?? 'pending';

        if ($view === 'pending') {
            echo "<h2>Pending Bookings</h2>";
            $sql = "SELECT b.id, u.id AS user_id, f.from_location, f.destination, f.fare, b.booked_at 
                    FROM bookings b
                    JOIN fare f ON b.fare_id = f.id
                    JOIN users u ON u.id = b.user_id
                    WHERE b.rider_name IS NULL AND b.status = 'active'";
        } elseif ($view === 'confirmed') {
            echo "<h2>Confirmed Bookings</h2>";
            $sql = "SELECT b.id, u.id AS user_id, f.from_location, f.destination, f.fare, b.booked_at 
                    FROM bookings b
                    JOIN fare f ON b.fare_id = f.id
                    JOIN users u ON u.id = b.user_id
                    WHERE b.rider_name = '$riderName' AND b.status = 'active'";
        } else {
            echo "<h2>Booking History</h2>";
            $sql = "SELECT b.id, u.id AS user_id, f.from_location, f.destination, f.fare, b.booked_at, b.arrived_time 
                    FROM bookings b
                    JOIN fare f ON b.fare_id = f.id
                    JOIN users u ON u.id = b.user_id
                    WHERE b.rider_name = '$riderName' AND b.status = 'arrived'";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
            <div class="card">
                <strong>User ID:</strong> <?php echo $row['user_id']; ?><br>
                <strong>From:</strong> <?php echo $row['from_location']; ?><br>
                <strong>To:</strong> <?php echo $row['destination']; ?><br>
                <strong>Fare:</strong> ₱<?php echo $row['fare']; ?><br>
                <strong>Booked At:</strong> <?php echo $row['booked_at']; ?><br>
                <?php if ($view === 'pending'): ?>
                    <form method="post">
                        <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="confirm_booking">Confirm</button>
                    </form>
                <?php elseif ($view === 'confirmed'): ?>
                    <form method="post">
                        <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="mark_arrived">Mark as Arrived</button>
                    </form>
                <?php elseif ($view === 'history'): ?>
                    <strong>Arrived Time:</strong> <?php echo $row['arrived_time']; ?><br>
                <?php endif; ?>
            </div>
        <?php endwhile;
        else:
            echo "<p style='color: gray;'>No data to show.</p>";
        endif;
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
