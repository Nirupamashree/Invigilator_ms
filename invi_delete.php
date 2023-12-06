<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $servername = "sqlserver43.mysql.database.azure.com";
    $username = "nirupamashree";
    $password = "laks@2003";
    $database = "user1_db";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $facultyName = $_POST["facultyName"];
        $date = $_POST["date"];
        $slot = $_POST["slot"];

        // Check if the record exists before deleting
        $checkSql = "SELECT * FROM `{$dbname}`.`allocate` WHERE `facultyname` = ? AND `date` = ? AND `slot` = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("sss", $facultyName, $date, $slot);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            echo '<div class="alert error">Record does not exist. Cannot delete.</div>';
        } else {
            // Delete the row from the allocate table
            $deleteSql = "DELETE FROM `{$dbname}`.`allocate` WHERE `facultyname` = ? AND `date` = ? AND `slot` = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("sss", $facultyName, $date, $slot);

            if ($deleteStmt->execute()) {
                echo '<div class="alert success">Row has been deleted successfully.</div>';
            } else {
                echo '<div class="alert error">Error deleting the row: ' . $conn->error . '</div>';
            }

            $deleteStmt->close();
        }

        $checkStmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Invigilation Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
        }

        form {
            width: 400px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .alert {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            /* Add font weight */
        }

        /* Add additional styles for the field names */
        label[for="facultyName"],
        label[for="date"],
        label[for="day"],
        label[for="slot"] {
            color: #333;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <h2>DELETE INVIGILATION SCHEDULE</h2>

        <label for="facultyName">Faculty Name:</label>
        <input type="text" name="facultyName" id="facultyName" required>

        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required>

        <label for="day">Day:</label>
        <input type="text" name="day" id="day" readonly>

        <label for="slot">Slot:</label>
        <select name="slot" id="slot">
            <option value="slot1">Slot 1</option>
            <option value="slot2">Slot 2</option>
            <option value="slot3">Slot 3</option>
            <option value="slot4">Slot 4</option>
            <option value="slot5">Slot 5</option>
            <option value="slot6">Slot 6</option>
            <option value="slot7">Slot 7</option>
            <option value="slot8">Slot 8</option>
        </select>

        <input type="submit" name="delete" value="Delete Slot">
    </form>

    <script>
        // Function to set the day based on the selected date
        function setDay() {
            var selectedDate = new Date(document.getElementById("date").value);
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var selectedDay = days[selectedDate.getDay()];
            document.getElementById("day").value = selectedDay;
        }

        // Call setDay() function when the date is changed
        document.getElementById("date").addEventListener("change", setDay);
    </script>
</body>
</html>
