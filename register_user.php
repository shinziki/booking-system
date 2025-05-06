<?php
$success = "";
$error = "";

// Only run when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // DB connection
  $host = "localhost";
  $username = "root";
  $password = "";
  $database = "user_system";

  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $name = $_POST['name'];
  $number = $_POST['number'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Check if email already exists
  $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $error = "Email already registered.";
  } else {
    $sql = "INSERT INTO users (name, number, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $number, $email, $password);

    if ($stmt->execute()) {
      $success = "Registration successful!";
    } else {
      $error = "Registration failed.";
    }

    $stmt->close();
  }

  $check->close();
  $conn->close();
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Signup</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: url('images/logo.png') no-repeat center center fixed;
      background-size: cover;
    }

    .signup-container {
      background: rgba(40, 40, 40, 0.95);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
      border: 1px solid #cec8c8;
      padding: 30px;
      width: 400px;
      text-align: center;
      color: white;
      margin-left: 880px;
      margin-top: 100px;
      border-radius: 10px;
      transition: transform 0.3s ease;
    }

    .signup-container:hover {
      transform: scale(1.01);
    }

    h2 {
      margin-bottom: 20px;
      font-size: 28px;
    }

    input[type="text"],
    input[type="number"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 5px;
      transition: border 0.3s ease;
    }

    input:focus {
      border: 1px solid #0066ff;
      outline: none;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: orange;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 15px;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #e69500;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .login-link a {
      color: orange;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .back-button {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .back-button a {
      background-color: white;
      color: black;
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 5px;
      font-weight: bold;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .back-button a:hover {
      background-color: #d3d3d3;
    }

    .message {
      margin-top: 10px;
      font-size: 14px;
      color: lightgreen;
    }

    .error {
      color: salmon;
    }
  </style>
</head>
<body>

  <div class="back-button">
    <a href="user-login.php">BACK</a>
  </div>

  <div class="signup-container">
    <h2>Sign Up</h2>
    
    <?php if ($success): ?>
      <div class="message"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="number" name="number" placeholder="Mobile Number" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>

    <div class="login-link">
      Already have an account? <a href="user-login.php">Login</a>
    </div>
  </div>

</body>
</html>
