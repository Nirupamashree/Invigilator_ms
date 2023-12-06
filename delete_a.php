<!DOCTYPE html>
<html>
<head>
  <title>Delete Faculty Records</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
  }

  .form-container {
    max-width: 300px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
  }

  .form-group input[type="text"],
  .form-group input[type="email"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f7f7f7;
  }

  /* Updated CSS for the field size */
  .form-group input[type="text"] {
    width: 95%; /* Adjust the width as desired */
  }

  .form-group input[type="email"] {
    width: 95%; /* Adjust the width as desired */
  }

  .form-group input[type="submit"] {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .form-group input[type="submit"]:hover {
    background-color: #45a049;
  }

  .alert {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
    text-align: center;
    font-weight: bold;
  }

  .alert-success {
    background-color: #dff0d8;
    color: #3c763d;
  }

  .alert-error {
    background-color: #f2dede;
    color: #a94442;
  }
</style>

</head>
<body>
    <br>
    <br>
    <br>
  <div class="form-container">
    <h2 style="text-align: center;">DELETE FACULTY RECORDS</h2>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div class="form-group">
        <label for="faculty_name">Faculty Name:</label>
        <input type="text" name="faculty_name" id="faculty_name" required>
      </div>
      <div class="form-group">
        <label for="faculty_email">Faculty Email:</label>
        <input type="email" name="faculty_email" id="faculty_email" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Delete">
      </div>
    </form>

    <?php
    // Database credentials
    $servername = "sqlserver43.mysql.database.azure.com";
    $username = "nirupamashree";
    $password = "laks@2003";
    $database = "user1_db";

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Faculty details
      $facultyName = $_POST['faculty_name'];
      $facultyEmail = $_POST['faculty_email'];

      // Create a database connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Validate faculty name and email
      $validationError = false;
      $validationMessage = '';

      // Check if faculty name exists in the database
      $nameExistsSql = "SELECT * FROM user_form WHERE name = '$facultyName'";
      $nameExistsResult = $conn->query($nameExistsSql);
      if ($nameExistsResult->num_rows == 0) {
        $validationError = true;
        $validationMessage .= 'Faculty name does not exist.<br>';
      }

      // Check if faculty email exists in the database
      $emailExistsSql = "SELECT * FROM user_form WHERE email = '$facultyEmail'";
      $emailExistsResult = $conn->query($emailExistsSql);
      if ($emailExistsResult->num_rows == 0) {
        $validationError = true;
        $validationMessage .= 'Faculty email does not exist.<br>';
      }

      // If there are validation errors, display them in an alert box
      if ($validationError) {
        echo '<div class="alert alert-error">' . $validationMessage . '</div>';
      } else {
        // Prepare the delete query
        $sql = "DELETE FROM user_form WHERE name = '$facultyName' AND email = '$facultyEmail'";

        // Execute the delete query
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success">Records deleted successfully.</div>';
        } else {
            echo '<div class="alert alert-error">Error deleting records: ' . $conn->error . '</div>';
        }
      }

      // Close the database connection
      $conn->close();
    }
    ?>
  </div>
</body>
</html>
