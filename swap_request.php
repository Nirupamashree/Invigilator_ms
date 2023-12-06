<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $servername = "sqlserver43.mysql.database.azure.com";
    $username = "nirupamashree";
    $password = "laks@2003";
    $dbname = "user1_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $facultyName = $_POST["facultyName"];
    $date = $_POST["date"];
    $day = date('l', strtotime($date));
    $slot = $_POST["slot"];
    $venue = $_POST["venue"];

    // Check if the allocation exists for the given date, day, slot, and venue
    $stmt = $conn->prepare("SELECT * FROM allocate WHERE date = ? AND day = ? AND slot = ? AND venue = ?");
    $stmt->bind_param("ssss", $date, $day, $slot, $venue);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($facultyName === $_SESSION['username']) {
        $errorMessage = "You cannot submit a swap request for yourself.";
    } else {
        // Check if the allocation exists for the given date, day, slot, and venue
        if ($result->num_rows === 0) {
            $errorMessage = "No invigilation schedule for the given date. You cannot swap with the selected faculty.";
        } else {
            // Prepare and execute the SQL statement to insert the swap request
            $stmt = $conn->prepare("INSERT INTO swap_request (request_from, request_to, date, day, slot, venue) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $_SESSION['username'], $facultyName, $date, $day, $slot, $venue);
            $stmt->execute();

            // Close the prepared statement and database connection
            $stmt->close();
            $conn->close();

            // Set the success message
            $successMessage = "Swap request updated successfully";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Swap Request Form</title>
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

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        select {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            background-color: #f2f2f2;
        }

        .alert.success {
            border-color: #a3cfa3;
            color: #155a15;
            background-color: #eaf7ea;
        }

        .alert.error {
            border-color: #f56e6e;
            color: #a70d0d;
            background-color: #f9dddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>SWAP REQUEST FORM</h2>

        <?php if (isset($successMessage)) : ?>
            <div class="alert success"><?php echo $successMessage; ?></div>
        <?php elseif (isset($errorMessage)) : ?>
            <div class="alert error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

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

            <label for="venue">Venue:</label>
            <select id="venue" name="venue" required>
                <option value="ab1">ab1</option>
                <option value="ab2">ab2</option>
                <option value="ab3">ab3</option>
            </select>

            <input type="submit" name="submit" value="Submit">
        </form>
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
