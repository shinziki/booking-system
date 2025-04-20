<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "user_system");  // Update with your actual database credentials
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['email'] = $user['email'];

      // Redirect to the dashboard
      header("Location: user-dashboard.php");
      exit();
    } else {
      $error_message = "Incorrect password!";
    }
  } else {
    $error_message = "User not found!";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('logo.png');
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
      background: rgba(40, 40, 40, 0.95); /* dark gray/black */
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
      border: 1px solid #cec8c8;
      padding: 30px;
      width: 400px;
      text-align: center;
      color: white;
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
    }

    .account-links a {
      margin: 0 5px;
      padding: 8px 15px;
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

    .admin-btn {
      float: right;
      margin-top: -80px;
      background-color: white;
      color: #04aa6d;
      border: 1px solid #ccc;
      padding: 5px 10px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .admin-btn:hover {
      background-color: #04aa6d;
      color: white;
    }

    .register-btn {
      background-color: #0066ff;
      color: white;
      padding: 8px 20px;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      margin-top: 10px;
      border: none;
      transition: background-color 0.3s ease;
    }

    .register-btn:hover {
      background-color: #004ccf;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <img src="helmet.jpg" alt="Helmet">
    <a href="AdminLog.html" class="admin-btn">👤 Admin</a>
    <h1>LOGIN</h1>

    <?php
    if (isset($error_message)) {
      echo "<div style='color: red;'>" . $error_message . "</div>";
    }
    ?>

    <form method="POST">
      <label for="email">Email:</label>
      <input type="text" id="email" name="email" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <div class="show-password">
        <input type="checkbox" onclick="togglePassword()"> Show
      </div>

      <button type="submit" class="login-btn">Login</button>
    </form>

    <div class="account-links">
      Don't have an Account?<br>
      <a href="register_user.php" class="register-btn">🧑‍🎓 Click here!</a>
      <a href="rider-login.html">👤 Rider</a>
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
