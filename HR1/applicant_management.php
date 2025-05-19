<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

$i = 1;
$applicants = $conn->query("
  SELECT 
    a.*, 
    p.position_name, 
    r.status_label 
  FROM application a
  LEFT JOIN positions p ON a.position_id = p.position_id
  LEFT JOIN recruitment_status r ON a.process_id = r.id
  ORDER BY a.date_created DESC
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Applicant Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

  <div class="container py-5">
    <h2 class="mb-4">Applicant List</h2>

    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <!-- Search bar on the left -->
          <div style="width: 300px;">
            <input type="text" id="searchInput" class="form-control" placeholder="Search applicants...">
          </div>

          <!-- New Applicant button on the right -->
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newApplicantModal">
            + New Applicant
          </button>
        </div>

        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th class="text-center align-middle" style="width: 50px;">#</th>
              <th class="text-center align-middle" style="width: 40%;">Applicant Information</th>
              <th class="text-center align-middle" style="width: 100px;">Status</th>
              <th class="text-center align-middle" style="width: 180px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($applicants && $applicants->num_rows > 0): ?>
              <?php while ($row = $applicants->fetch_assoc()): ?>
                <tr>
                  <td class="text-center align-middle"><?= $i++ ?></td>
                  <td class="align-middle">
                    <p class="mb-1"><strong>Name:</strong> <?= $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'] ?></p>
                    <p class="mb-0"><strong>Applied for:</strong> <?= $row['position_name'] ?></p>
                  </td>
                  <td class="text-center align-middle">
                    <?php if (!empty($row['status_label'])): ?>
                      <span class="badge bg-primary"><?= htmlspecialchars($row['status_label']) ?></span>
                    <?php else: ?>
                      <span class="badge bg-secondary">New</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center align-middle">
                    <button class="btn btn-sm btn-info viewBtn" data-id="<?= $row['id'] ?>">View</button>
                    <button class="btn btn-sm btn-warning editBtn" data-id="<?= $row['id'] ?>">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $row['id'] ?>">Delete</button>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center align-middle">No applicants found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- + New Applicant Modal -->
  <div class="modal fade" id="newApplicantModal" tabindex="-1" aria-labelledby="newApplicantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Large modal -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newApplicantModalLabel">New Applicant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form action="save_applicant.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row g-3">
              <!-- Position -->
              <div class="col-md-6">
                <label for="position_id" class="form-label">Position</label>
                <select name="position_id" id="position_id" class="form-select" required>
                  <option value="" selected disabled>Select Position</option>
                  <?php
                    $positions = $conn->query("SELECT * FROM positions");
                    while ($pos = $positions->fetch_assoc()):
                  ?>
                    <option value="<?= $pos['position_id'] ?>"><?= $pos['position_name'] ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <!-- Lastname -->
              <div class="col-md-6">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" name="lastname" id="lastname" class="form-control" required>
              </div>

              <!-- Firstname -->
              <div class="col-md-6">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" name="firstname" id="firstname" class="form-control" required>
              </div>

              <!-- Middlename -->
              <div class="col-md-6">
                <label for="middlename" class="form-label">Middle Name</label>
                <input type="text" name="middlename" id="middlename" class="form-control">
              </div>

              <!-- Gender -->
              <div class="col-md-6">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select" required>
                  <option value="" disabled selected>Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>

              <!-- Email -->
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
              </div>

              <!-- Contact -->
              <div class="col-md-6">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" name="contact" id="contact" class="form-control" required>
              </div>

              <!-- Address -->
              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" rows="2" required></textarea>
              </div>

              <!-- Cover Letter -->
              <div class="col-12">
                <label for="cover_letter" class="form-label">Cover Letter (Optional)</label>
                <textarea name="cover_letter" id="cover_letter" class="form-control" rows="3"></textarea>
              </div>

              <!-- Resume Upload -->
              <div class="col-md-12">
                <label for="resume" class="form-label">Upload Resume (PDF/DOC)</label>
                <input type="file" name="resume" id="resume" class="form-control" accept=".pdf,.doc,.docx" required>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="viewApplicantModal" tabindex="-1" aria-labelledby="viewApplicantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewApplicantModalLabel">View Applicant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="viewApplicantContent">
          <!-- Content loaded via AJAX -->
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editApplicantModal" tabindex="-1" aria-labelledby="editApplicantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="editApplicantForm" enctype="multipart/form-data" method="POST" action="update_applicant.php">
          <div class="modal-header">
            <h5 class="modal-title" id="editApplicantModalLabel">Edit Applicant</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="editApplicantContent">
            <!-- Edit form loaded -->
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
  // View
  $('.viewBtn').click(function () {
    const id = $(this).data('id');
    $.get('view_applicant.php', { id }, function (data) {
      $('#viewApplicantContent').html(data);
      var viewModal = new bootstrap.Modal(document.getElementById('viewApplicantModal'));
      viewModal.show();
    });
  });

  // Edit
  $('.editBtn').click(function () {
    const id = $(this).data('id');
    $.get('edit_applicant_form.php', { id }, function (data) {
      $('#editApplicantContent').html(data);
      var editModal = new bootstrap.Modal(document.getElementById('editApplicantModal'));
      editModal.show();
    });
  });

  // Delete
  $('.deleteBtn').click(function () {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to delete this applicant?')) {
      $.post('delete_applicant.php', { id }, function (res) {
        if (res === 'success') {
          alert('Applicant deleted successfully.');
          location.reload();
        } else {
          alert('Failed to delete applicant.');
        }
      });
    }
  });
});
</script>



</body>
</html>
