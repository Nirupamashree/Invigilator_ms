<?php
// MySQL server configuration
$servername = "sqlserver43.mysql.database.azure.com";
$username = "nirupamashree";
$password = "laks@2003";
$database = "user1_db";

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the swap_lab table
$query = "SELECT * FROM swap_lab";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      padding: 8px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tbody tr:hover {
      background-color: #eaeaea;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>VIEW SWAP REQUEST (LAB)</h2>
    <table>
      <thead>
        <tr>
          <th>REQUEST FROM</th>
          <th>Course</th>
          <th>Date</th>
          <th>Day</th>
          <th>Slot</th>
          <th>Venue</th>
          <th>MY NAME</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Loop through each row and display the data in the table
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['request_from'] . "</td>";
            echo "<td>" . $row['course'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['day'] . "</td>";
            echo "<td>" . $row['slot'] . "</td>";
            echo "<td>" . $row['venue'] . "</td>";
            echo "<td>" . $row['request_to'] . "</td>";
            echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
