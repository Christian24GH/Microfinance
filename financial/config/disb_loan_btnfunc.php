<?php
require_once("disb_loan_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM disb_loan_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>