<?php
require_once("gl_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM general_ledger_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>