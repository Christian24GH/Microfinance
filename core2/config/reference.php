<?php
if (!isset($_GET['client_id'])) {
    echo "No client selected.";
    exit;
}

$client_id = intval($_GET['client_id']);

$conn = new mysqli('localhost', 'root', '', 'lown_db');
if ($conn->connect_error) {
    echo "Connection failed.";
    exit;
}

$sql = "SELECT firstname, lastname, relationship, contact, email 
        FROM client_references 
        WHERE client_id = $client_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul class='list-unstyled m-0'>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='mb-2'>
                <strong>{$row['firstname']} {$row['lastname']}</strong><br>
                <small>{$row['relationship']} - {$row['email']} - {$row['contact']}</small>
              </li>";
    }
    echo "</ul>";
} else {
    echo "No references found.";
}

$conn->close();
?>
