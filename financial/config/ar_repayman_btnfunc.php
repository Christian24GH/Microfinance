<?php
require_once("ar_repayman_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM ar_repayment_management_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>