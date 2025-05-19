<?php
include 'db_connect.php';

if (!isset($_POST['position'], $_POST['year'], $_POST['month'])) {
    echo json_encode(["error" => "Missing parameters."]);
    exit;
}

$position = trim($_POST['position']);
$year = (int)$_POST['year'];
$month = trim($_POST['month']);

$monthNum = date("m", strtotime($month)); // Convert month to numeric

$query = $conn->prepare("
    SELECT a.firstname, a.middlename, a.lastname, a.email, p.name as position
    FROM application a
    JOIN positions p ON a.position_id = p.position_id
    WHERE p.name = ? 
    AND YEAR(a.date_created) = ? 
    AND MONTH(a.date_created) = ?
");

$query->bind_param("sii", $position, $year, $monthNum);
$query->execute();
$result = $query->get_result();

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = [
        "name" => $row["firstname"] . " " . $row["middlename"] . " " . $row["lastname"],
        "email" => $row["email"],
        "position" => $row["position"]
    ];
}

echo json_encode($employees);
?>
