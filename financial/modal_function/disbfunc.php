<?php
session_start();
include("../config/disb_conn.php");

/* update disbursement */
    if (isset($_POST['update_info'])) {
        $id = $_POST['disbursement_id'];
        $amount = $_POST['amount'];
        $disbursementDate = $_POST['disbursementDate'];

        // Fetch existing data from disbursement_tbl
        $existing_data_query = "SELECT * FROM disbursement_tbl WHERE disbursement_id = ?";
        $stmt_existing = mysqli_prepare($connections, $existing_data_query);
        mysqli_stmt_bind_param($stmt_existing, "s", $id);
        mysqli_stmt_execute($stmt_existing);
        $existing_data_result = mysqli_stmt_get_result($stmt_existing);
        $existing_data = mysqli_fetch_assoc($existing_data_result);

        // Check if any changes are needed
        if (
            $existing_data['amount'] == $amount &&
            $existing_data['disbursementDate'] == $disbursementDate
        ) {
            $_SESSION['status'] = array(
                'message' => "You've failed to update the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/disbursement.php');
            exit();
        }

        // Update disbursement_tbl
        $sql = "UPDATE disbursement_tbl SET amount=?, disbursementDate=? WHERE disbursement_id=?";
        $stmt = mysqli_prepare($connections, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $amount, $disbursementDate, $id);

        // Update ar_loan_account_tbl
        $ar_loanacc_sql = "UPDATE ar_loan_account_tbl SET disbursementDate=? WHERE fk_loan_id=(SELECT fk_loan_id FROM disbursement_tbl WHERE disbursement_id=?)";
        $ar_loanacc_stmt = mysqli_prepare($connections, $ar_loanacc_sql);
        mysqli_stmt_bind_param($ar_loanacc_stmt, "ss", $disbursementDate, $id);

        // Update ar_disb_tracking_tbl
        $ar_disbtrack_sql = "UPDATE ar_disb_tracking_tbl SET transactionDate=? WHERE loan_id=(SELECT loan_id FROM disbursement_tbl WHERE disbursement_id=?)";
        $ar_disbtrack_stmt = mysqli_prepare($connections, $ar_disbtrack_sql);
        mysqli_stmt_bind_param($ar_disbtrack_stmt, "ss", $disbursementDate, $id);

        // Update collection_ar_tbl with JOIN
        $col_ar_sql = "UPDATE collection_ar_tbl AS c
                JOIN ar_loan_account_tbl AS a ON c.loanacc_id = a.loanacc_id
                JOIN disbursement_tbl AS d ON a.fk_loan_id = d.loan_id
                SET c.transactionDate = ?
                WHERE d.disbursement_id = ?";
        $col_ar_stmt = mysqli_prepare($connections, $col_ar_sql);
        mysqli_stmt_bind_param($col_ar_stmt, "si", $disbursementDate, $id);
        mysqli_stmt_execute($col_ar_stmt);

        // Execute all updates
        if (
            mysqli_stmt_execute($stmt) &&
            mysqli_stmt_execute($ar_loanacc_stmt) &&
            mysqli_stmt_execute($ar_disbtrack_stmt) && // Ensure this is executed
            mysqli_stmt_execute($col_ar_stmt)
        ) {
            $_SESSION['status'] = array(
                'message' => "You've successfully updated the information.",
                'class' => 'text-success'
            );
            header('location: ../financial/disbursement.php');
        } else {
            $_SESSION['status'] = array(
                'message' => "You've failed to update the information.",
                'class' => 'text-danger'
            );
            header('location: ../financial/disbursement.php');
            exit();
        }
    }
/* update disbursement */

/* delete disbursement */
if (isset($_POST['confirm_delete_btn'])) {
    $id = $_POST['disbursement_id'];

    $delete_query = "DELETE FROM disbursement_tbl WHERE disbursement_id = '$id'";
    $delete_query_run = mysqli_query($connections, $delete_query);

    if ($delete_query_run) {
        $_SESSION['status'] = array(
            'message' => "You've successfully deleted the request.",
            'class' => 'text-success'
        );
        header('location: ../financial/disbursement.php');
    } else {
        $_SESSION['status'] = array(
            'message' => "You've failed to delete the request.",
            'class' => 'text-danger'
        );
        header('location: ../financial/disbursement.php');
    }
}
/* delete disbursement */