<?php
session_start();
include("../config/gl_statement_conn.php");

/* update statement */
    if (isset($_POST['update_info'])) {
        $id = $_POST['statement_id'];
        $gain= $_POST['gain'];
        $expenses = $_POST['expenses'];
        $revenue = $_POST['revenue'];
        $loss = $_POST['loss'];

        $existing_data_query = "SELECT * FROM gl_statement_tbl WHERE statement_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['gain'] == $gain && $existing_data['expenses'] == $expenses &&
            $existing_data['revenue'] == $revenue && $existing_data['loss'] == $loss
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_statement.php');
            exit();
        }

        $sql = "UPDATE gl_statement_tbl SET gain=?, expenses=?, revenue=?, loss=? WHERE statement_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $gain, $expenses, $revenue, $loss, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_statement.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_statement.php');
            exit();
        }
    }
/* update statement */

/* delete statement */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['statement_id'];

        $delete_query = "DELETE FROM gl_statement_tbl WHERE statement_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_statement.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_statement.php');
        }
    }
/* delete statement */