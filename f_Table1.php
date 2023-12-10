<!DOCTYPE html>
<html>
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
    <script>
        function showAlert(message) {
            alert(message);
        }

        function showTable() {
            var facultyName = document.getElementById('faculty_name').value;
            window.location.href = '?faculty_name=' + encodeURIComponent(facultyName);
        }
    </script>
</head>
<body>
    <?php
    // Include the Azure Storage Blob SDK
    require_once 'vendor/autoload.php';

    use MicrosoftAzure\Storage\Blob\BlobRestProxy;
    use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
    use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;

    // Azure Storage account credentials
    $connectionString = 'DefaultEndpointsProtocol=https;AccountName=storage123443;AccountKey=vKRDkRv/PG3pEXPK5i0g6EV1g08MBCJaxVUu6eN89IUH2mMymbEha55QT1RiPBuRGF+q7+F1vP/s+AStI5iMhg==;EndpointSuffix=core.windows.net';
    $containerName = 'nirupama43';
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $facultyName = $_POST['faculty_name'] ?? '';

        // Upload tt.jpg to Azure Blob Storage
        $imagePath = $_FILES['file']['tmp_name'];

        try {
            // Upload the image to Azure Blob Storage
            $content = file_get_contents($imagePath);
            $blobName = 'tt.jpg';
            $blobClient->createBlockBlob(
                $containerName,
                $blobName,
                $content,
                new CreateBlockBlobOptions()
            );

            echo "Image uploaded to Azure Blob Storage successfully!";
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo "Error uploading image: $error_message";
        }
    }

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
        <h3>Faculty time-table</h3>
        <a href="tt.jpg" download>
            <img src="tt.jpg" alt="Timetable Image" style="max-width: 100%; height: auto; margin-top: 20px;">
        </a>
        <br>
        <div class="form-container">
            <!-- Form to show the table -->
            <form method="get">
                <label for="faculty_name_show">Faculty Name:</label>
                <input type="text" id="faculty_name_show" name="faculty_name" required value="<?php echo $facultyName; ?>">
                <input type="submit" value="Show Table">
            </form>
        </div>

        <div class="form-container">
            <!-- Form to upload the image -->
            <form method="post" enctype="multipart/form-data">
                <label for="faculty_name_upload">Faculty Name:</label>
                <input type="text" id="faculty_name_upload" name="faculty_name" required value="<?php echo $facultyName; ?>">
                <label for="file">Upload tt.jpg to Azure Blob Storage:</label>
                <input type="file" name="file" id="file" accept=".jpg">
                <input type="submit" value="Upload Image">
            </form>
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
