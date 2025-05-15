<?php
session_start();
include("../config/gl_transaction_conn.php");

/* update transaction */
    if (isset($_POST['update_info'])) {
        $id = $_POST['transaction_id'];
        $transactionAmount= $_POST['transactionAmount'];
        $transactionItem = $_POST['transactionItem'];
        $transactionTime = $_POST['transactionTime'];
        $transactionDate = $_POST['transactionDate'];
        $transaction_type= $_POST['transaction_type'];

        $existing_data_query = "SELECT * FROM gl_transaction_tbl WHERE transaction_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['transactionAmount'] == $transactionAmount && $existing_data['transactionItem'] == $transactionItem &&
            $existing_data['transactionTime'] == $transactionTime && $existing_data['transactionDate'] == $transactionDate &&
            $existing_data['transaction_type'] == $transaction_type
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_transaction.php');
            exit();
        }

        $sql = "UPDATE gl_transaction_tbl SET transactionAmount=?, transactionItem=?, transactionTime=?, transactionDate=?, transaction_type=? WHERE transaction_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $transactionAmount, $transactionItem, $transactionTime, $transactionDate, $transaction_type, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_transaction.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_transaction.php');
            exit();
        }
    }
/* update transaction */

/* delete transaction */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['transaction_id'];

        $delete_query = "DELETE FROM gl_transaction_tbl WHERE transaction_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_transaction.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_transaction.php');
        }
    }
/* delete transaction */