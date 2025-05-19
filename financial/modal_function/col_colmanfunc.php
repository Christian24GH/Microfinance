<?php
session_start();
include("../config/collman_conn.php");

function display_data() {
    global $connections;

    $today = date('Y-m-d');
    $query = "SELECT loan_id AS collection_id, borrower_id AS overdueAccount, term AS overdueDate, status 
              FROM disb_loan_tbl 
              WHERE term < '$today' 
              AND status IN ('active', 'approved')";

    $result = mysqli_query($connections, $query);
    return $result;
}

/* update account management */
    if (isset($_POST['update_info'])) {
        $id = $_POST['collection_id'];
        $overdueAccount = $_POST['overdueAccount'];
        $overdueDate = $_POST['overdueDate'];
        $status = $_POST['status'];

        $existing_data_query = "SELECT * FROM collection_management_tbl WHERE collection_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['overdueAccount'] == $overdueAccount && $existing_data['overdueDate'] == $overdueDate
            && $existing_data['status'] == $status
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/collection_management.php');
            exit();
        }

        $sql = "UPDATE collection_management_tbl SET overdueAccount=?, overdueDate=?, status=? WHERE collection_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $overdueAccount, $overdueDate, $status, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/collection_management.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/collection_management.php');
            exit();
        }
    }
/* update account management */

/* delete account management */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['collection_id'];

        $delete_query = "DELETE FROM collection_management_tbl WHERE collection_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/collection_management.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/collection_management.php');
        }
    }
/* delete account management */