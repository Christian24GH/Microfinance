<?php
session_start();
include("../config/ar_repayman_conn.php");

/* update disbursement tracking */
    if (isset($_POST['update_info'])) {
        $id = $_POST['repayment_id'];
        $amountPaid = $_POST['amountPaid'];
        $paymentDate = $_POST['paymentDate'];

        $existing_data_query = "SELECT * FROM ar_repayment_management_tbl WHERE repayment_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['amountPaid'] == $amountPaid && $existing_data['paymentDate'] == $paymentDate
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ar_repayment.php');
            exit();
        }

        $sql = "UPDATE ar_repayment_management_tbl SET amountPaid=?, paymentDate=?  WHERE repayment_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $amountPaid, $paymentDate, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/ar_repayment.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ar_repayment.php');
            exit();
        }
    }
/* update disbursement tracking */

/* delete disbursement tracking */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['repayment_id'];

        $delete_query = "DELETE FROM ar_repayment_management_tbl WHERE repayment_id = '$id'";
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