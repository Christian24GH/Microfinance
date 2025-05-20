<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $q = $conn->query("SELECT a.*, p.position_name FROM application a JOIN positions p ON a.position_id = p.position_id WHERE a.id = $id");
  $app = $q->fetch_assoc();
  if ($app):
?>
<div>
  <p><strong>Name:</strong> <?= $app['lastname'] . ', ' . $app['firstname'] . ' ' . $app['middlename'] ?></p>
  <p><strong>Gender:</strong> <?= $app['gender'] ?></p>
  <p><strong>Email:</strong> <?= $app['email'] ?></p>
  <p><strong>Contact:</strong> <?= $app['contact'] ?></p>
  <p><strong>Address:</strong> <?= $app['address'] ?></p>
  <p><strong>Cover Letter:</strong> <?= nl2br($app['cover_letter']) ?></p>
  <p><strong>Applied For:</strong> <?= $app['position_name'] ?></p>
  <p><strong>Resume:</strong> <a href="<?= $app['resume_path'] ?>" target="_blank">Download</a></p>
</div>
<?php
  else:
    echo "<p>Applicant not found.</p>";
  endif;
}
?>
