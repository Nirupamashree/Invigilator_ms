<?php
session_start(); // Start the session

$servername = "sqlserver43.mysql.database.azure.com";
$username = "nirupamashree";
$password = "password@123";
$dbname = "user1_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the accept table
$sql = "SELECT * FROM accept";
$result = $conn->query($sql);

// Initialize data array for table and PDF
$data = array();
$data[] = array("Faculty Name(REQUESTED)", "Faculty Name(ACCEPTED)", "Date", "Day", "Slot", "Venue");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            $row["request_to"],
            $row["request_from"],
            $row["date"],
            $row["day"],
            $row["slot"],
            $row["venue"]
        );
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accepted Requests</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .filter-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .download-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Accepted Requests</h2>

        <table>
            <thead>
                <tr>
                    <th>Faculty Name(REQUESTED)</th>
                    <th>Faculty Name(ACCEPTED)</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Slot</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data as $row) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>$cell</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <a class="download-button" href="generate_pdf.php" target="_blank">Download PDF</a>
    </div>

    <script>
        function filterTable() {
            // Implement your filtering logic here
            alert("Filtering table");
        }
    </script>
</body>
</html>
