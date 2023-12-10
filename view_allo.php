<!DOCTYPE html>
<html>
<head>
    <title>Allocate Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .filter-container input[type="text"] {
            padding: 10px;
            margin-left: 10px;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: white; 
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .table-container {
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <h1>ALLOCATED RECORDS</h1>

    <div class="filter-container">
        <label for="filter">Search:</label>
        <input type="text" id="filter" name="filter" placeholder="Enter a keyword">
    </div>

    <div class="table-container">
        <table id="recordsTable">
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Semester</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Slot</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('tcpdf.php');

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

// Fetch records from the allocate table
                $sql = "SELECT * FROM `{$dbname}`.`allocate`";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $pdf = new TCPDF();

    // Set document properties
                    $pdf->SetCreator('Your Name');
                    $pdf->SetAuthor('Your Name');
                    $pdf->SetTitle('Allocated Records');

    // Add a page
                    $pdf->AddPage();

    // Set font
                    $pdf->SetFont('Arial', 'B', 12);

    // Add table headers
                    $pdf->Cell(30, 10, 'Faculty Name', 1);
                    $pdf->Cell(20, 10, 'Semester', 1);
                    $pdf->Cell(20, 10, 'Course', 1);
                    $pdf->Cell(25, 10, 'Date', 1);
                    $pdf->Cell(20, 10, 'Day', 1);
                    $pdf->Cell(20, 10, 'Slot', 1);
                    $pdf->Cell(25, 10, 'Venue', 1);

    // Add table rows
                    while ($row = $result->fetch_assoc()) {
                        $pdf->Ln(); // Move to the next line
                        $pdf->Cell(30, 10, $row['facultyName'], 1);
                        $pdf->Cell(20, 10, $row['semester'], 1);
                        $pdf->Cell(20, 10, $row['course'], 1);
                        $pdf->Cell(25, 10, $row['date'], 1);
                        $pdf->Cell(20, 10, $row['day'], 1);
                        $pdf->Cell(20, 10, $row['slot'], 1);
                        $pdf->Cell(25, 10, $row['venue'], 1);
                    }

    // Save the PDF to a file
                    $pdfFileName = 'allocated_records.pdf';
                    $pdf->Output($pdfFileName, 'F');

    // Provide a download link
                    echo "<a href='{$pdfFileName}' download>Generate PDF</a>";
                } else {
                    echo "No records found.";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Filter table rows based on the entered keyword
        document.getElementById("filter").addEventListener("keyup", function() {
            var keyword = this.value.toLowerCase();
            var table = document.getElementById("recordsTable");
            var rows = table.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var found = false;

                for (var j = 0; j < cells.length; j++) {
                    var cellText = cells[j].textContent || cells[j].innerText;

                    if (cellText.toLowerCase().indexOf(keyword) > -1) {
                        found = true;
                        break;
                    }
                }

                if (found) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        });
    </script>
</body>
</html>
