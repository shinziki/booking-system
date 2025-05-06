<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_system");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Query the database for the rider's data
    $query = "SELECT * FROM riders WHERE rider_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $name); // "s" means the name is a string
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result of the query

    if ($row = $result->fetch_assoc()) {
        // Check if the password is correct
        if (password_verify($password, $row['password'])) {
            // Store rider's id and name in session
            $_SESSION['rider_id'] = $row['id'];  // Store rider_id
            $_SESSION['rider_name'] = $row['name'];  // Store name if needed

            // Redirect to rider_dashboard.php
            header("Location: rider_dashboard.php");
            exit();
        } else {
            $error = "❌ Incorrect password!";
        }
    } else {
        $error = "❌ Rider not found!";
    }
}
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rider Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('images/logo.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-box {
      background: rgba(40, 40, 40, 0.95);
      border: 1px solid #cec8c8;
      padding: 30px;
      width: 400px;
      text-align: center;
      color: white;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
      margin-left: 880px;
      border-radius: 10px;
      transition: transform 0.3s ease;
    }

    .login-box:hover {
      transform: scale(1.01);
    }

    .login-box img {
      width: 100px;
    }

    h1 {
      font-size: 36px;
      margin-bottom: 20px;
    }

    label {
      display: block;
      text-align: left;
      margin: 15px 0 5px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 5px;
      transition: border 0.3s;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      border: 1px solid #0066ff;
      outline: none;
    }

    .show-password {
      text-align: left;
      margin-top: 5px;
    }

    button.login-btn {
      background-color: orange;
      color: white;
      padding: 10px 25px;
      font-size: 16px;
      border: none;
      margin-top: 20px;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    button.login-btn:hover {
      background-color: #e69500;
    }

    .account-links {
      margin-top: 20px;
      text-align: center;
    }

    .account-links p {
      margin: 10px 0;
      font-size: 14px;
    }

    .account-links a {
      display: inline-block;
      margin-top: 5px;
      padding: 8px 20px;
      text-decoration: none;
      border: 1px solid #ccc;
      border-radius: 5px;
      color: black;
      font-size: 14px;
      background-color: #f9f9f9;
      transition: all 0.3s ease;
    }

    .account-links a:hover {
      background-color: #e0e0e0;
      color: #333;
    }

    .back-btn {
      float: right;
      margin-top: -60px;
      background-color: white;
      border: 1px solid #ccc;
      color: black;
      padding: 5px 10px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .back-btn:hover {
      background-color: #0066ff;
      color: white;
    }

    .error-message {
      color: red;
      font-size: 14px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <img src="images/helmet.jpg" alt="Helmet">
    <a href="user-login.php" class="back-btn">Back</a>
    <h1>Riders</h1>

    <?php if (!empty($error)): ?>
      <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <div class="show-password">
        <input type="checkbox" onclick="togglePassword()"> Show
      </div>
      
      <button type="submit" class="login-btn">Login</button>
    </form>

    <div class="account-links">
      <p>Don't have an Account?</p>
      <a href="register_rider.php">🧑‍🎓 Click here!</a>
      
    </div>
  </div>

  <script>
    function togglePassword() {
      const pw = document.getElementById("password");
      pw.type = pw.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
