<?php 
  include('db_connect.php');

  // Get only hired employees
  $employees = $conn->query("
  SELECT a.id, CONCAT(a.firstname, ' ', a.lastname) AS full_name
  FROM application a
  JOIN recruitment_status rs ON a.process_id = rs.id
  WHERE rs.status_label = 'Hired'
");

  // Fetch recognitions
  $recognitions = $conn->query("
  SELECT r.*, 
         CONCAT(a.firstname, ' ', a.lastname) AS receiver_name,
         e.full_name AS sender_name
  FROM social_recognitions r
  LEFT JOIN application a ON r.receiver_id = a.id
  LEFT JOIN employee_users e ON r.sender_id = e.id
  ORDER BY r.date_created DESC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Social Recognition</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
  <div class="row g-4">

    <!-- Recognition Feed -->
    <div class="card p-4 mb-4 shadow-sm col-md-8">
      <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Recognition Wall</h5>
          <input type="text" class="form-control w-50" placeholder="Search recognitions...">
        </div>
        <div class="row g-3">
          <!-- Recognition Card -->
          <?php while($row = $recognitions->fetch_assoc()): ?>
            <div class="col-12">
              <div class="card shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <h6 class="card-title mb-1">
                        <?= htmlspecialchars($row['receiver_name']) ?> - 
                        <span class="text-success"><?= htmlspecialchars($row['recognition_type']) ?></span>
                      </h6>
                      <p class="card-text mb-1"><?= htmlspecialchars($row['message']) ?></p>
                      <?php if ($row['badge_path']): ?>
                        <img src="<?= $row['badge_path'] ?>" class="img-fluid rounded mb-2" style="max-width: 150px;" />
                      <?php endif; ?>
                      <small class="text-muted"><?= date('F Y', strtotime($row['date_created'])) ?></small>
                    </div>
                    <div>
                      <!-- Edit Button triggers modal -->
                      <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>

                      <!-- Delete form -->
                      <form action="delete_recognition.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this recognition?');">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
              <div class="modal-dialog">
                <form action="update_recognition.php" method="POST" enctype="multipart/form-data" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Recognition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                    <div class="mb-3">
                      <label class="form-label">Recognition Type</label>
                      <input type="text" class="form-control" name="recognition_type" value="<?= htmlspecialchars($row['recognition_type']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Message</label>
                      <textarea class="form-control" name="message" rows="3" required><?= htmlspecialchars($row['message']) ?></textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Change Badge (optional)</label>
                      <input type="file" class="form-control" name="badge">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          <?php endwhile; ?>


        </div>
      </div>
    </div>

    <!-- Submit Recognition Form -->
    <div class="col-md-4">
      <div class="card p-4 shadow-sm">
        <h5 class="mb-3">Submit Recognition</h5>
        <form action="submit_recognition.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Employee</label>
            <select class="form-select" name="employee_id" required>
              <option selected disabled>Select Employee</option>
              <?php while ($emp = $employees->fetch_assoc()): ?>
                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['full_name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Recognition Type</label>
            <input type="text" class="form-control" name="recognition_type" placeholder="e.g. Team Player" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea class="form-control" name="message" rows="3" placeholder="Write your appreciation..." required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Upload Badge (optional)</label>
            <input type="file" class="form-control" name="badge">
          </div>
          <button type="submit" class="btn btn-primary w-100">Submit Recognition</button>
        </form>
      </div>
    </div>


  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
