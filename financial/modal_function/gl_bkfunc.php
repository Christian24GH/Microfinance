<?php
session_start();
include("../config/gl_book_keeper_conn.php");

/* update book keeper */
    if (isset($_POST['update_info'])) {
        $id = $_POST['bookkeeper_id'];
        $fname = $_POST['fname'];
        $age = $_POST['age'];
        $bday = $_POST['bday'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];

        $existing_data_query = "SELECT * FROM gl_book_keeper_tbl WHERE bookkeeper_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['fname'] == $fname && $existing_data['age'] == $age &&
            $existing_data['bday'] == $bday && $existing_data['address'] == $address && $existing_data['contact'] == $contact && $existing_data['email'] == $email
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_book_keeper.php');
            exit();
        }

        $sql = "UPDATE gl_book_keeper_tbl SET fname=?, age=?, bday=?, address=?, contact=?, email=? WHERE bookkeeper_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $fname, $age, $bday, $address, $contact, $email, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_book_keeper.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_book_keeper.php');
            exit();
        }
    }
/* update book keeper*/

/* delete book keeper */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['bookkeeper_id'];

        $delete_query = "DELETE FROM gl_book_keeper_tbl WHERE bookkeeper_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/gl_book_keeper.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/gl_book_keeper.php');
        }
    }
/* delete book keeper */