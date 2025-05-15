<?php
require_once("disb_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM disbursement_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>