<?php include('db_connect.php');

// Load all vacancies once
$vacancy_result = $conn->query("SELECT * FROM vacancy");
$pos = [];
while ($row = $vacancy_result->fetch_assoc()) {
    $pos[$row['id']] = $row['position'];
}

// Get filters
$pid = isset($_GET['pid']) && $_GET['pid'] >= 0 ? $_GET['pid'] : 'all';
$position_id = isset($_GET['position_id']) && $_GET['position_id'] >= 0 ? $_GET['position_id'] : 'all';
?>

<div class="container-fluid">
  <div class="col-lg-12">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <b><span class="h5">Application List</span></b>
                <button class="btn btn-primary float-end" id="new_application">
                  <i class="fa fa-plus"></i> New Applicant
                </button>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-2 offset-md-2 text-end">Position:</div>
              <div class="col-md-5">
                <select class="custom-select select2" id="position_filter">
                  <option value="all" <?= $position_id == "all" ? "selected" : "" ?>>All</option>
                  <?php foreach($pos as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $position_id == $k ? "selected" : "" ?>><?= $v ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <hr><br>

            <table class="table table-bordered table-hover" id="applicationTable">
              <thead class="thead-dark">
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Applicant Information</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                $stat_arr = [0 => "New"];
                $status_result = $conn->query("SELECT * FROM recruitment_status ORDER BY id ASC");
                while ($row = $status_result->fetch_assoc()) {
                    $stat_arr[$row['id']] = $row['status_label'];
                }

                // Build WHERE clause
                $where = [];
                if ($pid !== 'all') $where[] = "a.process_id = '$pid'";
                if ($position_id !== 'all') $where[] = "a.position_id = '$position_id'";
                $awhere = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

                $applications = $conn->query("SELECT a.*, v.position FROM application a 
                                              INNER JOIN vacancy v ON v.id = a.position_id 
                                              $awhere ORDER BY a.id ASC");

                while ($row = $applications->fetch_assoc()):
                ?>
                <tr>
                  <td class="text-center"><?= $i++ ?></td>
                  <td>
                    <p>Name: <b><?= ucwords("{$row['lastname']}, {$row['firstname']} {$row['middlename']}") ?></b></p>
                    <p>Applied for: <b><?= $row['position'] ?></b></p>
                  </td>
                  <td class="text-center"><?= $stat_arr[$row['process_id']] ?? 'N/A' ?></td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-primary view_application" data-id="<?= $row['id'] ?>">View</button>
                    <button class="btn btn-sm btn-primary edit_application" data-id="<?= $row['id'] ?>">Edit</button>
                    <button class="btn btn-sm btn-danger delete_application" data-id="<?= $row['id'] ?>">Delete</button>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div> <!-- .col-lg-12 -->
    </div> <!-- .row -->
  </div>
</div>

<!-- Custom Modal -->
<div class="modal fade" id="customModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Content loaded dynamically -->
      </div>
    </div>
  </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this application?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>


<!-- Modals (Confirm Delete, New Applicant) -->


<style>
td { vertical-align: middle !important; }
td p { margin: unset; }
img { max-width: 100px; max-height: 150px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const positionFilter = document.getElementById('position_filter');
  const modal = new bootstrap.Modal(document.getElementById('customModal'));
  let deleteId = null;

  // DataTable initialization
  $('#applicationTable').DataTable();

  // Filter position
  positionFilter.addEventListener('change', () => {
    window.location.href = `index.php?page=applications&position_id=${positionFilter.value}&pid=<?= $pid ?>`;
  });

  // Show modal (reusable)
  const showModal = (title, url) => {
    fetch(url)
      .then(res => res.text())
      .then(html => {
        document.querySelector('#customModal .modal-title').innerText = title;
        document.querySelector('#customModal .modal-body').innerHTML = html;
        modal.show();
      });
  };

  // New Application
  document.getElementById("new_application").addEventListener("click", () => {
    showModal("New Application", "manage_application.php");
  });

  // Edit/View Application
  document.querySelectorAll(".edit_application").forEach(btn =>
    btn.addEventListener("click", () =>
      showModal("Edit Application", `manage_application.php?id=${btn.dataset.id}`)
    )
  );

  document.querySelectorAll(".view_application").forEach(btn =>
    btn.addEventListener("click", () =>
      showModal("View Application", `view_application.php?id=${btn.dataset.id}`)
    )
  );

  // Delete Application
  document.querySelectorAll(".delete_application").forEach(btn =>
    btn.addEventListener("click", () => {
      deleteId = btn.dataset.id;
      new bootstrap.Modal(document.getElementById("confirmDeleteModal")).show();
    })
  );

  document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
    if (deleteId) {
      fetch('ajax.php?action=delete_application', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + deleteId
      })
      .then(res => res.text())
      .then(resp => {
        if (resp == 1) location.reload();
      });
    }
  });
});
</script>
