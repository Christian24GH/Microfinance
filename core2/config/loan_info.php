<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'lown_db';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) die("DB connection error.");

$client_id = intval($_GET['client_id']);
$sql = "SELECT * FROM loan_info WHERE client_id = $client_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered text-center'>";
    echo "<thead><tr>
            <th>Loan ID</th>
            <th>Amount</th>
            <th>Months</th>
            <th>Terms</th>
            <th>Purpose</th>
            <th>Interest</th>
            <th>Total</th>
          </tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['loan_id']}</td>
                <td>{$row['amount']}</td>
                <td>{$row['month']}</td>
                <td>{$row['terms']}</td>
                <td>{$row['purpose']}</td>
                <td>{$row['interest']}</td>
                <td>{$row['total']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "No loan records found.";
}
?>
