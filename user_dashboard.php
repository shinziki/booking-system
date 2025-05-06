<?php
session_start();

// Simulate login (remove this in real system)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Replace with actual login logic
}
$currentUserId = $_SESSION['user_id'];

// Handle feedback submission
$successMessage = $errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];
    $userId = $_POST['user_id'];

    // Connect to MariaDB
    $conn = new mysqli("localhost", "root", "", "user_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert feedback into feedbacks table
    $stmt = $conn->prepare("INSERT INTO feedbacks (user_id, feedback_text, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $userId, $feedback, $rating);

    if ($stmt->execute()) {
        $successMessage = "Feedback submitted successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: rgb(60, 59, 59);
      color: white;
    }

    .header {
      background-color: #333;
      padding: 10px;
      display: flex;
      justify-content: flex-end;
    }

    .logout-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: white;
    }

    .container {
      display: flex;
      background-color: whitesmoke;
    }

    .sidebar {
      width: 150px;
      background-color: #2c2c2c;
      padding: 20px;
      height: calc(100vh - 52px);
    }

    .sidebar button {
  width: 100%;
  padding: 15px; /* Increase padding for larger buttons */
  margin-bottom: 10px;
  background-color: #444;
  color: white;
  border: none;
  cursor: pointer;
  transition: background 0.3s;
  font-size: 18px; /* Increase font size for better readability */
}

.sidebar button:hover {
  background-color: #555;
}


    .main-content {
      flex: 1;
      padding: 30px;
      background-color: #1a1a1a;
    }

    .feedback-section {
      background-color: #2b2b2b;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
    }

    .feedback-section h2 {
      margin-bottom: 20px;
    }

    .feedback-section textarea {
      width: 100%;
      height: 100px;
      padding: 10px;
      border: none;
      border-radius: 5px;
      resize: none;
      background-color: #444;
      color: white;
    }

    .feedback-section select,
    .feedback-section button {
      margin-top: 10px;
      padding: 10px;
      background-color: #444;
      color: white;
      border: none;
      border-radius: 5px;
    }

    .feedback-section button:hover {
      background-color: #666;
    }

    .message {
      margin-bottom: 10px;
      color: #90ee90;
    }

    .error {
      margin-bottom: 10px;
      color: #ff6961;
    }
  </style>
</head>
<body>
<h1>User Dashboard</h1>
<div class="header">
  <form method="post" action="user-login.php">
    <button class="logout-btn" type="submit">Logout</button>
  </form>
</div>

<div class="container">
  <div class="sidebar">
    <form method="get" action="">
      <button name="view" value="book">Book</button>
      <button name="view" value="feedback_form">Feedback</button>
      <button name="view" value="feedback_list">My Feedbacks</button>
    </form>
  </div> <!-- End sidebar -->

  <div class="main-content">
    <?php if (!empty($successMessage)) echo "<div class='message'>$successMessage</div>"; ?>
    <?php if (!empty($errorMessage)) echo "<div class='error'>$errorMessage</div>"; ?>

    <?php
    $view = $_GET['view'] ?? 'feedback_form';

    if ($view === 'feedback_form'):
    ?>
      <div class="feedback-section">
        <h2>Enter your Suggestions or Concerns</h2>
        <form method="post">
          <textarea name="feedback" required></textarea><br>
          <label for="rating">Rate Us:</label>
          <select name="rating" id="rating">
            <option value="5">5</option>
            <option value="4">4</option>
            <option value="3">3</option>
            <option value="2">2</option>
            <option value="1">1</option>
          </select><br>
          <input type="hidden" name="user_id" value="<?php echo $currentUserId; ?>">
          <button type="submit" name="submit_feedback">Submit</button>
        </form>
      </div>

    <?php elseif ($view === 'feedback_list'):
      $conn = new mysqli("localhost", "root", "", "user_system");
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $feedbacks = [];
      $stmt = $conn->prepare("SELECT feedback_text, rating, submitted_at FROM feedbacks WHERE user_id = ? ORDER BY submitted_at DESC");
      $stmt->bind_param("i", $currentUserId);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
          $feedbacks[] = $row;
      }

      $stmt->close();
      $conn->close();
    ?>

      <?php if (!empty($feedbacks)): ?>
        <div style="margin-top: 30px;">
          <h3>Your Submitted Feedbacks</h3>
          <ul style="list-style: none; padding-left: 0;">
            <?php foreach ($feedbacks as $fb): ?>
              <li style="margin-bottom: 15px; background-color: #3a3a3a; padding: 10px; border-radius: 8px;">
                <strong>Rating:</strong> <?php echo $fb['rating']; ?>/5<br>
                <strong>Feedback:</strong> <?php echo htmlspecialchars($fb['feedback_text']); ?><br>
                <small><i><?php echo $fb['submitted_at']; ?></i></small>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php else: ?>
        <p style="color: grey; margin-top: 20px;">You haven't submitted any feedback yet.</p>
      <?php endif; ?>

      <?php else: ?>

<?php
$conn = new mysqli("localhost", "root", "", "user_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Handle booking cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
  $bookingId = $_POST['cancel_booking_id'];
  $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $bookingId, $currentUserId);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
      echo "<p style='color:lightgreen;'>Booking canceled successfully!</p>";
  } else {
      echo "<p style='color:red;'>Error: Booking cancellation failed.</p>";
  }

  $stmt->close();
}

// Handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_now'])) {
    $fareId = $_POST['fare_id'];
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, fare_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $currentUserId, $fareId);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:lightgreen;'>Booking successful!</p>";
}



// Fetch fares for booking form
$fareResult = $conn->query("SELECT * FROM fare ORDER BY fare_date DESC");
?>

<h2>Book a Ride</h2>
<form method="post">
  <label for="fare_id">Select Fare:</label>
  <select name="fare_id" required>
    <?php while ($fare = $fareResult->fetch_assoc()): ?>
      <option value="<?php echo $fare['id']; ?>">
        <?php echo $fare['from_location'] . " ➜ " . $fare['destination'] . " - ₱" . $fare['fare']; ?>
      </option>
    <?php endwhile; ?>
  </select>
  <button type="submit" name="book_now">Book Now</button>
</form>

<hr>
<h3>Your Booking History</h3>
<?php
$stmt = $conn->prepare("
SELECT b.id, f.from_location, f.destination, f.fare, b.booked_at, b.rider_name, b.arrived_time
FROM bookings b
JOIN fare f ON b.fare_id = f.id
WHERE b.user_id = ?
ORDER BY b.booked_at DESC
");

$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
?>
  <ul style="list-style: none; padding-left: 0;">
    <?php while ($row = $result->fetch_assoc()): ?>
      <li style="margin-bottom: 15px; background-color: #3a3a3a; padding: 10px; border-radius: 8px;">
        <strong>From:</strong> <?php echo $row['from_location']; ?><br>
        <strong>To:</strong> <?php echo $row['destination']; ?><br>
        <strong>Fare:</strong> ₱<?php echo $row['fare']; ?><br>
        <strong>Booked At:</strong> <?php echo $row['booked_at']; ?><br>
        <strong>Rider:</strong> <?php echo $row['rider_name'] ?? 'Pending'; ?><br>
        <strong>Arrival Time:</strong> <?php echo $row['arrived_time'] ?? 'Not arrived'; ?><br>



        <!-- Add cancel booking button -->
        <form method="post" style="margin-top:5px;">
          <input type="hidden" name="cancel_booking_id" value="<?php echo $row['id']; ?>">
          <button type="submit" name="cancel_booking">Cancel Booking</button>
        </form>
      </li>
    <?php endwhile; ?>
  </ul>
<?php else: ?>
  <p style="color: grey;">You have no bookings yet.</p>
<?php endif;

$stmt->close();
$conn->close();
?>

<?php endif; ?>
 
  </div> <!-- End main content -->
</div> <!-- End container -->

</body>
</html>
