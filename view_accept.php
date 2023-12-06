<!DOCTYPE html>
<html>
<head>
    <style>
             body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }

        .accept-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .accept-table th,
        .accept-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .accept-table thead {
            background-color: #f5f5f5;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .filter-container label {
            margin-right: 10px;
        }

        .filter-container input[type="text"] {
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
   
<div class="filter-container">

        <label for="filter">Search:</label>
        <input type="text" id="filter" name="filter" placeholder="Enter a keyword">
    </div>
<div class="container">

    <?php
    session_start();

    // Assuming you have already established a database connection
    $servername = "sqlserver43.mysql.database.azure.com";
    $username = "nirupamashree";
    $password = "laks@2003";
    $dbname = "user1_db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to retrieve data from the accept table based on the logged-in faculty name and search keyword
    function retrieveAcceptRecords($conn, $facultyName, $searchKeyword) {
        $sql = "SELECT * FROM accept WHERE request_from = '$facultyName' AND 
            (request_from LIKE '%$searchKeyword%' OR date LIKE '%$searchKeyword%' OR 
            day LIKE '%$searchKeyword%' OR slot LIKE '%$searchKeyword%' OR venue LIKE '%$searchKeyword%')";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
    
    // Function to display the accept table
    function displayAcceptRecords($acceptRecords) {
        echo '<table id="recordsTable" class="accept-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ACCEPTED BY</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Slot</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($acceptRecords as $record) {
            echo '<tr>
                <td>' . $record['id'] . '</td>
                <td>' . $record['request_to'] . '</td>
                <td>' . $record['date'] . '</td>
                <td>' . $record['day'] . '</td>
                <td>' . $record['slot'] . '</td>
                <td>' . $record['venue'] . '</td>
            </tr>';
        }

        echo '</tbody></table>';
    }

    // Check if the form is submitted and handle the search action
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['searchBtn'])) {
            // Retrieve the logged-in faculty name from the session
            $facultyName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

            // Get the search keyword from the form submission
            $searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';

            // Retrieve and display filtered accept records for the logged-in faculty
            $acceptRecords = retrieveAcceptRecords($conn, $facultyName, $searchKeyword);

            echo '<h1>VIEW SWAP ACCEPTED REQUESTS</h1>';
            displayAcceptRecords($acceptRecords);

            $conn->close();
            return;
        }
    }

    // Retrieve the logged-in faculty name from the session
    $facultyName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

    // Retrieve and display all accept records for the logged-in faculty initially
    $acceptRecords = retrieveAcceptRecords($conn, $facultyName, '');

    echo '<h2>VIEW SWAP ACCEPTED REQUESTS</h2>';
    displayAcceptRecords($acceptRecords);

    $conn->close();
    ?>



    <script>
        // Filter table rows based on the entered keyword
        document.getElementById("filter").addEventListener("keyup", function () {
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
</div>

</body>
</html>
