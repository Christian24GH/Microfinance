<?php
require_once("ap_liability_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM ap_liability_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>