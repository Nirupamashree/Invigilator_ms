<?php
// MySQL server configuration
$servername = "sqlserver43.mysql.database.azure.com";
$username = "nirupamashree";
$password = "password@123";
$database = "user1_db";

// Create a connection to the MySQL server
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start the PHP session

$error = ""; // Variable to store login error message

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        $query = "SELECT * FROM admin_form WHERE email='$email' AND password='$pass'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            // User found, fetch user details
            $row = mysqli_fetch_assoc($result);
            $userId = $row['id'];
            $username = $row['name'];

            // Store user information in session variables
            $_SESSION['userId'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;

            header('Location: admin.html'); // Redirect to the desired page after successful login
            exit();
        } else {
            $error = 'Incorrect email or password!';
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

    .login-container {
      
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

    input[type="email"],
    input[type="password"] {
      width: 95%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    input[type="checkbox"] {
      margin-right: 5px;
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

    .hide-password input[type="password"] {
      -webkit-text-security: disc;
      -moz-text-security: disc;
      -ms-text-security: disc;
      text-security: disc;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>ADMIN LOGIN</h2>
    <form action="" method="POST">
      <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        <input type="checkbox" id="showPassword" onchange="togglePasswordVisibility()">
        <label for="showPassword">Show Password</label>
      </div>
      <div class="input-group">
        <input type="submit" name="submit" value="Login">
      </div>
      <?php if (!empty($error)): ?>
        <p><?php echo $error; ?></p>
      <?php endif; ?>
    </form>
  </div>
  <script>
    function togglePasswordVisibility() {
      var passwordInput = document.getElementById("password");
      var showPasswordCheckbox = document.getElementById("showPassword");

      if (showPasswordCheckbox.checked) {
        passwordInput.type = "text";
      } else {
        passwordInput.type = "password";
      }
    }
  </script>
</body>
</html>
