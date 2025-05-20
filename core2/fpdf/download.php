<?php
require('fpdf.php'); // download from http://www.fpdf.org/

$loan_id = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;

$conn = new mysqli('localhost','root','','lown_db');
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "
    SELECT loan_info.*, client_info.first_name, client_info.last_name 
    FROM loan_info 
    JOIN client_info ON loan_info.client_id = client_info.client_id 
    WHERE loan_info.loan_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows == 0) {
    die("Loan data not found.");
}
$row = $res->fetch_assoc();

$stmt->close();
$conn->close();

$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Loan Information',0,1,'C');
$pdf->Ln(5);

// Table header
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(60,10, 'Field', 1, 0, 'C', true);
$pdf->Cell(120,10, 'Value', 1, 1, 'C', true);

// Table content
$pdf->SetFont('Arial','',12);

function addRow($pdf, $field, $value) {
    $pdf->Cell(60,10, $field, 1);
    $pdf->Cell(120,10, $value, 1, 1);
}

addRow($pdf, 'Loan ID', $row['loan_id']);
addRow($pdf, 'Client Name', $row['first_name'] . ' ' . $row['last_name']);
addRow($pdf, 'Purpose', $row['purpose']);
addRow($pdf, 'Amount', '₱' . number_format($row['amount'], 2));
addRow($pdf, 'Terms (months)', $row['terms']);
addRow($pdf, 'Interest Rate', $row['interest'] . '%');
addRow($pdf, 'Total to Pay', '₱' . number_format($row['total'], 2));

$pdf->Output('D', 'loan_'.$loan_id.'_data.pdf');
exit;
?>
