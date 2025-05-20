<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfinance";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;

if (!$loanId || !in_array($action, ['view', 'download'])) {
  echo "Invalid request.";
  exit;
}

$sql = "
  SELECT loan_info.*, client_info.first_name, client_info.last_name
  FROM loan_info
  JOIN client_info ON loan_info.client_id = client_info.client_id
  WHERE loan_info.loan_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $loanId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  if ($action === 'view') {
    echo "<ul class='list-group'>";
    echo "<li class='list-group-item'><strong>Purpose:</strong> " . htmlspecialchars($row['purpose']) . "</li>";
    echo "<li class='list-group-item'><strong>Client:</strong> " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</li>";
    echo "<li class='list-group-item'><strong>Amount:</strong> ₱" . number_format($row['amount'], 2) . "</li>";
    echo "<li class='list-group-item'><strong>Terms:</strong> " . htmlspecialchars($row['terms']) . " months</li>";
    echo "<li class='list-group-item'><strong>Interest Rate:</strong> " . htmlspecialchars($row['interest']) . "%</li>";
    echo "<li class='list-group-item'><strong>Total to Pay:</strong> ₱" . number_format($row['total'], 2) . "</li>";
    echo "</ul>";
  } elseif ($action === 'download') {
    echo "<p><strong>Download Ready:</strong></p>";
    echo "<p>Client: " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</p>";
    echo "<p>Purpose: " . htmlspecialchars($row['purpose']) . "</p>";
    echo "<p>Amount: ₱" . number_format($row['amount'], 2) . "</p>";
    echo "<p>Terms: " . htmlspecialchars($row['terms']) . " months</p>";
    echo "<p>Interest Rate: " . htmlspecialchars($row['interest']) . "%</p>";
    echo "<p>Total to Pay: ₱" . number_format($row['total'], 2) . "</p>";
    echo "<a href='#' class='btn btn-primary disabled'>Download PDF (Coming Soon)</a>";
  }
} else {
  echo "Loan record not found.";
}

$stmt->close();
$conn->close();
?>
