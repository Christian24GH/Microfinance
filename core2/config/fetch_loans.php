<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lown_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$search = $_GET['search'] ?? '';
$interest = $_GET['interest_rate'] ?? '';
$term = $_GET['term'] ?? '';

$sql = "
  SELECT 
    loan.*, 
    client_info.client_id, 
    client_info.firstname, 
    client_info.lastname
  FROM loan
  JOIN client_info ON loan.client_id = client_info.client_id
  WHERE 1
";

if ($search) {
  $search = $conn->real_escape_string($search);
  $sql .= " AND (
    loan.loan_type LIKE '%$search%' OR
    client_info.client_id LIKE '%$search%' OR
    client_info.firstname LIKE '%$search%' OR
    client_info.lastname LIKE '%$search%'
  )";
}


if ($interest !== '') {
  $interest = floatval($interest);
  $sql .= " AND loan.interest_rate = $interest";
}

if ($term !== '') {
  $term = intval($term);
  $sql .= " AND loan.term = $term";
}

$result = $conn->query($sql);

if ($result->num_rows > 0):
  while($row = $result->fetch_assoc()):
?>
<tr>
  <td><?= $row["loan_id"] ?></td>
  <td><?= htmlspecialchars($row["firstname"]) ?></td>
  <td><?= htmlspecialchars($row["lastname"]) ?></td>
  <td><?= $row["loan_type"] ?></td>
  <td><?= $row["start_date"] ?></td>
  <td><?= $row["interest_rate"] ?></td>
  <td><?= $row["term"] ?></td>
  <td>₱<?= number_format($row["original_amount"], 2) ?></td>
  <td>₱<?= number_format($row["current_balance"], 2) ?></td>
  <td>
    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="showModalContent('View', <?= $row['loan_id'] ?>)">
      <i class="bi bi-eye-fill"></i>
    </button>
    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#downloadModal" onclick="showModalContent('Download', <?= $row['loan_id'] ?>)">
      <i class="bi bi-download"></i>
    </button>
  </td>
</tr>
<?php
  endwhile;
else:
?>
<tr><td colspan="10">No loan records found.</td></tr>
<?php
endif;

$conn->close();
?>
