<?php
require_once("disb_repayment_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM disb_repayment_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>