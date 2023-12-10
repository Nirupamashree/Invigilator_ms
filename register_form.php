<!DOCTYPE html>
<html>

<head>
    <title>CREATE USER'S ACCOUNT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .form-container label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .form-container input[type="text"],
        .form-container input[type="password"] {
            width: 100%;
            height: 30px;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .form-container input[type="submit"] {
            width: 100%;
            height: 40px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .alert {
            margin-top: 10px;
            padding: 10px;
            border-radius: 3px;
        }

        .alert.error {
            background-color: #ffecec;
            border: 1px solid #f5aca6;
            color: #d83e3e;
        }

        .alert.success {
            background-color: #d5f3e5;
            border: 1px solid #2b9348;
            color: #1f9d55;
        }

    </style>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var confirmInput = document.getElementById("confirm_password");
            var passwordVisibility = document.getElementById("password_visibility");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                confirmInput.type = "text";
                passwordVisibility.textContent = "Hide Password";
            } else {
                passwordInput.type = "password";
                confirmInput.type = "password";
                passwordVisibility.textContent = "Show Password";
            }
        }

        function downloadPDF() {
            // Trigger download link click
            var downloadLink = document.getElementById("downloadLink");
            downloadLink.click();
        }
    </script>
</head>

<body>

    <?php
    // Database connection
    $host = 'sqlserver43.mysql.database.azure.com';
    $db = 'user1_db';
    $user = 'nirupamashree';
    $password = 'password@123';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $password, $options);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    // Function to validate password strength
    function validatePassword($password) {
        // Password must be at least 6 characters long
        if (strlen($password) < 6) {
            return false;
        }

        // Password must contain a combination of letters, numbers, and special characters
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
            return false;
        }

        return true;
    }

    // Handle form submission
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        $errorMsg = '';

        // Check if username already exists in the database
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM user_form WHERE name = ?');
        $stmt->execute([$name]);
        $nameCount = $stmt->fetchColumn();

        // Check if email already exists in the database
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM user_form WHERE email = ?');
        $stmt->execute([$email]);
        $emailCount = $stmt->fetchColumn();

        if ($nameCount > 0) {
            $errorMsg = 'Username already exists.';
        } elseif ($emailCount > 0) {
            $errorMsg = 'Email address is already registered.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg = 'Invalid email address.';
        } elseif ($password !== $confirmPassword) {
            $errorMsg = 'Password and confirm password do not match.';
        } elseif (!validatePassword($password)) {
            $errorMsg = 'Password must be at least 6 characters long and must contain a combination of letters, numbers, and special characters.';
        } elseif (!preg_match('/^[A-Za-z]+[0-9]*$/', $name)) {
            $errorMsg = 'Username must only contain alphanumeric characters.';
        } else {
            // Store user information in the database
            $stmt = $pdo->prepare('INSERT INTO user_form (name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $password]);

            // Generate PDF document
            require_once('tcpdf.php');
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, 'User Registration Details');
            $pdf->Ln();
            $pdf->Cell(40, 10, 'Name: ' . $name);
            $pdf->Ln();
            $pdf->Cell(40, 10, 'Email: ' . $email);
            $pdf->Ln();
            // ... (add more details as needed)

            // Save the PDF to a file (you can adjust the path and filename as needed)
            $pdfFilePath = 'Downloads/generated_pdf/' . $name . '_registration.pdf';
            $pdf->Output($pdfFilePath, 'F');

            $successMsg = 'Registration Successful!';
        }
    }
    ?>

    <br><br><br><br><br>
    <div class="form-container">
        <h2>CREATE USER ACCOUNT</h2>

        <?php
        if (isset($errorMsg)) {
            echo '<p class="alert error">' . $errorMsg . '</p>';
        }

        if (isset($successMsg)) {
            echo '<p class="alert success">' . $successMsg . '</p>';
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" pattern="[A-Za-z]+[0-9]*" title="Username must only contain alphanumeric characters." required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <input type="checkbox" id="password_visibility" onclick="togglePasswordVisibility()">
            <label for="password_visibility">Show Password</label>

            <input type="submit" name="submit" value="Register">
        </form>
    </div>

</body>
</html>
