<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to the login page if not logged in
    exit();
}

// Get the faculty name from the session
$facultyName = $_SESSION['username'];

// MySQL server configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "user1_db";

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the schedule for the faculty from the 'allocate' table
$query = "SELECT * FROM allocate WHERE facultyName = '$facultyName'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: white; 
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .table-container {
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }


        

        .filter-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .filter-container input[type="text"] {
            padding: 10px;
            margin-left: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h2>Schedule for <?php echo $facultyName; ?></h2>

<div class="filter-container">
        <label for="filter">Search:</label>
        <input type="text" id="filter" name="filter" placeholder="Enter a keyword">
    </div>

    <div class="table-container" >

        <table id="recordsTable">
            <tr>
                <th>Faculty Name</th>
                <th>Semester</th>
                <th>Course</th>
                <th>Date</th>
                <th>Day</th>
                <th>Slot</th>
                <th>Venue</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['facultyName']; ?></td>
                    <td><?php echo $row['semester']; ?></td>
                    <td><?php echo $row['course']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['day']; ?></td>
                    <td><?php echo $row['slot']; ?></td>
                    <td><?php echo $row['venue']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script>
        // Filter table rows based on the entered keyword
        // Filter table rows based on the entered keyword
        document.getElementById("filter").addEventListener("keyup", function() {
            var keyword = this.value.toLowerCase();
            var table = document.getElementById("recordsTable");
            var rows = table.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var found = false;

                for (var j = 0; j < cells.length; j++) {
                    var cellText = cells[j].textContent || cells[j].innerText;

                    if (cellText.toLowerCase().indexOf(keyword) > -1) {
                        found = true;
                        break;
                    }
                }

                if (found) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        });
    </script>
</body>
</html>
