<?php
$host = "localhost";
$username = "root";
$password = "";     
$dbname = "user_system";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// var_dump($_GET);
// exit;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM fare WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Fare deleted successfully.');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete fare.');
                window.location.href = 'index.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>
