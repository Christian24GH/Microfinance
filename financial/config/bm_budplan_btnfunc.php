<?php
require_once("bm_budplan_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM bm_budget_planning_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>