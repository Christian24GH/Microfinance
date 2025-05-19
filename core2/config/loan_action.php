<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lown_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$action = $_GET['action'] ?? '';
$loanId = $_GET['loan_id'] ?? 0;

if (!$loanId || !in_array($action, ['view', 'download'])) {
  echo "Invalid request.";
  exit;
}

$sql = "
  SELECT loan.*, client_info.firstname, client_info.lastname
  FROM loan
  JOIN client_info ON loan.client_id = client_info.client_id
  WHERE loan.loan_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loanId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  if ($action === 'view') {
    echo "<ul class='list-group'>";
    echo "<li class='list-group-item'><strong>Loan Type:</strong> {$row['loan_type']}</li>";
    echo "<li class='list-group-item'><strong>Client:</strong> {$row['firstname']} {$row['lastname']}</li>";
    echo "<li class='list-group-item'><strong>Start Date:</strong> {$row['start_date']}</li>";
    echo "<li class='list-group-item'><strong>Interest Rate:</strong> {$row['interest_rate']}%</li>";
    echo "<li class='list-group-item'><strong>Term:</strong> {$row['term']} months</li>";
    echo "<li class='list-group-item'><strong>Original Amount:</strong> ₱" . number_format($row['original_amount'], 2) . "</li>";
    echo "<li class='list-group-item'><strong>Current Balance:</strong> ₱" . number_format($row['current_balance'], 2) . "</li>";
    echo "</ul>";
  } elseif ($action === 'download') {
    // Simulate downloadable info (replace with actual file generation if needed)
    echo "<p><strong>Download Ready:</strong></p>";
    echo "<p>Client: {$row['firstname']} {$row['lastname']}</p>";
    echo "<p>Loan Type: {$row['loan_type']}</p>";
    echo "<p>Amount: ₱" . number_format($row['original_amount'], 2) . "</p>";
    echo "<a href='#' class='btn btn-primary disabled'>Download PDF (Coming Soon)</a>";
  }
} else {
  echo "Loan record not found.";
}

$conn->close();
?>
