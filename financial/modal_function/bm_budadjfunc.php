<?php
session_start();
include("../config/bm_budadj_conn.php");

/* update budget planning */
    if (isset($_POST['update_info'])) {
        $id = $_POST['adjustment_id'];
        $adjustmentAmount = $_POST['adjustmentAmount'];
        $adjustmentDate = $_POST['adjustmentDate'];
        $reasonCode = $_POST['reasonCode'];

        $existing_data_query = "SELECT * FROM bm_budget_adjustment_tbl WHERE adjustment_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['adjustmentAmount'] == $adjustmentAmount && $existing_data['adjustmentDate'] == $adjustmentDate
            && $existing_data['reasonCode'] == $reasonCode
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/bm_budget_adjustment.php');
            exit();
        }

        $sql = "UPDATE bm_budget_adjustment_tbl SET adjustmentAmount=?, adjustmentDate=?, reasonCode=?  WHERE adjustment_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $adjustmentAmount, $adjustmentDate, $reasonCode, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/bm_budget_adjustment.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/bm_budget_adjustment.php');
            exit();
        }
    }
/* update funder repayment */

/* delete funder repayment */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['adjustment_id'];

        $delete_query = "DELETE FROM bm_budget_adjustment_tbl WHERE adjustment_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/bm_budget_adjustment.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/bm_budget_adjustment.php');
        }
    }
/* delete funder repayment */