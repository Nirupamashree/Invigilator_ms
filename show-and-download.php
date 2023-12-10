<?php
// Your existing PHP code remains unchanged

require('tcpdf/tcpdf.php');

$facultyName = $_GET['faculty_name'] ?? '';

if (!empty($facultyName)) {
    $stmt = $pdo->prepare("SELECT * FROM $facultyName");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate PDF content
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Add table headers
    foreach ($result[0] as $column => $value) {
        $pdf->Cell(30, 10, $column, 1);
    }
    $pdf->Ln();

    // Add table data
    foreach ($result as $row) {
        foreach ($row as $value) {
            $pdf->Cell(30, 10, $value, 1);
        }
        $pdf->Ln();
    }

    // Output PDF to the browser
    $pdf->Output($facultyName . '_timetable.pdf', 'D');
} else {
    echo "Invalid faculty name.";
}
?>
