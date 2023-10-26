<?php
    $message = ''; // Initialize the message variable
    $messageClass = ''; // Initialize the message class variable

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["allocate"])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user1_db";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $facultyName = $_POST["facultyName"];
        $day = $_POST["day"];
        $slot = $_POST["slot"];
        $date = $_POST["date"];

        // Check if the slot is empty for the faculty and slot
        $sql = "SELECT * FROM `{$facultyName}` WHERE `day` = ? AND `{$slot}` = ''";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $day);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = 'Slot available.';
            $messageClass = 'success';
        } else {
            $message = 'Slot not available.';
            $messageClass = 'error';
        }

        // Close the prepared statement and database connection
        $stmt->close();
        $conn->close();
    }
?>




<!DOCTYPE html>
<html>
<head>
    <title>CHECK SLOT AVAILABILITY</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        #message {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .success {
            color: #4CAF50;
        }

        .error {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>CHECK SLOT AVAILABILITY</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="facultyName">Faculty Name:</label>
            <input type="text" id="facultyName" name="facultyName" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required onchange="fillDay()" min="<?php echo date('Y-m-d'); ?>">

            <label for="day">Day:</label>
            <input type="text" id="day" name="day" readonly>

            <label for="slot">Slot:</label>
            <select id="slot" name="slot" required>
                <option value="slot1">Slot 1</option>
                <option value="slot2">Slot 2</option>
                <option value="slot3">Slot 3</option>
                <option value="slot4">Slot 4</option>
                <option value="slot5">Slot 5</option>
                <option value="slot6">Slot 6</option>
                <option value="slot7">Slot 7</option>
                <option value="slot8">Slot 8</option>
            </select>

            <input type="submit" name="allocate" value="Check Availability">
        </form>

        <div id="message" class="<?php echo $messageClass; ?>">
            <?php echo $message; ?>
        </div>
    </div>

    <script>
        function fillDay() {
            var dateInput = document.getElementById("date");
            var dayInput = document.getElementById("day");

            var selectedDate = new Date(dateInput.value);
            var options = { weekday: 'long' };
            var day = selectedDate.toLocaleDateString("en-US", options);

            dayInput.value = day;
        }
    </script>
</body>
</html>
