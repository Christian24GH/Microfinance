<?php
session_start();
include("../config/ar_disbtrack_conn.php");

/* update disbursement tracking */
    if (isset($_POST['update_info'])) {
        $id = $_POST['disbursement_id'];
        $amount = $_POST['amount'];
        $transactionDate = $_POST['transactionDate'];

        $existing_data_query = "SELECT * FROM ar_disb_tracking_tbl WHERE disbursement_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['amount'] == $amount && $existing_data['transactionDate'] == $transactionDate
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ar_disb_tracking.php');
            exit();
        }

        $sql = "UPDATE ar_disb_tracking_tbl SET amount=?, transactionDate=?  WHERE disbursement_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $amount, $transactionDate, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/ar_disb_tracking.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ar_disb_tracking.php');
            exit();
        }
    }
/* update disbursement tracking */

/* delete disbursement tracking */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['disbursement_id'];

        $delete_query = "DELETE FROM ar_disb_tracking_tbl WHERE disbursement_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/ar_disb_tracking.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ar_disb_tracking.php');
        }
    }
/* delete disbursement tracking */