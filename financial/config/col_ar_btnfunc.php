<?php
require_once("col_ar_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM collection_ar_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>