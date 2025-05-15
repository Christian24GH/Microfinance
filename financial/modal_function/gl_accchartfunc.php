<?php
session_start();
include("../config/gl_accchart_conn.php");

/* update chart of account */
    if (isset($_POST['update_info'])) {
        $id = $_POST['account_id'];
        $accountName = $_POST['accountName'];
        $accountLoan = $_POST['accountLoan'];
        $accountIncome = $_POST['accountIncome'];
        $accountInterest = $_POST['accountInterest'];
        $account_type = $_POST['account_type'];

        $existing_data_query = "SELECT * FROM gl_chart_of_account_tbl WHERE account_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['accountName'] == $accountName && $existing_data['accountLoan'] == $accountLoan &&
            $existing_data['accountIncome'] == $accountIncome && $existing_data['accountInterest'] == $accountInterest && $existing_data['account_type'] == $account_type
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_chart_of_acc.php');
            exit();
        }

        $sql = "UPDATE gl_chart_of_account_tbl SET accountName=?, accountLoan=?, accountIncome=?, accountInterest=?, account_type=? WHERE account_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $accountName, $accountLoan, $accountIncome, $accountInterest, $account_type, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_chart_of_acc.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_chart_of_acc.php');
            exit();
        }
    }
/* update chart of account*/

/* delete chart of account */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['account_id'];

        $delete_query = "DELETE FROM gl_chart_of_account_tbl WHERE account_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_chart_of_acc.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_chart_of_acc.php');
        }
    }
/* delete chart of account */