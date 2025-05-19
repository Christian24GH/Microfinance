<?php
require_once("ap_funder_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM ap_funding_source_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>