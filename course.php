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
            justify-content: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            padding: 10px;
            border-radius: 4px;
            margin-left: 10px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .result-message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>VIEW COURSE HANDLING FACULTY</h2>
        <form method="POST" style="max-width: 800px;">
            <label for="course_code">Course Code:</label>
            <input type="text" id="course_code" name="course_code" required>
            <button type="submit" name="submit">Submit</button>
        </form>

        <?php
        // Assuming you have already established a database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user1_db";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $tableHTML = ''; // Variable to store the HTML table

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseCode = $_POST['course_code'];

            // Prepare and execute the query to retrieve records based on the course code
            $stmt = $conn->prepare("SELECT * FROM course WHERE code = ?");
            $stmt->bind_param("s", $courseCode);
            $stmt->execute();
            $result = $stmt->get_result();

            // Generate the HTML table if records are found
            if ($result->num_rows > 0) {
                $tableHTML .= '<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Faculty Name</th>
                                <th>Course Code</th>
                                <th>Course Name</th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = $result->fetch_assoc()) {
                    $tableHTML .= '<tr>
                            <td>' . $row['id'] . '</td>
                            <td>' . $row['facultyName'] . '</td>
                            <td>' . $row['code'] . '</td>
                            <td>' . $row['courseName'] . '</td>
                        </tr>';
                }

                $tableHTML .= '</tbody></table>';
            } else {
                $tableHTML = '<p class="result-message">No records found.</p>';
            }

            $stmt->close();
        }

        $conn->close();
        ?>

        <!-- Display the generated table -->
        <?php echo $tableHTML; ?>
    </div>
</body>
</html>
