<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "Invalid request";
    exit;
}

$id = intval($_GET['id']);

// Fetch applicant info with prepared statement
$stmt = $conn->prepare("
    SELECT id, position_id, lastname, firstname, middlename, gender, email, contact, 
           address, cover_letter, resume_path, process_id
    FROM application WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Applicant not found";
    exit;
}

$applicant = $result->fetch_assoc();

// Fetch positions for dropdown
$positions = $conn->query("SELECT position_id, position_name FROM positions ORDER BY position_name ASC");

// Fetch recruitment statuses for dropdown (only active ones - status=1)
$status_options = $conn->query("SELECT id, status_label FROM recruitment_status WHERE status = 1 ORDER BY status_label ASC");
?>

<div class="row g-3">
  <input type="hidden" name="id" value="<?= htmlspecialchars($applicant['id']) ?>">

  <!-- Position Dropdown -->
  <div class="col-md-6">
    <label for="position_id" class="form-label">Position</label>
    <select name="position_id" id="position_id" class="form-select" required>
      <option value="" disabled>Select Position</option>
      <?php while ($pos = $positions->fetch_assoc()): ?>
        <option value="<?= $pos['position_id'] ?>" <?= ($pos['position_id'] == $applicant['position_id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($pos['position_name']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <!-- Status Dropdown (mapped from process_id) -->
  <div class="col-md-6">
    <label for="process_id" class="form-label">Status</label>
    <select name="process_id" id="process_id" class="form-select" required>
      <option value="" disabled>Select Status</option>
      <?php while ($status = $status_options->fetch_assoc()): ?>
        <option value="<?= $status['id'] ?>" <?= ($status['id'] == $applicant['process_id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($status['status_label']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <!-- Last Name -->
  <div class="col-md-6">
    <label for="lastname" class="form-label">Last Name</label>
    <input type="text" name="lastname" id="lastname" class="form-control" value="<?= htmlspecialchars($applicant['lastname']) ?>" required>
  </div>

  <!-- First Name -->
  <div class="col-md-6">
    <label for="firstname" class="form-label">First Name</label>
    <input type="text" name="firstname" id="firstname" class="form-control" value="<?= htmlspecialchars($applicant['firstname']) ?>" required>
  </div>

  <!-- Middle Name -->
  <div class="col-md-6">
    <label for="middlename" class="form-label">Middle Name</label>
    <input type="text" name="middlename" id="middlename" class="form-control" value="<?= htmlspecialchars($applicant['middlename']) ?>">
  </div>

  <!-- Gender -->
  <div class="col-md-6">
    <label for="gender" class="form-label">Gender</label>
    <select name="gender" id="gender" class="form-select" required>
      <option value="Male" <?= ($applicant['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
      <option value="Female" <?= ($applicant['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
    </select>
  </div>

  <!-- Email -->
  <div class="col-md-6">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($applicant['email']) ?>" required>
  </div>

  <!-- Contact Number -->
  <div class="col-md-6">
    <label for="contact" class="form-label">Contact Number</label>
    <input type="text" name="contact" id="contact" class="form-control" value="<?= htmlspecialchars($applicant['contact']) ?>" required>
  </div>

  <!-- Address -->
  <div class="col-12">
    <label for="address" class="form-label">Address</label>
    <textarea name="address" id="address" class="form-control" rows="2" required><?= htmlspecialchars($applicant['address']) ?></textarea>
  </div>

  <!-- Cover Letter -->
  <div class="col-12">
    <label for="cover_letter" class="form-label">Cover Letter (Optional)</label>
    <textarea name="cover_letter" id="cover_letter" class="form-control" rows="3"><?= htmlspecialchars($applicant['cover_letter']) ?></textarea>
  </div>

  <!-- Resume Upload -->
  <div class="col-12">
    <label for="resume" class="form-label">Upload Resume (PDF/DOC) – Leave blank if no change</label>
    <input type="file" name="resume" id="resume" class="form-control" accept=".pdf,.doc,.docx">
  </div>
</div>
