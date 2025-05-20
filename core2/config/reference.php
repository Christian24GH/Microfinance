<?php
if (!isset($_GET['client_id'])) {
    echo "No client selected.";
    exit;
}

$client_id = intval($_GET['client_id']);

$conn = new mysqli('localhost', 'root', '', 'microfinance');
if ($conn->connect_error) {
    echo "Connection failed.";
    exit;
}

// Adjusted SQL with correct column names from client_references table
$sql = "SELECT fr_first_name, fr_last_name, fr_relationship, fr_contact_number 
        FROM client_references 
        WHERE client_id = $client_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul class='list-unstyled m-0'>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='mb-2'>
                <strong>" . htmlspecialchars($row['fr_first_name']) . " " . htmlspecialchars($row['fr_last_name']) . "</strong><br>
                <small>" . htmlspecialchars($row['fr_relationship']) . " - " . htmlspecialchars($row['fr_contact_number']) . "</small>
              </li>";
    }
    echo "</ul>";
} else {
    echo "No references found.";
}

$conn->close();
?>
