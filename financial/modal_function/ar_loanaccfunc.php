<?php
session_start();
include("../config/ar_loanacc_conn.php");

/* update loan account */
if (isset($_POST['update_info'])) {
    $loanacc_id = $_POST['loanacc_id'];
    $initialAmount = $_POST['initialAmount'];
    $interestRate = $_POST['interestRate'];
    $status = $_POST['status'];
    $disbursementDate = $_POST['disbursementDate'];

    // Fetch existing data from ar_loan_account_tbl
    $existing_data_query = "SELECT * FROM ar_loan_account_tbl WHERE loanacc_id = ?";
    $stmt_existing = mysqli_prepare($connections, $existing_data_query);
    mysqli_stmt_bind_param($stmt_existing, "s", $loanacc_id);
    mysqli_stmt_execute($stmt_existing);
    $existing_data_result = mysqli_stmt_get_result($stmt_existing);
    $existing_data = mysqli_fetch_assoc($existing_data_result);

    // Check if any changes are needed
    if (
        $existing_data['initialAmount'] == $initialAmount &&
        $existing_data['interestRate'] == $interestRate &&
        $existing_data['status'] == $status &&
        $existing_data['disbursementDate'] == $disbursementDate
    ) {
        $_SESSION['status'] = array(
            'message' => "No changes were made.",
            'class' => 'text-danger'
        );
        header('location: ../financial/ar_loanacc.php');
        exit();
    }

    // Update ar_loan_account_tbl
    $sql = "UPDATE ar_loan_account_tbl SET initialAmount=?, interestRate=?, status=?, disbursementDate=? WHERE loanacc_id=?";
    $stmt = mysqli_prepare($connections, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $initialAmount, $interestRate, $status, $disbursementDate, $loanacc_id);

    // Update disb_loan_tbl status
    $disb_loan_sql = "UPDATE disb_loan_tbl SET status=? WHERE loan_id=(SELECT fk_loan_id FROM ar_loan_account_tbl WHERE loanacc_id=?)";
    $disb_loan_stmt = mysqli_prepare($connections, $disb_loan_sql);
    mysqli_stmt_bind_param($disb_loan_stmt, "ss", $status, $loanacc_id);

    // Execute both updates
    if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($disb_loan_stmt)) {
        $_SESSION['status'] = array(
            'message' => "You've successfully updated the information.",
            'class' => 'text-success'
        );
        header('location: ../financial/ar_loanacc.php');
    } else {
        $_SESSION['status'] = array(
            'message' => "You've failed to update the information.",
            'class' => 'text-danger'
        );
        header('location: ../financial/ar_loanacc.php');
        exit();
    }
}
/* update loan account */

/* delete loan account */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['loanacc_id'];

        $delete_query = "DELETE FROM ar_loan_account_tbl WHERE loanacc_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/ar_loanacc.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ar_loanacc.php');
        }
    }
/* delete loan account */