<!DOCTYPE html>
<html>
<head>
    <title>CREATE INVIGILATION SCHEDULE</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* CSS styles omitted for brevity */
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

        select,
        input[type="text"],
        input[type="date"] {
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
        label[for="firstInput"],
        label[for="secondInput"],
        label[for="facultyName"],
        label[for="date"],
        label[for="day"],
        label[for="slot"],
        label[for="venue"] {
            color: #333;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["allocate"])) {
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
        $day = $_POST["day"];
        $slot = $_POST["slot"];
        $venue = $_POST["venue"];
        $date = $_POST["date"];

        // Check if the faculty is already allocated to the selected slot
        $sql = "SELECT * FROM `allocate` WHERE `facultyname` = ? AND `day` = ? AND `slot` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $facultyName, $day, $slot);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="alert error">Faculty is already allocated to this slot.</div>';
            exit; // Stop further execution of the code
        }

        // Check if the slot is empty for the faculty and slot
        $sql = "SELECT * FROM `{$facultyName}` WHERE `day` = ? AND `{$slot}` = ''";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $day);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Store the data in the allocate table
            $sql = "INSERT INTO `allocate` (`facultyname`, `semester`, `course`, `date`, `day`, `slot`, `venue`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $facultyName, $semester, $course, $date, $day, $slot, $venue);

            // Set the values for semester and course based on the selected options
            $semester = $_POST["firstInput"];
            $course = $_POST["secondInput"];

            if ($stmt->execute()) {
                echo '<div class="alert success">Slot has been allocated successfully.</div>';
            } else {
                echo '<div class="alert error">Error allocating the slot: ' . $conn->error . '</div>';
            }
        } else {
            echo '<div class="alert error">Slot not available.</div>';
        }

        // Close the prepared statement and database connection
        $stmt->close();
        $conn->close();
    }
    ?>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <h2>CREATE INVIGILATION SCHEDULE</h2>

        <label for="firstInput">Semester:</label>
        <select name="firstInput" id="firstInput">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
        </select>

        <label for="secondInput">Course:</label>
        <select name="secondInput" id="secondInput"></select>

        <label for="facultyName">Faculty Name:</label>
        <input type="text" name="facultyName" id="facultyName" required>

        <label for="date">Date:</label>
        <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" required>

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

        <label for="venue">Venue:</label>
        <select name="venue" id="venue">
            <option value="ab1">ab1</option>
            <option value="ab2">ab2</option>
            <option value="ab3">ab3</option>
        </select>

        <input type="submit" name="allocate" value="Allocate Slot">
    </form>

    <script>
        $(document).ready(function() {
            var options = {
                "1": ["Option 1.1", "Option 1.2", "Option 1.3"],
                "2": ["Option 2.1", "Option 2.2"],
                "3": ["Option 3.1", "Option 3.2", "Option 3.3", "Option 3.4"],
                "4": ["Option 4.1", "Option 4.2"],
                "5": ["Option 5.1"],
                "6": ["Option 6.1", "Option 6.2", "Option 6.3", "Option 6.4", "Option 6.5"],
                "7": ["Option 7.1", "Option 7.2", "Option 7.3"]
            };

            $("#firstInput").change(function() {
                var selectedValue = $(this).val();
                var secondInput = $("#secondInput");

                secondInput.empty();

                if (options.hasOwnProperty(selectedValue)) {
                    var optionList = options[selectedValue];
                    for (var i = 0; i < optionList.length; i++) {
                        var option = $("<option>").val(optionList[i]).text(optionList[i]);
                        secondInput.append(option);
                    }
                }
            });

            $("#date").change(function() {
                var selectedDate = new Date($(this).val());
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var selectedDay = days[selectedDate.getDay()];
                $("#day").val(selectedDay);
            });
        });
    </script>
</body>
</html>
