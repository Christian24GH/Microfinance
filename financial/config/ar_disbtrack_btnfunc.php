<?php
require_once("ar_disbtrack_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM ar_disb_tracking_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>