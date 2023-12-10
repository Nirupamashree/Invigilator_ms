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

            if (facultyName.trim() === '') {
                showAlert("Please enter a valid faculty name.");
                return;
            }

            // AJAX request to fetch table data
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var result = JSON.parse(xhr.responseText);
                        if (result.error) {
                            showAlert(result.error);
                        } else {
                            generatePDF(result.data, facultyName);
                        }
                    } else {
                        showAlert("Error fetching data from the server.");
                    }
                }
            };
            xhr.open('GET', 'fetch-table-data.php?faculty_name=' + encodeURIComponent(facultyName), true);
            xhr.send();
        }

        function generatePDF(data, facultyName) {
            var pdf = new jsPDF();
            pdf.text(10, 10, 'Faculty Timetable: ' + facultyName);

            var columns = Object.keys(data[0]);
            var rows = data.map(obj => columns.map(key => obj[key]));

            pdf.autoTable({
                head: [columns],
                body: rows
            });

            pdf.save(facultyName + '_timetable.pdf');
        }
    </script>
</head>
<body>
    <?php
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

$facultyName = $_GET['faculty_name'] ?? '';
$response = [];

if (!empty($facultyName)) {
    $stmt = $pdo->prepare("SELECT * FROM $facultyName");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['data'] = $result;
} else {
    $response['error'] = "Invalid faculty name.";
}

header('Content-Type: application/json');
echo json_encode($response);
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
