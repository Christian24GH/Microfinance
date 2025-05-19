<?php
session_start();
include("../config/col_ar_conn.php");

/* update ar */
    if (isset($_POST['update_info'])) {
        $id = $_POST['ar_id'];
        $outstandingAmount = $_POST['outstandingAmount'];
        $transactionDate = $_POST['transactionDate'];

        $existing_data_query = "SELECT * FROM collection_ar_tbl WHERE ar_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['outstandingAmount'] == $outstandingAmount && $existing_data['transactionDate'] == $transactionDate
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/col_ar.php');
            exit();
        }

        $sql = "UPDATE collection_ar_tbl SET outstandingAmount=?, transactionDate=? WHERE ar_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $outstandingAmount, $transactionDate, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/col_ar.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/col_ar.php');
            exit();
        }
    }
/* update ar */

/* delete ar */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['ar_id'];

        $delete_query = "DELETE FROM collection_ar_tbl WHERE ar_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/col_ar.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/col_ar.php');
        }
    }
/* delete ar */