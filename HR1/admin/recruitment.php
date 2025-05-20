<?php include('db_connect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Vacancy List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css">

  <style>
    .job-description {
      font-size: 0.875rem;
      color: #555; 
    }
    .job-description-content {
      max-height: 150px;
      overflow-y: auto;
    }
  </style>

</head>
<body>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Job Vacancy List</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newVacancyModal">
                <i class="fa fa-plus"></i> New Vacancy
            </button>
      </div>
      <div class="card-body">
      <!-- Search bar -->
      <div class="mb-3 d-flex justify-content-between align-items-center">
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Search job name or description...">
      </div>
      <!-- Table -->
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th class="text-center" style="width: 45%;">Job Information</th>
              <th class="text-center">Availability</th>
              <th class="text-center">Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              include 'db_connect.php';
              $i = 1;
              $vacancies = $conn->query("SELECT * FROM vacancy ORDER BY id ASC");
              while ($row = $vacancies->fetch_assoc()):
              ?>
              <tr data-search="<?= strtolower($row['position'] . ' ' . strip_tags($row['description'])) ?>">
                <td class="text-center align-middle"><?= $i++ ?></td>
                <td class="align-middle">
                  <p><b>Job Name:</b> <strong><?= htmlspecialchars($row['position']) ?></strong></p>
                  <div class="job-description">
                    <p><b>Job Description:</b></p>
                    <div class="job-description-content">
                      <?= html_entity_decode($row['description']) ?>
                    </div>
                  </div>
                </td>
                <td class="text-center align-middle"><?= $row['availability'] ?></td>
                <td class="text-center">
                  <?php if ($row['status'] == 1): ?>
                    <span class="badge bg-success">Active</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Inactive</span>
                  <?php endif; ?>
                </td>
                <td class="text-center align-middle">
                  <button class="btn btn-sm btn-primary viewBtn" data-id="<?= $row['id'] ?>">View</button>
                  <button class="btn btn-sm btn-warning editBtn" data-id="<?= $row['id'] ?>">Edit</button>
                  <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $row['id'] ?>">Delete</button>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<!-- New Job Modal -->
<div class="modal fade" id="newVacancyModal" tabindex="-1" aria-labelledby="newVacancyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="vacancyForm" method="POST" action="save_vacancy.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="newVacancyModalLabel">New Job Vacancy</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
          <div class="mb-3">
            <label for="job_name" class="form-label">Job Name</label>
            <input type="text" class="form-control" id="job_name" name="job_name" required>
          </div>
          
          <div class="mb-3">
            <label for="availability" class="form-label">Availability</label>
            <input type="number" class="form-control" id="availability" name="availability" min="1" required>
          </div>
          
          <div class="mb-3">
            <label for="description" class="form-label">Job Description</label>
            <textarea class="form-control jqte-editor" id="description" name="description" rows="5"></textarea>
          </div>
        </div>
        
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Vacancy Modal -->
<div class="modal fade" id="vacancyModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vacancyModalLabel">Job Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".jqte-editor").jqte();

            $('#vacancyForm').on('submit', function (e) {
            
            
            const formData = $(this).serialize();
            $.post("save_vacancy.php", formData, function(response) {
                if (response == 1) {
                alert("Vacancy added successfully!");
                location.reload();
                } else {
                alert("Failed to save vacancy.");
                }
            });
            });
        });

    $(document).ready(function () {
      // View
      $('.viewBtn').click(function () {
        let id = $(this).data('id');
        $('#vacancyModal .modal-title').text('View Vacancy');
        $('#vacancyModal .modal-body').load('manage_vacancy.php?id=' + id + '&view=1', function () {
          $('#vacancyModal').modal('show');
          $('.text-jqte').jqte({ readonly: true }); // Disable editing
          $('#manage-vacancy :input').prop('disabled', true); // Disable form fields
        });
      });

      // Edit
      $('.editBtn').click(function () {
        let id = $(this).data('id');
        $('#vacancyModal .modal-title').text('Edit Vacancy');
        $('#vacancyModal .modal-body').load('manage_vacancy.php?id=' + id, function () {
          $('#vacancyModal').modal('show');
          $('.text-jqte').jqte();
        });
      });

      // Delete
      $('.deleteBtn').click(function () {
        let id = $(this).data('id');
        if (confirm("Are you sure you want to delete this vacancy?")) {
          $.ajax({
            url: 'ajax.php?action=delete_vacancy',
            method: 'POST',
            data: { id: id },
            success: function (resp) {
              if (resp == 1) {
                alert("Vacancy successfully deleted.");
                location.reload();
              } else {
                alert("Failed to delete.");
              }
            }
          });
        }
      });
    });

    document.getElementById("searchInput").addEventListener("input", function () {
      const keyword = this.value.toLowerCase();
      const rows = document.querySelectorAll("tbody tr");

      rows.forEach(row => {
        const searchData = row.getAttribute("data-search");
        if (searchData.includes(keyword)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
    </script>
</body>
</html>
