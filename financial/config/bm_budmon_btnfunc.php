<?php
require_once("bm_budmon_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM bm_budget_monitoring_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>