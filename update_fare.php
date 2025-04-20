<?php
$host = "localhost";
$username = "root";
$password = "";     
$dbname = "user_system";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// var_dump($_POST);
// exit;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $from = $_POST['from_location'];
    $destination = $_POST['destination'];
    $fare = $_POST['fare'];
    $date = $_POST['fare_date'];

    // Validate inputs if necessary

    $sql = "UPDATE fare SET 
                from_location = ?, 
                destination = ?, 
                fare = ?, 
                fare_date = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $from, $destination, $fare, $date, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
        // echo "<script>
        //     alert('Fare updated successfully.');
        // </script>";
    } else {
        echo "<script>
            alert('Error updating fare: " . $conn->error . "');
            window.history.back();
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
