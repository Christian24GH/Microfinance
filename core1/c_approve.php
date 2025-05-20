<?php
session_start();
include "components/config.php";

// Update c_status to 'Approved' for the given client_id
$client_id = $_GET["id"];
$sql_status = "UPDATE client_status SET c_status = 'Approved' WHERE client_id = ?";
$stmt_status = $conn->prepare($sql_status);
$stmt_status->bind_param("s", $client_id);
$stmt_status->execute();

// Check if a record already exists for this client_id
$sql_check = "SELECT COUNT(*) FROM client_loan_limit WHERE client_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $client_id);
$stmt_check->execute();
$stmt_check->bind_result($count);
$stmt_check->fetch();
$stmt_check->close();

if ($count > 0) {
    // Update existing record
    $sql_loanlimit = "UPDATE client_loan_limit SET ll_amount = NULL, ll_month = NULL, ll_interest = NULL WHERE client_id = ?";
    $stmt_loanlimit = $conn->prepare($sql_loanlimit);
    $stmt_loanlimit->bind_param("s", $client_id);
    $stmt_loanlimit->execute();
    $stmt_loanlimit->close();
} else {
    // Insert new record
    $sql_loanlimit = "INSERT INTO client_loan_limit (ll_amount, ll_month, ll_interest, client_id) VALUES (NULL, NULL, NULL, ?)";
    $stmt_loanlimit = $conn->prepare($sql_loanlimit);
    $stmt_loanlimit->bind_param("s", $client_id);
    $stmt_loanlimit->execute();
    $stmt_loanlimit->close();
}

$sql_status = "UPDATE client_status SET l_status = 'Pending' WHERE client_id = ?";
$stmt_status = $conn->prepare($sql_status);
$stmt_status->bind_param("s", $client_id);
$stmt_status->execute();
$stmt_status->close();

header("Location: users.php");
exit();
?>
