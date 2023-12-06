<?php
// MySQL server configuration
$servername = "sqlserver43.mysql.database.azure.com";
$username = "nirupamashree";
$password = "laks@2003";
$database = "user1_db";
// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start the PHP session

$successMessage = '';
$errorMessage = '';

if (isset($_POST['submit'])) {
    $facultyName = mysqli_real_escape_string($conn, $_POST['facultyName']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $date = $_POST['date'];
    $day = date("l", strtotime($date));
    $slot = $_POST['slot'];
    $venue = $_POST['venue'];
    $requestFrom = $_SESSION['username'];

    if ($facultyName === $requestFrom) {
        $errorMessage = "Error: You cannot swap with yourself.";
    } else {
        // Check if facultyName and course belong to the same row in the course table
        $query = "SELECT * FROM course WHERE facultyName='$facultyName' AND courseName='$course'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            // Insert the values into the swap_lab table
            $insertQuery = "INSERT INTO swap_lab (request_to, course, date, day, slot, venue, request_from) VALUES ('$facultyName', '$course', '$date', '$day', '$slot', '$venue', '$requestFrom')";
            if (mysqli_query($conn, $insertQuery)) {
                $successMessage = "Data inserted successfully.";
            } else {
                $errorMessage = "Error: " . mysqli_error($conn);
            }
        } else {
            $errorMessage = "Error: Faculty name and course do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      width: 400px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .input-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="date"],
    select {
      width: 95%;
      padding: 4px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    input[type="text"][readonly] {
      background-color: #f2f2f2;
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

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    input[type="submit"]:focus {
      outline: none;
    }

    .alert {
      margin-top: 10px;
      padding: 10px;
      border-radius: 3px;
      color: #fff;
      font-weight: bold;
    }

    .success {
      background-color: #4CAF50;
    }

    .error {
      background-color: #f44336;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>SWAPPING FOR LAB</h2>
    <?php if (!empty($successMessage)): ?>
      <div class="alert success"><?php echo $successMessage; ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
      <div class="alert error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
      <div class="input-group">
        <label for="facultyName">Faculty Name:</label>
        <input type="text" id="facultyName" name="facultyName" placeholder="Enter faculty name" required>
      </div>
      <div class="input-group">
        <label for="course">Course:</label>
        <input type="text" id="course" name="course" placeholder="Enter course" required>
      </div>
      <div class="input-group">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" onchange="updateDayField()" required>
      </div>
      <div class="input-group">
        <label for="day">Day:</label>
        <input type="text" id="day" name="day" readonly>
      </div>
      <div class="input-group">
        <label for="slot">Slot:</label>
        <select id="slot" name="slot" required>
          <option value="">Select slot</option>
          <option value="8:50-10:30">8:50-10:30</option>
          <option value="10:30-12:20">10:30-12:20</option>
          <option value="1:40-3:20">1:40-3:20</option>
          <option value="2:30-4:10">2:30-4:10</option>
        </select>
      </div>
      <div class="input-group">
        <label for="venue">Venue:</label>
        <select id="venue" name="venue" required>
          <option value="">Select venue</option>
          <option value="ab1">ab1</option>
          <option value="ab2">ab2</option>
          <option value="ab2">ab3</option>
        </select>
      </div>
      <div class="input-group">
        <input type="submit" name="submit" value="Submit">
      </div>
    </form>
  </div>

  <script>
    function updateDayField() {
      const dateInput = document.getElementById('date');
      const dayInput = document.getElementById('day');
      const selectedDate = new Date(dateInput.value);
      const options = { weekday: 'long' };
      const day = selectedDate.toLocaleDateString('en-US', options);
      dayInput.value = day;
    }
  </script>
</body>
</html>
