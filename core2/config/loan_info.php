<?php
if (!isset($_GET['client_id'])) {
    echo "No client selected.";
    exit;
}

$client_id = intval($_GET['client_id']);

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'lown_db');
if ($conn->connect_error) {
    echo "Connection failed: " . htmlspecialchars($conn->connect_error); // Optional: for debugging
    exit;
}

// Prepare SQL query using prepared statement
$stmt = $conn->prepare("SELECT loan_id, amount, month, terms, purpose, loan_state 
                        FROM loan_info 
                        WHERE client_id = client_ids");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<ul class='list-unstyled m-0'>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='mb-3 border-bottom pb-2'>
                <strong>Loan ID:</strong> " . htmlspecialchars($row['loan_id']) . "<br>
                <strong>Amount:</strong> ₱" . htmlspecialchars($row['amount']) . "<br>
                <strong>Months:</strong> " . htmlspecialchars($row['month']) . "<br>
                <strong>Terms:</strong> " . htmlspecialchars($row['terms']) . "<br>
                <strong>Purpose:</strong> " . htmlspecialchars($row['purpose']) . "<br>
                <strong>Status:</strong> " . htmlspecialchars($row['loan_state']) . "
              </li>";
    }
    echo "</ul>";
} else {
    echo "No loan records found.";
}

$stmt->close();
$conn->close();
?>
