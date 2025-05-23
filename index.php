<?php
$host = "localhost";
$username = "root";
$password = "";     
$dbname = "user_system";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $from = $_POST['from'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $fare = $_POST['fare'] ?? '';
  
    if (!empty($from) && !empty($destination) && !empty($fare)) {
        $stmt = $conn->prepare("INSERT INTO fare (from_location, destination, fare, fare_date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ssd", $from, $destination, $fare);        
        $result = $stmt->execute();
        
        $stmt->close();
        
        // Redirect to the same page to prevent form resubmission on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css?v=1.3"> 
</head>
<body>

  <div class="topbar">
    <h1>Admin Dashboard</h1>
  </div>

  <div class="container">
    <div class="sidebar">
      <button class="nav-btn" onclick="showSection('transaction')">🧾 Transaction</button>
      <button class="nav-btn" onclick="showSection('customer')">👤 Customer</button>
      <button class="nav-btn" onclick="showSection('riders')">🏍️ Riders</button>
      <button class="nav-btn" onclick="showSection('fare')">💰 Fare</button>
      <button class="nav-btn" onclick="showSection('feedback')">📦 Feedback</button>

      <!-- Back and Logout Buttons -->
      <a href="Adminlog.html" class="nav-btn">➡️ Logout</a>
    </div>

    <div class="main">
      <!-- TRANSACTION -->
      <div id="transaction" class="transaction-table-container hidden">

        <h2>Transactions</h2>
        <table>
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>User ID</th>
              <th>Rider ID</th>
              <th>From</th>
              <th>To</th>
              <th>Fare (₱)</th>
              <th>Confirmed At</th>
              <th>Arrived</th>
              <th>Reached</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>BK123</td>
              <td>U001</td>
              <td>R002</td>
              <td>SM City</td>
              <td>Ayala Center</td>
              <td>120</td>
              <td>2025-04-12 08:35</td>
              <td>2025-04-12 08:55</td>
              <td>2025-04-12 09:00</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- CUSTOMER -->
      <div id="customer" class="table-container ">
        <h2>Customer</h2>
        <button class="refresh-btn" onclick="refreshTable('customer')">REFRESH</button>
        <table>
          <thead>
            <tr>
              <th>NAME</th>
              <th>NUMBER</th>
              <th>EMAIL</th>
              <th>PASSWORD</th>
            </tr>
          </thead>
          <tbody>
  <?php
    $sql = "SELECT name, number, email, password FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['password']) . "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='4'>No customers found</td></tr>";
    }
  ?>
</tbody>
        </table>
      </div>
<!-- RIDERS -->
<div id="riders" class="table-container hidden">
  <h2>Riders</h2>
  <button class="refresh-btn" onclick="refreshTable('riders')">REFRESH</button>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Plate Number</th>
        <th>Contact</th>
        <th>Address</th>
        <th>License Number</th>
        <th>Password</th>
        <th>Registration Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $sql = "SELECT rider_name, plate_number, contact, address, license_number, password, registration_date FROM riders";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['rider_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['plate_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['license_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
            echo "<td>" . htmlspecialchars($row['registration_date']) . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7'>No riders found</td></tr>";
        }
      ?>
    </tbody>
  </table>
</div>

      <!-- FARE -->
      <div id="fare" class="table-container hidden">
        <h2>Fare</h2>
        <button class="refresh-btn" onclick="refreshTable('fare')">REFRESH</button>

        <form id="fareForm" method="POST">
          <div>
            <label for="from">From:</label>
            <input type="text" id="from" name="from" required>
          </div>
          <div>
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required>
          </div>
          <div>
            <label for="fare">Fare (₱):</label>
            <input type="number" id="fare" name="fare" required>
          </div>
          <div>
            <button type="submit">Add Fare</button>
          </div>
        </form>

        <br>
        <div style="margin-top: 15px;">
          <input type="text" id="fareSearchInput" placeholder="Search Fare...">
          <button onclick="searchFare()">Search</button>
        </div>
        <br>
        <table id="fareTable">
          <thead>
            <tr>
              <th>No. ID</th>
              <th>From</th>
              <th>Destination</th>
              <th>Fare (₱)</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
    <?php
        $sql = "SELECT * FROM fare";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['from_location']) . "</td>";
                echo "<td>" . htmlspecialchars($row['destination']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fare']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fare_date']) . "</td>";
                echo "<td>
                  <button onclick=\"editFare('". $row['id'] . "', '" . addslashes($row['from_location']) . "', '". addslashes($row['destination']) . "', '" . $row['fare'] . "', '" . $row['fare_date'] . "')\">Edit</button> 
                  <button onclick=\"deleteFare('".$row['id']."')\">Delete</button>
                  </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No fares found</td></tr>";
        }
    ?>
        </tbody>
        </table>
      </div>
<!-- FEEDBACK -->
<div id="feedback" class="table-container hidden">
  <h2>Feedback</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Feedback</th>
        <th>Rating</th>
        <th>Submitted At</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $mysqli = new mysqli("localhost", "root", "", "user_system");
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM feedbacks ORDER BY submitted_at DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['user_id']}</td>
              <td>{$row['feedback_text']}</td>
              <td>{$row['rating']}</td>
              <td>{$row['submitted_at']}</td>
            </tr>";
        }
      ?>
    </tbody>
  </table>
</div>

<div id="editPopup">
      <div id="editPopupBox">
        <h2>Edit Fare</h2>
        <form id="editForm" method="post" action="update_fare.php">
          <input type="hidden" name="id" id="editId">
          <div>
            <label>From:</label>
            <input type="text" name="from_location" id="editFrom">
          </div>
          <div>
            <label>Destination:</label>
            <input type="text" name="destination" id="editDestination">
          </div>
          <div>
            <label>Fare:</label>
            <input type="number" name="fare" id="editFareAmount">
          </div>
          <div>
            <label>Date:</label>
            <input type="date" name="fare_date" id="editFareDate">
          </div>
          <div style="text-align: right; margin-top: 10px;">
            <button type="button" onclick="closeEditPopup()">Cancel</button>
            <button type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function showSection(sectionId) {
      const sections = document.querySelectorAll('.main > div');
      sections.forEach(section => section.classList.add('hidden'));

      const section = document.getElementById(sectionId);
      if (section) section.classList.remove('hidden');
    }

    function refreshTable(tableId) {
      const table = document.getElementById(tableId + 'Table');
      if (table) {
        table.querySelector('tbody').innerHTML = '<tr><td colspan="5">Loading...</td></tr>';
        setTimeout(() => {
          // You can fetch new data here if needed
          table.querySelector('tbody').innerHTML = '<tr><td colspan="5">Data refreshed</td></tr>';
        }, 1500);
      }
    }

    function searchFare() {
      const input = document.getElementById('fareSearchInput');
      const filter = input.value.toUpperCase();
      const table = document.getElementById('fareTable');
      const tr = table.getElementsByTagName('tr');
      
      for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[0]; // Searching for 'From' column
        if (td) {
          const textValue = td.textContent || td.innerText;
          if (textValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }

    // function editFare() {
    //   alert("Edit fare functionality");
    // }

    function deleteFare(id) {
      let result = confirm("Are you sure to delete this fare?");

      if (result) {
        window.location.href = "delete_fare.php?id=" + id;
      }
    }
  </script>
  
  <script src="editPopUp.js"></script>

</body>
</html>