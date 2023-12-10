<?php
require_once('tcpdf/tcpdf.php');

// Retrieve data from the accept table
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

$sql = "SELECT * FROM accept";
$result = $conn->query($sql);

// Initialize data array for table
$data = array();
$data[] = array("Faculty Name(REQUESTED)", "Faculty Name(ACCEPTED)", "Date", "Day", "Slot", "Venue");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            $row["request_to"],
            $row["request_from"],
            $row["date"],
            $row["day"],
            $row["slot"],
            $row["venue"]
        );
    }
}

// Create a PDF document using TCPDF
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();

// Output your table data to the PDF
$pdf->SetFont('helvetica', '', 12);
foreach ($data as $row) {
    foreach ($row as $cell) {
        $pdf->Cell(40, 10, $cell, 1);
    }
    $pdf->Ln();
}

// Output PDF for download
$pdf->Output('Accepted_Requests.pdf', 'D');
exit();
?>
