<?php
session_start();
include("../config/disb_loan_conn.php");

/* update loan */
if (isset($_POST['update_info'])) {
    $id = $_POST['loan_id'];
    $amount = $_POST['amount'];
    $term = $_POST['term'];
    $status = $_POST['status'];

    $existing_data_query = "SELECT * FROM disb_loan_tbl WHERE loan_id = ?";
    $stmt_existing = mysqli_prepare($connections, $existing_data_query);
    mysqli_stmt_bind_param($stmt_existing, "s", $id);
    mysqli_stmt_execute($stmt_existing);
    $existing_data_result = mysqli_stmt_get_result($stmt_existing);
    $existing_data = mysqli_fetch_assoc($existing_data_result);

    if (
        $existing_data['amount'] == $amount &&
        $existing_data['term'] == $term &&
        $existing_data['status'] == $status
    ) {
        $_SESSION['status'] = array(
            'message' => "No changes were made.",
            'class' => 'text-danger'
        );
        header('location: ../financial/disb_loan.php');
        exit();
    }

    // Update disb_loan_tbl
    $sql = "UPDATE disb_loan_tbl SET amount=?, term=?, status=? WHERE loan_id=?";
    $stmt = mysqli_prepare($connections, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $amount, $term, $status, $id);

    // Update ar_loan_account_tbl
    $ar_loanacc_sql = "UPDATE ar_loan_account_tbl SET status=? WHERE fk_loan_id=?";
    $ar_loanacc_stmt = mysqli_prepare($connections, $ar_loanacc_sql);
    mysqli_stmt_bind_param($ar_loanacc_stmt, "ss", $status, $id);

    // Execute both updates
    if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($ar_loanacc_stmt)) {
        $_SESSION['status'] = array(
            'message' => "You've successfully updated the information.",
            'class' => 'text-success'
        );
        header('location: ../financial/disb_loan.php');
    } else {
        $_SESSION['status'] = array(
            'message' => "You've failed to update the information.",
            'class' => 'text-danger'
        );
        header('location: ../financial/disb_loan.php');
        exit();
    }
}
/* update loan */

/* delete loan */
if (isset($_POST['confirm_delete_btn'])) {
    $id = $_POST['loan_id'];

    $delete_query = "DELETE FROM disb_loan_tbl WHERE loan_id = '$id'";
    $delete_query_run = mysqli_query($connections, $delete_query);

    if ($delete_query_run) {
        $_SESSION['status'] = array(
            'message' => "You've successfully deleted the request.",
            'class' => 'text-success'
        );
        header('location: ../financial/disb_loan.php');
    } else {
        $_SESSION['status'] = array(
            'message' => "You've failed to delete the request.",
            'class' => 'text-danger'
        );
        header('location: ../financial/disb_loan.php');
    }
}
/* delete loan */

/* requested loan */
    if (isset($_POST['request_disbursement'])) {
        $borrower_id = $_POST['borrower_id'];
        $amount = $_POST['amount'];
        $interestRate = $_POST['interestRate'];
        $term = $_POST['term'];
        $status = $_POST['status'];

        // Insert into disb_loan_tbl only
        $loan_sql = "INSERT INTO disb_loan_tbl (borrower_id, amount, interestRate, term, status) VALUES (?, ?, ?, ?, ?)";
        $loan_stmt = mysqli_prepare($connections, $loan_sql);
        mysqli_stmt_bind_param($loan_stmt, "sssss", $borrower_id, $amount, $interestRate, $term, $status);

        if (mysqli_stmt_execute($loan_stmt)) {
            $_SESSION['status'] = [
                'message' => "You've successfully requested.",
                'class' => 'text-success'
            ];
        } else {
            $_SESSION['status'] = [
                'message' => "Request failed.",
                'class' => 'text-danger'
            ];
        }

        header('location: ../financial/disb_loan.php');
        exit();
    }
/* requested loan */

/* approve loan */
    if (isset($_POST['approve_loan'])) {
        $loan_id = $_POST['loan_id'];
        $disbursementDate = date('Y-m-d');
        $status = 'approved';

        mysqli_begin_transaction($connections);

        try {
            // Retrieve loan details including interestRate
            $loan_query = "SELECT borrower_id, amount, term, interestRate FROM disb_loan_tbl WHERE loan_id = ?";
            $loan_stmt = mysqli_prepare($connections, $loan_query);
            mysqli_stmt_bind_param($loan_stmt, "s", $loan_id);
            mysqli_stmt_execute($loan_stmt);
            $loan_result = mysqli_stmt_get_result($loan_stmt);
            $loan_data = mysqli_fetch_assoc($loan_result);

            $borrower_id = $loan_data['borrower_id'];
            $amount = $loan_data['amount'];
            $term = $loan_data['term'];
            $interestRate = $loan_data['interestRate']; // now reliably fetched from DB


            // Update loan status in disb_loan_tbl
            $update_loan_sql = "UPDATE disb_loan_tbl SET status = ? WHERE loan_id = ?";
            $update_loan_stmt = mysqli_prepare($connections, $update_loan_sql);
            mysqli_stmt_bind_param($update_loan_stmt, "ss", $status, $loan_id);
            mysqli_stmt_execute($update_loan_stmt);

            // Insert into disbursement_tbl
            $disbursement_sql = "INSERT INTO disbursement_tbl (loan_id, amount, disbursementDate) VALUES (?, ?, ?)";
            $dis_stmt = mysqli_prepare($connections, $disbursement_sql);
            mysqli_stmt_bind_param($dis_stmt, "sss", $loan_id, $amount, $disbursementDate);
            mysqli_stmt_execute($dis_stmt);

            // Insert into ar_loan_account_tbl
            $loanacc_sql = "INSERT INTO ar_loan_account_tbl (fk_loan_id, initialAmount, interestRate, status, disbursementDate) VALUES (?, ?, ?, ?, ?)";
            $loanacc_stmt = mysqli_prepare($connections, $loanacc_sql);
            mysqli_stmt_bind_param($loanacc_stmt, "sssss", $loan_id, $amount, $interestRate, $status, $disbursementDate);
            mysqli_stmt_execute($loanacc_stmt);

            // Get the auto-generated loanacc_id
            $loanacc_id = mysqli_insert_id($connections);

            // Insert into ar_disb_tracking_tbl
            $disbtrack_sql = "INSERT INTO ar_disb_tracking_tbl (loan_id, amount, transactionDate) VALUES (?, ?, ?)";
            $disbtrack_stmt = mysqli_prepare($connections, $disbtrack_sql);
            mysqli_stmt_bind_param($disbtrack_stmt, "sss", $loan_id, $amount, $disbursementDate);
            mysqli_stmt_execute($disbtrack_stmt);

            // Insert into collection_ar_tbl
            $collection_sql = "INSERT INTO collection_ar_tbl (loanacc_id, outstandingAmount, transactionDate, disb_loan_id) VALUES (?, ?, ?, ?)";
            $collection_stmt = mysqli_prepare($connections, $collection_sql);
            mysqli_stmt_bind_param($collection_stmt, "ssss", $loanacc_id, $amount, $disbursementDate, $loan_id);
            mysqli_stmt_execute($collection_stmt);

            // Commit the transaction
            mysqli_commit($connections);

            echo json_encode([
                'message' => "Loan approved successfully.",
                'status' => 'success'
            ]);
        } catch (Exception $e) {
            mysqli_rollback($connections);
            echo json_encode([
                'message' => "Approval failed: " . $e->getMessage(),
                'status' => 'error'
            ]);
        }
        exit();
    }
/* approve loan */