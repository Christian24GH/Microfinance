<?php
include 'db_connect.php';
if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $delete = $conn->query("DELETE FROM application WHERE id = $id");
  echo $delete ? 'success' : 'error';
}
?>
