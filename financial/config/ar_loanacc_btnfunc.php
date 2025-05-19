<?php
require_once("ar_loanacc_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM ar_loan_account_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>