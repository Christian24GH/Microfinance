<?php
$connections = mysqli_connect("localhost:3306", "root", "", "microfinance");
if (mysqli_connect_errno()) {

    echo "Failed to connect in mySQL:", mysqli_connect_error();
} else {
    echo "";
}
/* loan_tbl*/
 if (isset($_POST['click_edit_btn'])) {
    $id = $_POST['loan_id'];
    $arrayresult = [];

    $query = "SELECT * FROM disb_loan_tbl WHERE loan_id = '$id'";
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
/* loan_tbl */
?>