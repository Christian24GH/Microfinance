<?php
require('fpdf.php');  // Adjust path if needed

// Read the JSON payload from POST
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['charts']) || !is_array($data['charts'])) {
    http_response_code(400);
    echo "No chart images received.";
    exit;
}

$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 20);

// Generate pages for each chart
foreach ($data['charts'] as $chart) {
    if (empty($chart['data']) || empty($chart['name'])) continue;

    $pdf->AddPage();

    // Chart title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $chart['name'], 0, 1, 'C');
    $pdf->Ln(5);

    // Decode base64 image data and save temporarily
    $imgData = preg_replace('#^data:image/\w+;base64,#i', '', $chart['data']);
    $imgDecoded = base64_decode($imgData);
    if ($imgDecoded === false) {
        // Skip this chart if decoding fails
        continue;
    }
    $tmpFile = tempnam(sys_get_temp_dir(), 'chart_') . '.png';
    file_put_contents($tmpFile, $imgDecoded);

    // Calculate image display size maintaining aspect ratio
    $pageWidth = $pdf->GetPageWidth() - 20; // 10 mm margin each side
    list($width, $height) = getimagesize($tmpFile);
    if (!$width || !$height) {
        unlink($tmpFile);
        continue;
    }
    $ratio = $height / $width;
    $pdfWidth = $pageWidth;
    $pdfHeight = $pdfWidth * $ratio;

    $pdf->Image($tmpFile, 10, $pdf->GetY(), $pdfWidth, $pdfHeight);
    unlink($tmpFile);

    $pdf->Ln($pdfHeight + 5);

    // Table header for raw data
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(90, 10, 'Category', 1, 0, 'C', true);
    $pdf->Cell(90, 10, 'Value', 1, 1, 'C', true);

    // Table rows for raw data
    $pdf->SetFont('Arial', '', 12);
    if (!empty($chart['rawData']) && is_array($chart['rawData'])) {
        foreach ($chart['rawData'] as $category => $value) {
            $pdf->Cell(90, 10, $category, 1);
            $pdf->Cell(90, 10, (string)$value, 1, 1);
        }
    }
}


// Output PDF for download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="loan_charts_report.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('D', 'loan_charts_report.pdf');
exit;
?>
