<!DOCTYPE html>
<html lang="en">
<head>
    <title>Faculty Table Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 30px;
            font-size: 24px;
            font-weight: bold;
            /*text-transform: uppercase;*/
            letter-spacing: 1px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        table {
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0;
            width: 80%;
            background-color: #fff;
            margin-top: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2); /* Modified shadow */
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .container::after {
            content: "";
            display: table;
            clear: both;
        }

        .form-container {
            float: left;
            width: 300px;
            margin-right: 20px;
        }

        .form-container label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .form-container input[type="text"] {
            width: 100%;
            height: 30px;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .form-container input[type="button"] {
            width: 100%;
            height: 40px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .form-container input[type="button"]:hover {
            background-color: #45a049;
        }

        .table-container {
            margin-top: 30px;
        }

        .table-container table {
            width: 100%;
        }

        .form-container input[type="button"] {
            margin-bottom: 10px;
        }
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

            // Create a new jsPDF instance
            var pdf = new jsPDF();

            // Set the header
            pdf.text(10, 10, 'Faculty Timetable: ' + facultyName);

            // Get the table element
            var table = document.querySelector('.table-container table');

            // Convert the table to a data URL
            var dataURL = tableToDataURL(table);

            // Add the image of the table to the PDF
            pdf.addImage(dataURL, 'JPEG', 10, 20);

            // Save the PDF
            pdf.save(facultyName + '_timetable.pdf');
        }

        // Function to convert a table to data URL
        function tableToDataURL(table) {
            var ctx = document.createElement('canvas').getContext('2d');
            var dataURL;

            // Draw the table on a canvas
            var tableWidth = table.offsetWidth;
            var tableHeight = table.offsetHeight;
            ctx.canvas.width = tableWidth;
            ctx.canvas.height = tableHeight;
            var img = new Image();

            // Create a data URL from the canvas
            img.onload = function() {
                ctx.drawImage(img, 0, 0, tableWidth, tableHeight);
                dataURL = ctx.canvas.toDataURL('image/jpeg');
            };
            img.src = 'data:image/svg+xml;base64,' + btoa(new XMLSerializer().serializeToString(table));

            return dataURL;
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
            <input type="button" onclick="showTable()" value="Show Table">
            <br>
            <br>
        </div>

        <?php if ($showTable): ?>
        <div class="table-container">
            <h2>Faculty: <?php echo $facultyName; ?></h2>
            <table>
                <tr>
                                            <th>day</th>
                                            <th>slot1</th>
                                            <th>slot2</th>
                                            <th>slot3</th>
                                            <th>slot4</th>
                                            <th>slot5</th>
                                            <th>slot6</th>
                                            <th>slot7</th>
                                            <th>slot8</th>
                                    </tr>
                                    <tr>
                                                    <td>Monday</td>
                                                    <td></td>
                                                    <td>19CSE311</td>
                                                    <td></td>
                                                    <td>19CSE332</td>
                                                    <td></td>
                                                    <td>19CSE313</td>
                                                    <td>19CSE313</td>
                                                    <td></td>
                                            </tr>
                                    <tr>
                                                    <td>Tuesday</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>19CSE311</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                            </tr>
                                    <tr>
                                                    <td>Wednesday</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>19CSE102</td>
                                                    <td>19CSE102</td>
                                                    <td></td>
                                                    <td>19CSE332</td>
                                                    <td></td>
                                            </tr>
                                    <tr>
                                                    <td>Thursday</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>19CSE383</td>
                                                    <td>19CSE383</td>
                                                    <td></td>
                                                    <td>19CSE213</td>
                                                    <td>19CSE213</td>
                                                    <td></td>
                                            </tr>
                                    <tr>
                                                    <td>Friday</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>19CSE332</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                            </tr>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
