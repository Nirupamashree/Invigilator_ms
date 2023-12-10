<!DOCTYPE html>
<html>
<head>
    <title>Faculty Table Viewer</title>
    <style>
        /* Your existing CSS styles remain unchanged */
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script>
        function showAlert(message) {
            alert(message);
        }

        function showTable() {
            var facultyName = document.getElementById('faculty_name').value;
            window.location.href = 'show-and-download.php?faculty_name=' + encodeURIComponent(facultyName);
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

    $showTable = false;
    $facultyName = $_GET['faculty_name'] ?? '';

    if (!empty($facultyName)) {
        if ($pdo->query("SHOW TABLES LIKE '$facultyName'")->rowCount() > 0) {
            $stmt = $pdo->prepare("SELECT * FROM $facultyName");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $showTable = true;
        }
    }

    if (!$showTable && !empty($facultyName)) {
        echo '<script>showAlert("Faculty TimeTable does not exist.");</script>';
    } 
    ?>

    <div class="container">
        <h2>FACULTY TIMETABLE</h2>
        <div class="form-container">
            <label for="faculty_name">Faculty Name:</label>
            <input type="text" id="faculty_name" name="faculty_name" required value="<?php echo $facultyName; ?>">
            <input type="button" onclick="showTable()" value="Show Table and Download PDF">
            <br>
            <br>
        </div>

        <?php if ($showTable): ?>
        <div class="table-container">
            <h2>Faculty: <?php echo $facultyName; ?></h2>
            <table>
                <tr>
                    <?php foreach ($result[0] as $column => $value): ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?php echo $value; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
