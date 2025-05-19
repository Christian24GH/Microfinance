<?php
session_start();
include("../config/disb_repayment_conn.php");

/* update repayment */
    if (isset($_POST['update_info'])) {
        $repayment_id = $_POST['repayment_id'];
        $amountPaid = $_POST['amountPaid'];
        $repaymentDate = $_POST['repaymentDate'];

        // If repaymentDate is empty, fetch the current date from the database
        if (empty($repaymentDate)) {
            $get_date_query = "SELECT repaymentDate FROM disb_repayment_tbl WHERE repayment_id = '$repayment_id'";
            $get_date_result = mysqli_query($connections, $get_date_query);

            if ($get_date_result && mysqli_num_rows($get_date_result) > 0) {
                $get_date_row = mysqli_fetch_assoc($get_date_result);
                $repaymentDate = $get_date_row['repaymentDate'];
            } else {
                $_SESSION['status'] = array(
                    'message' => "Repayment record not found.",
                    'class' => 'text-danger'
                );
                header('location: ../financial/disb_repayment.php');
                exit();
            }
        }

        // Update repayment record
        $update_repayment_query = "UPDATE disb_repayment_tbl SET amountPaid = '$amountPaid', repaymentDate = '$repaymentDate' WHERE repayment_id = '$repayment_id'";
        $update_repayment_result = mysqli_query($connections, $update_repayment_query);

        if ($update_repayment_result) {
            // Get the loan_id associated with this repayment
            $loan_query = "SELECT loan_id FROM disb_repayment_tbl WHERE repayment_id = '$repayment_id'";
            $loan_result = mysqli_query($connections, $loan_query);
            $loan_row = mysqli_fetch_assoc($loan_result);
            $loan_id = $loan_row['loan_id'];

            if ($update_loan_result) {
                $_SESSION['status'] = array(
                    'message' => "You've successfully Updated the Information.",
                    'class' => 'text-success'
                );
            } else {
                $_SESSION['status'] = array(
                    'message' => "Failed to update the loan amount paid.",
                    'class' => 'text-danger'
                );
            }
        } else {
            $_SESSION['status'] = array(
                'message' => "Failed to update the repayment information.",
                'class' => 'text-danger'
            );
        }
        header('location: ../financial/disb_repayment.php');
        exit();
    }
/* update repayment */

/* delete repayment */
if (isset($_POST['confirm_delete_btn'])) {
    $id = $_POST['repayment_id'];

    $delete_query = "DELETE FROM disb_repayment_tbl WHERE repayment_id = '$id'";
    $delete_query_run = mysqli_query($connections, $delete_query);

    if ($delete_query_run) {
        $_SESSION['status'] = array(
            'message' => "You've successfully deleted the request.",
            'class' => 'text-success'
        );
    } else {
        $_SESSION['status'] = array(
            'message' => "You've failed to delete the request.",
            'class' => 'text-danger'
        );
    }
    header('location: ../financial/disb_repayment.php');
    exit();
}
/* delete repayment */