<?php
require_once("collman_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM collection_management_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>