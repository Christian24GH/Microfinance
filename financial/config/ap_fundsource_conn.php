<?php
$connections = mysqli_connect("localhost:3306", "root", "", "microfinance");
if (mysqli_connect_errno()) {

    echo "Failed to connect in mySQL:", mysqli_connect_error();
} else {
    echo "";
}
/* funding_source_tbl*/
 if (isset($_POST['click_edit_btn'])) {
    $id = $_POST['source_id'];
    $arrayresult = [];

    $query = "SELECT * FROM ap_funding_source_tbl WHERE source_id = '$id'";
    $query_run = mysqli_query($connections, $query);

    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_array($query_run)) {
            array_push($arrayresult, $row);
            header('content-type: application/json');
            echo json_encode($arrayresult);
        }
    } else {
        echo '<h4>No record found.</h4>';
    }
}
/* funding_source_tbl */
?>