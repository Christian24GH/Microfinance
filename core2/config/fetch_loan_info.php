<?php
// fetch_loan_info.php

include 'db.php'; // Your DB connection setup

if (!isset($_GET['client_id'])) {
    http_response_code(400);
    echo "Client ID is required.";
    exit;
}

$client_id = intval($_GET['client_id']);

$sql = "SELECT loan_id, amount, month, terms, purpose, loan_state FROM loan_info WHERE client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered'>";
    echo "<thead><tr>
            <th>Loan ID</th>
            <th>Amount</th>
            <th>Month</th>
            <th>Terms</th>
            <th>Purpose</th>
            <th>Status</th>
          </tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['loan_id']) . "</td>";
        echo "<td>" . htmlspecialchars(number_format($row['amount'], 2)) . "</td>";
        echo "<td>" . htmlspecialchars($row['month']) . "</td>";
        echo "<td>" . htmlspecialchars($row['terms']) . "</td>";
        echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
        echo "<td>" . htmlspecialchars($row['loan_state']) . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No loans found for this client.</p>";
}
?>
