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

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        table thead {
            background-color: #f5f5f5;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>VIEW DENIED VIEW REQUEST</h2>
        <form method="POST">
            <input type="text" name="search" placeholder="Search">
            <button type="submit" name="searchBtn">Search</button>
        </form>

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

        // Function to retrieve data from the deny table based on the logged-in faculty name and search keyword
        function retrieveDenyRecords($conn, $facultyName, $searchKeyword) {
            $sql = "SELECT * FROM deny WHERE request_from = '$facultyName' AND 
                    (request_from LIKE '%$searchKeyword%' OR date LIKE '%$searchKeyword%' OR 
                    day LIKE '%$searchKeyword%' OR slot LIKE '%$searchKeyword%' OR venue LIKE '%$searchKeyword%')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return [];
        }

        // Function to display the deny table
        function displayDenyRecords($denyRecords) {
            echo '<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DENIED BY</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Slot</th>
                            <th>Venue</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($denyRecords as $record) {
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

                // Retrieve and display filtered deny records for the logged-in faculty
                $denyRecords = retrieveDenyRecords($conn, $facultyName, $searchKeyword);
                displayDenyRecords($denyRecords);

                $conn->close();
                return;
            }
        }

        // Retrieve the logged-in faculty name from the session
        $facultyName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

        // Retrieve and display all deny records for the logged-in faculty initially
        $denyRecords = retrieveDenyRecords($conn, $facultyName, '');
        displayDenyRecords($denyRecords);

        $conn->close();
        ?>
    </div>
</body>
</html>
