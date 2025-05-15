<?php
require_once("col_accpay_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM collection_account_payment_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>