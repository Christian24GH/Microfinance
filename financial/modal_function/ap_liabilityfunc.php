<?php
session_start();
include("../config/ap_liability_conn.php");

/* update liability */
    if (isset($_POST['update_info'])) {
        $id = $_POST['liability_id'];
        $initialAmount = $_POST['initialAmount'];
        $interestRate = $_POST['interestRate'];
        $dueDate = $_POST['dueDate'];

        $existing_data_query = "SELECT * FROM ap_liability_tbl WHERE liability_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['initialAmount'] == $initialAmount && $existing_data['interestRate'] == $interestRate
            && $existing_data['dueDate'] == $dueDate
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ap_liability.php');
            exit();
        }

        $sql = "UPDATE ap_liability_tbl SET initialAmount=?, interestRate=?, dueDate=?  WHERE liability_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $initialAmount, $interestRate, $dueDate, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/ap_liability.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ap_liability.php');
            exit();
        }
    }
/* update liability */

/* delete liability */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['liability_id'];

        $delete_query = "DELETE FROM ap_liability_tbl WHERE liability_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/ap_liability.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ap_liability.php');
        }
    }
/* delete liability */