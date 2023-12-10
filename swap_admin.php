<?php
session_start(); // Start the session
require_once('tcpdf/tcpdf.php');

$servername = "sqlserver43.mysql.database.azure.com";
$username = "nirupamashree";
$password = "password@123";
$dbname = "user1_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the accept table
$sql = "SELECT * FROM accept";
$result = $conn->query($sql);

$data = array(
    array("Faculty Name(REQUESTED)", "Faculty Name(ACCEPTED)", "Date", "Day", "Slot", "Venue"),
    array("faculty1", "faculty2", "2023-11-02", "Thursday", "slot1", "ab1"),
    array("faculty2", "faculty1", "2023-12-01", "Friday", "slot1", "ab1")
);

// Create a PDF document using TCPDF
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();

// Output your table data to the PDF
$pdf->SetFont('helvetica', '', 12);
foreach ($data as $row) {
    $pdf->Cell(30, 10, $row[0], 1);
    $pdf->Cell(30, 10, $row[1], 1);
    $pdf->Cell(30, 10, $row[2], 1);
    $pdf->Cell(30, 10, $row[3], 1);
    $pdf->Cell(30, 10, $row[4], 1);
    $pdf->Cell(30, 10, $row[5], 1);
    $pdf->Ln();
}

// Output PDF for download
$pdf->Output('Swapped_Schedule.pdf', 'D');
exit();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accepted Requests</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .filter-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Accepted Requests</h2>

        <table>
            <thead>
                <tr>
                    <th>Faculty Name(REQUESTED)</th>
                    <th>Faculty Name(ACCEPTED)</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Slot</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["request_to"] . "</td>";
                        echo "<td>" . $row["request_from"] . "</td>";
                        echo "<td>" . $row["date"] . "</td>";
                        echo "<td>" . $row["day"] . "</td>";
                        echo "<td>" . $row["slot"] . "</td>";
                        echo "<td>" . $row["venue"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No accepted requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function filterTable() {
            // Implement your filtering logic here
            alert("Filtering table");
        }
    </script>
</body>
</html>
