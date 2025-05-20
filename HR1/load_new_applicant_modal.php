<?php
include 'admin/db_connect.php';
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
  <!-- New Applicant Modal Content -->
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
              <option value="<?= $pos['position_id'] ?>"><?= htmlspecialchars($pos['position_name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Last Name -->
        <div class="col-md-6">
          <label for="lastname" class="form-label">Last Name</label>
          <input type="text" name="lastname" id="lastname" class="form-control" required>
        </div>

        <!-- First Name -->
        <div class="col-md-6">
          <label for="firstname" class="form-label">First Name</label>
          <input type="text" name="firstname" id="firstname" class="form-control" required>
        </div>

        <!-- Middle Name -->
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
          <label for="email" class="form-label">Email Address</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <!-- Contact Number -->
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
          <label for="cover_letter" class="form-label">Cover Letter <span class="text-muted">(Optional)</span></label>
          <textarea name="cover_letter" id="cover_letter" class="form-control" rows="3"></textarea>
        </div>

        <!-- Resume Upload -->
        <div class="col-md-12">
          <label for="resume" class="form-label">Upload Resume (PDF/DOC)</label>
          <input type="file" name="resume" id="resume" class="form-control" accept=".pdf,.doc,.docx" required>
        </div>

      </div> <!-- /.row -->
    </div> <!-- /.modal-body -->

    <div class="modal-footer">
      <button type="submit" class="btn btn-success">Submit Application</button>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
  </form>



<script>
  $(document).on('click', '.apply-btn', function () {
    var positionId = $(this).data('position-id');

    $.ajax({
      url: 'load_new_applicant_modal.php',
      type: 'GET',
      data: { position_id: positionId },
      success: function (data) {
        $('#applyModalContent').html(data); // load content
        var modal = new bootstrap.Modal(document.getElementById('applyModal'), {});
        modal.show(); // show modal

        // Rebind dismiss buttons manually if needed
        $('#applyModalContent').find('[data-bs-dismiss="modal"]').each(function () {
          $(this).on('click', function () {
            modal.hide();
          });
        });
      }
    });
  });
</script>


</body>
</html>