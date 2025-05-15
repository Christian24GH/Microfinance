<?php
session_start();
include("../config/col_accpay_conn.php");

/* update payment */
    if (isset($_POST['update_info'])) {
        $id = $_POST['payment_id'];
        $amountPaid = $_POST['amountPaid'];
        $paymentMethod = $_POST['paymentMethod'];
        $date = $_POST['date'];
        $existing_data_query = "SELECT * FROM collection_account_payment_tbl WHERE payment_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        if (
            $existing_data['amountPaid'] == $amountPaid && $existing_data['paymentMethod'] == $paymentMethod
            && $existing_data['date'] == $date
        ) {
            $_SESSION['status'] = array(
                'message' => "No changes were made.",
                'class' => 'text-danger'
            );
            header('location: ../financial/col_account_payment.php');
            exit();
        }

        $sql = "UPDATE collection_account_payment_tbl SET amountPaid=?, paymentMethod=?, date=? WHERE payment_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $amountPaid, $paymentMethod, $date, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/col_account_payment.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failde to updated the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/col_account_payment.php');
            exit();
        }
    }
/* update payment */

/* delete payment */
    if (isset($_POST['confirm_delete_btn'])) {
        $id = $_POST['payment_id'];

        $delete_query = "DELETE FROM collection_account_payment_tbl WHERE payment_id = '$id'";
        $delete_query_run = mysqli_query($connections, $delete_query);

        if ($delete_query_run) {
            $_SESSION['status'] = array(
                'message' => "You've successfully deleted the request.",
                'class' => 'text-success'
            );
            header('location: ../financial/col_account_payment.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to delete the request.",
                'class' => 'text-danger'
            );
            header('location: ../financial/col_account_payment.php');
        }
    }
/* delete payment */

/* repay */
    if (isset($_POST['repay'])) {
        $loan_id = $_POST['loan_id'];
        $amountPaid = $_POST['amountPaid'];
        $repaymentDate = $_POST['repaymentDate'];
        $paymentMethod = $_POST['paymentMethod'];

        // Insert the repayment record
        $sql = "INSERT INTO disb_repayment_tbl (amountPaid, repaymentDate, loan_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $amountPaid, $repaymentDate, $loan_id);

        // Insert into ar_repayment_management_tbl (make sure columns exist!)
        $disbtrack_sql = "INSERT INTO ar_repayment_management_tbl (amountPaid, paymentDate, fk_loan_id) VALUES (?, ?, ?)";
        $dis_stmt = mysqli_prepare($connections, $disbtrack_sql);
        mysqli_stmt_bind_param($dis_stmt, "sss", $amountPaid, $repaymentDate, $loan_id);
        mysqli_stmt_execute($dis_stmt);

        $fk_loan_id = mysqli_insert_id($connections); // ar_repayment_management_tbl id

        // Insert into collection_account_payment_tbl using the ar_repayment ID
        $accpay_sql = "INSERT INTO collection_account_payment_tbl (paymentMethod, amountPaid, date, fk_loan_id) VALUES (?, ?, ?, ?)";
        $collection_stmt = mysqli_prepare($connections, $accpay_sql);
        mysqli_stmt_bind_param($collection_stmt, "ssss", $paymentMethod, $amountPaid, $repaymentDate, $fk_loan_id);

        // Execute the statement
        mysqli_stmt_execute($collection_stmt);

    if (mysqli_stmt_execute($stmt)) {
        // Auto-update the loan's remaining amount
        $update_sql = "UPDATE disb_loan_tbl SET amount = amount - ? WHERE loan_id = ?";
        $update_stmt = mysqli_prepare($connections, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ds", $amountPaid, $loan_id);
        
        // Execute the update for the loan amount
        if (mysqli_stmt_execute($update_stmt)) {
            
            // Update the outstanding amount in collection_ar_tbl
            $update_collection_ar_sql = "UPDATE collection_ar_tbl SET outstandingAmount = outstandingAmount - ? WHERE disb_loan_id = ?";
            $update_collection_ar_stmt = mysqli_prepare($connections, $update_collection_ar_sql);
            mysqli_stmt_bind_param($update_collection_ar_stmt, "ds", $amountPaid, $loan_id);

            // Execute the update for the outstanding amount
            if (mysqli_stmt_execute($update_collection_ar_stmt)) {
                $_SESSION['status'] = array(
                    'message' => "Repayment successful and loan updated.",
                    'class' => 'text-success'
                );
            } else {
                $_SESSION['status'] = array(
                    'message' => "Repayment inserted but loan failed to update outstanding amount.",
                    'class' => 'text-warning'
                );
            }
        } else {
            $_SESSION['status'] = array(
                'message' => "Repayment inserted but loan failed to update.",
                'class' => 'text-warning'
            );
        }
    } else {
        $_SESSION['status'] = array(
            'message' => "You've failed to update.",
            'class' => 'text-danger'
        );
    }
    header('location: ../financial/col_account_payment.php');
        exit();
    }
/* repay */

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

            // Update the loan amount in disb_loan_tbl
            $update_loan_query = "UPDATE disb_loan_tbl SET amount = amount - '$amountPaid' WHERE loan_id = '$loan_id'";
            $update_loan_result = mysqli_query($connections, $update_loan_query);

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