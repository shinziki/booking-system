<?php
session_start();
$currentUserId = $_SESSION['user_id'] ?? 1; // replace with actual session logic

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];
    $userId = $_POST['user_id'];

    $conn = new mysqli("localhost", "root", "", "your_database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
      background-color:rgb(60, 59, 59);
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
      padding: 10px;
      margin-bottom: 10px;
      background-color: #444;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
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

  <div class="header">
    <form method="post" action="logout.php">
      <button class="logout-btn" title="Logout">
        <img src="Image/Logout.png" alt="Logout" width="28" height="28">
      </button>
    </form>
  </div>

  <div class="container">
    <div class="sidebar">
      <form method="get" action="">
        <button name="view" value="book">Book</button>
        <button name="view" value="feedback">Feedback</button>
      </form>
    </div>

    <div class="main-content">
      <?php if (!empty($successMessage)) echo "<div class='message'>$successMessage</div>"; ?>
      <?php if (!empty($errorMessage)) echo "<div class='error'>$errorMessage</div>"; ?>

      <?php if ($_GET['view'] ?? 'feedback' === 'feedback'): ?>
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
      <?php else: ?>
        <div style="color: grey;">Booking section placeholder</div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
