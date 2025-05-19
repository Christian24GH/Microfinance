<?php
require('fpdf.php'); // download from http://www.fpdf.org/

$loan_id = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;

$conn = new mysqli('localhost','root','','lown_db');
if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT loan.*, client_info.firstname, client_info.lastname 
        FROM loan JOIN client_info ON loan.client_id = client_info.client_id 
        WHERE loan.loan_id = $loan_id";
$res = $conn->query($sql);

if($res->num_rows == 0) {
    die("Loan data not found.");
}
$row = $res->fetch_assoc();

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
addRow($pdf, 'Client Name', $row['firstname'].' '.$row['lastname']);
addRow($pdf, 'Loan Type', $row['loan_type']);
addRow($pdf, 'Start Date', $row['start_date']);
addRow($pdf, 'Interest Rate', $row['interest_rate'].'%');
addRow($pdf, 'Term', $row['term']);
addRow($pdf, 'Original Amount', '₱'.number_format($row['original_amount'], 2));
addRow($pdf, 'Current Balance', '₱'.number_format($row['current_balance'], 2));

$pdf->Output('D', 'loan_'.$loan_id.'_data.pdf');
exit;
?>
