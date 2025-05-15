<?php
session_start();
include("../config/ap_fundsource_conn.php");

/* update funding source */
    if (isset($_POST['update_info'])) {
        $id = $_POST['source_id'];
        $sourceName = $_POST['sourceName'];
        $contact = $_POST['contact'];
        $contractTerms = $_POST['contractTerms'];

        $existing_data_query = "SELECT * FROM ap_funding_source_tbl WHERE source_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['sourceName'] == $sourceName && $existing_data['contact'] == $contact
            && $existing_data['contractTerms'] == $contractTerms
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ap_funding_source.php');
            exit();
        }

        $sql = "UPDATE ap_funding_source_tbl SET sourceName=?, contact=?, contractTerms=?  WHERE source_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $sourceName, $contact, $contractTerms, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/ap_funding_source.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ap_funding_source.php');
            exit();
        }
    }
/* update funder repayment */

/* delete funder repayment */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['source_id'];

        $delete_query = "DELETE FROM ap_funding_source_tbl WHERE source_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/ap_funding_source.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/ap_funding_source.php');
        }
    }
/* delete funder repayment */