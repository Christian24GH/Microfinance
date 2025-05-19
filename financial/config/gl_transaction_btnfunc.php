<?php
require_once("gl_transaction_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM gl_transaction_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>