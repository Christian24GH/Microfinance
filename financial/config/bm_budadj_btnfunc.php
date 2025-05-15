<?php
require_once("bm_budadj_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM bm_budget_adjustment_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>