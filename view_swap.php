<?php
session_start();

// Assuming you have already established a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user1_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve data from the swap_request table based on the logged-in faculty name
function retrieveSwapRequests($conn, $facultyName) {
    $sql = "SELECT * FROM swap_request WHERE request_to = '$facultyName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

// Function to display the swap request table
function displaySwapRequests($swapRequests) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }

            h2 {
                text-align: center;
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            th, td {
                padding: 12px 15px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f5f5f5;
                font-weight: bold;
            }

            td input[type="checkbox"] {
                margin-right: 5px;
            }

            .action-buttons {
                text-align: center;
                margin-top: 20px;
            }

            .filter-container {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 20px;
            }
    
            .filter-container input[type="text"] {
                text-align: center;
                padding: 10px;
                margin-left: 10px;
                border-radius: 4px;
            }
            
            .action-buttons button {
                padding: 10px 20px;
                background-color: #4CAF50;
                color: #fff;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                margin-right: 10px;
            }

            .action-buttons button:last-child {
                background-color: #f44336;
            }
        </style>
    </head>
    <body>
        <div class="container">
        <h2>VIEW SWAP REQUEST</h2>

            <div class="filter-container">
            <label for="filter">Search:</label>
            <input type="text" id="filter" name="filter" placeholder="Enter a keyword">
            </div>

            <form method="POST">
                <table id="recordsTable">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>ID</th>
                            <th>MY USER NAME</th>
                            <th>Request From</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Slot</th>
                            <th>Venue</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($swapRequests as $request) {
        echo '<tr>
                <td><input type="checkbox" name="action[]" value="' . $request['id'] . '"></td>
                <td>' . $request['id'] . '</td>
                <td>' . $request['request_to'] . '</td>
                <td>' . $request['request_from'] . '</td>
                <td>' . $request['date'] . '</td>
                <td>' . $request['day'] . '</td>
                <td>' . $request['slot'] . '</td>
                <td>' . $request['venue'] . '</td>
            </tr>';
    }

    echo '</tbody></table>
            <div class="action-buttons">
                <button type="submit" name="accept">Accept</button>
                <button type="submit" name="deny">Deny</button>
            </div>
            </form>
        </div>
    </body>
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
    </html>';
}

// Check if the form is submitted and handle the accept and deny actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accept'])) {
        $selectedRequests = isset($_POST['action']) ? $_POST['action'] : [];

        // Process accept action for selected requests
        foreach ($selectedRequests as $requestId) {
            $selectSql = "SELECT * FROM swap_request WHERE id = $requestId";
            $result = $conn->query($selectSql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Insert the selected row into the accept table
                $acceptTo = $row['request_to'];
                $acceptFrom = $row['request_from'];
                $acceptDate = $row['date'];
                $acceptDay = $row['day'];
                $acceptSlot = $row['slot'];
                $acceptVenue = $row['venue'];

                $insertSql = "INSERT INTO accept (request_to, request_from, date, day, slot, venue)
                              VALUES ('$acceptTo', '$acceptFrom', '$acceptDate', '$acceptDay', '$acceptSlot', '$acceptVenue')";
                $conn->query($insertSql);

                // Delete the selected row from the swap_request table
                $deleteSql = "DELETE FROM swap_request WHERE id = $requestId";
                $conn->query($deleteSql);

                // Delete the selected row from the allocate table
                $deleteSql = "DELETE FROM allocate WHERE facultyName = ? AND date = ? AND day = ? AND slot = ?";
                $stmt = $conn->prepare($deleteSql);
                $stmt->bind_param("ssss", $acceptFrom, $acceptDate, $acceptDay, $acceptSlot);
                $stmt->execute();
                $stmt->close();

            }
        }
    } elseif (isset($_POST['deny'])) {
        $selectedRequests = isset($_POST['action']) ? $_POST['action'] : [];

        // Process deny action for selected requests
        foreach ($selectedRequests as $requestId) {
            $selectSql = "SELECT * FROM swap_request WHERE id = $requestId";
            $result = $conn->query($selectSql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Insert the selected row into the deny table
                $denyTo = $row['request_to'];
                $denyFrom = $row['request_from'];
                $denyDate = $row['date'];
                $denyDay = $row['day'];
                $denySlot = $row['slot'];
                $denyVenue = $row['venue'];

                $insertSql = "INSERT INTO deny (request_to, request_from, date, day, slot, venue)
                              VALUES ('$denyTo', '$denyFrom', '$denyDate', '$denyDay', '$denySlot', '$denyVenue')";
                $conn->query($insertSql);

                // Delete the selected row from the swap_request table
                $deleteSql = "DELETE FROM swap_request WHERE id = $requestId";
                $conn->query($deleteSql);
            }
        }
    }
}

// Retrieve the logged-in faculty name from the session
$facultyName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Retrieve and display swap requests for the logged-in faculty
$swapRequests = retrieveSwapRequests($conn, $facultyName);
displaySwapRequests($swapRequests);

$conn->close();
?>
