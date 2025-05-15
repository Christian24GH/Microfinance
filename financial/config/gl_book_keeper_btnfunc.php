<?php
require_once("gl_book_keeper_conn.php");

function display_data()
{
    global $connections;
    $query = "SELECT * FROM gl_book_keeper_tbl";
    $result = mysqli_query($connections, $query);
    return $result;
}
?>