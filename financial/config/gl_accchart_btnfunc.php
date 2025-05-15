<?php
require_once("gl_accchart_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM gl_chart_of_account_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>