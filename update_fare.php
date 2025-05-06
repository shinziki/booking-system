<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "user_system";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? '';
    $fare = $_POST['fare'] ?? '';

    if (!empty($id) && !empty($fare)) {
        // Only update the fare column
        $stmt = $conn->prepare("UPDATE fare SET fare = ? WHERE id = ?");
        $stmt->bind_param("di", $fare, $id);
        
        if ($stmt->execute()) {
            // Redirect back to main dashboard page after successful update
            header("Location: index.php"); // change filename if needed
            exit;
        } else {
            echo "Failed to update fare.";
        }

        $stmt->close();
    } else {
        echo "Missing fare or ID.";
    }
}
$conn->close();
?>
