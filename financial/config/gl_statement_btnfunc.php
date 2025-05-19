<?php
require_once("gl_statement_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM gl_statement_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>