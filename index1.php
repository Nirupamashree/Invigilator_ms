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
        // Construct the URL of your Azure Function
        $functionUrl = 'https://function43.azurewebsites.net/api/HttpTrigger1';
        $code = 'CtbneX7Ybx1cY1NBmQuVcgxoRvv55Zm8is1vyrlVVsbvAzFuU-ZoDQ==';

        // Append the credentials to the URL
        $urlWithCredentials = "{$functionUrl}?code={$code}&username={$email}&password={$pass}";

        // Make a request to the Azure Function
        $response = file_get_contents($urlWithCredentials);
        $responseData = json_decode($response, true);

        if ($responseData && $responseData['status'] === 200) {
            // Authentication successful
            $userId = $responseData['userId'];
            $username = $responseData['username'];

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
<!-- The rest of your HTML remains unchanged -->
