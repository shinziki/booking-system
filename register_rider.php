<?php
// Start session
session_start();

// Database connection (update with your database credentials)
$conn = new mysqli("localhost", "root", "", "user_system");  // Replace with your actual database credentials

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Collect form data
  $name = $_POST['name'];
  $plate_number = $_POST['plate_number'];
  $contact = $_POST['contact'];
  $address = $_POST['address'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Encrypt the password
  $license_number = $_POST['license_number'];

  // Prepare and bind the SQL statement
  $stmt = $conn->prepare("INSERT INTO riders (name, plate_number, contact, address, password, license_number) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssss", $name, $plate_number, $contact, $address, $password, $license_number);

  // Execute the query and check if it was successful
  if ($stmt->execute()) {
    // Redirect after successful registration
    echo "<script>alert('Rider registered successfully!'); window.location.href = 'user-login.html';</script>";
  } else {
    // Show error message if the query failed
    echo "<script>alert('Error in registration. Please try again.');</script>";
  }

  // Close the statement and the database connection
  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rider Registration</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: url('logo.png') no-repeat center center fixed;
      background-size: cover;
    }

    .form-container {
      max-width: 800px;
      margin: 100px auto;
      background: rgba(40, 40, 40, 0.9); /* transparent dark */
      border-radius: 10px;
      padding: 40px;
      color: white;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
    }

    h2 {
      text-align: center;
      font-size: 32px;
      margin-bottom: 30px;
    }

    form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px 30px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      font-size: 14px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .full-width {
      grid-column: 1 / 3;
    }

    .form-buttons {
      grid-column: 1 / 3;
      text-align: right;
    }

    button {
      padding: 10px 20px;
      background-color: white;
      color: black;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #d3d3d3;
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
  </style>
</head>
<body>

  <div class="back-button">
    <a href="user-login.html">BACK</a>
  </div>

  <div class="form-container">
    <h2>Personal Info</h2>
    <form action="register_rider.php" method="POST">
      <div>
        <label>Complete Name:</label>
        <input type="text" name="name" required>
      </div>
      <div>
        <label>Plate Number:</label>
        <input type="text" name="plate_number" required>
      </div>

      <div>
        <label>Contact:</label>
        <input type="text" name="contact" required>
      </div>
      <div>
        <label>Address:</label>
        <input type="text" name="address" required>
      </div>

      <div>
        <label>Password:</label>
        <input type="password" name="password" required>
      </div>
      <div>
        <label>Licence Number:</label>
        <input type="text" name="license_number" required>
      </div>

      <div class="form-buttons">
        <button type="submit">Register</button>
      </div>
    </form>
  </div>

</body>
</html>
