<?php
    include __DIR__.'/session.php';
?>
<?php
// Database connection settings
$host = 'localhost';
$user = 'root';       // Replace with your DB username
$password = '';       // Replace with your DB password
$database = 'microfinance';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query client_info table
$sql = "SELECT * FROM client_info";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Performance Monitoring</title>
    <link href="./resources/css/app.css" rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --mfc1: #4bc5ec;
            --mfc2: #94dcf4;
            --mfc3: #2c3c8c;
            --mfc4: #5cbc9c;
            --mfc5: #bdccdc;
            --mfc6: #353c61;
            --mfc7: #272c47;
            --mfc8: white;
        }

        body {
            background-color: var(--mfc5);
        }

        .card-mfc1 { background-color: var(--mfc1); color: var(--mfc8); }
        .card-mfc2 { background-color: var(--mfc2); color: var(--mfc8); }
        .card-mfc3 { background-color: var(--mfc3); color: var(--mfc8); }
        .card-mfc4 { background-color: var(--mfc4); color: var(--mfc8); }

        .table-header-custom {
            background-color: var(--mfc3);
            color: var(--mfc8);
        }

        .card-shadow {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="relative">
    <?php 
        include __DIR__.'/components/sidebar.php';
    ?>
    <div id="main" class="visually-hidden">
        <?php 
            include __DIR__.'/components/header.php';
        ?>
   <div class="container my-5 p-4 bg-white rounded shadow">
  <h3 class="mb-4">Social Performance</h3>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center">
      <thead class="table-light">
      <tr>
        <th>Client ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Loan Status</th>
        <th>Actions</th>
      </tr>
    </thead>
  <?php
  if ($result->num_rows > 0):
    while($row = $result->fetch_assoc()): 
      $client_id = htmlspecialchars($row['client_id']);
      $firstname = htmlspecialchars($row['firstname']);
      $lastname = htmlspecialchars($row['lastname']);
      $contact = htmlspecialchars($row['contact']);
      $email = htmlspecialchars($row['email']);
      $loan_stat = htmlspecialchars($row['loan_stat']); // Changed from $status
?>
  <tr>
    <td><?= $client_id ?></td>
    <td><?= $firstname ?></td>
    <td><?= $lastname ?></td>
    <td><?= $contact ?></td>
    <td><?= $email ?></td>
    <td><?= $loan_stat ?></td>
    <td>
      <!-- View Loan Button -->
      <button 
        type="button"
        class="btn btn-outline-dark btn-sm viewLoanBtn" 
        data-client_id="<?= $client_id ?>" 
        data-bs-toggle="modal" 
        data-bs-target="#viewModal"
      >
        <i class="bi bi-eye-fill"></i>
      </button>

      <!-- Edit Button -->
      <button 
        class="btn btn-outline-dark btn-sm" 
        data-bs-toggle="modal" 
        data-bs-target="#editModal<?= $client_id ?>" 
        title="Edit">
        <i class="bi bi-pencil-fill"></i>
      </button>
    </td>
  </tr>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal<?= $client_id ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $client_id ?>" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="config/edit_status.php">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel<?= $client_id ?>">Edit Client <?= $client_id ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="client_id" value="<?= $client_id ?>">
            <div class="mb-3">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control" value="<?= $firstname ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control" value="<?= $lastname ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Contact</label>
              <input type="text" class="form-control" value="<?= $contact ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" value="<?= $email ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Loan Status</label>
              <select class="form-select" name="status" required>
                <option value="Active" <?= ($loan_stat === 'Active') ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= ($loan_stat === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                <option value="Flagged" <?= ($loan_stat === 'Flagged') ? 'selected' : '' ?>>Flagged</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </form>
    </div>
  </div>

<?php endwhile; else: ?>
  <tr>
    <td colspan="7" class="text-center">No clients found.</td>
  </tr>
<?php endif; ?>


<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">Loan Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="loadingSpinner" style="display:none;">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <div id="loanInfoContent">
          <!-- Loan info will be loaded here dynamically -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JAVASCRIPT -->
<script>
document.querySelectorAll('.viewLoanBtn').forEach(button => {
  button.addEventListener('click', (e) => {
    e.preventDefault(); // Stop default button behavior

    const clientId = button.dataset.client_id;
    const loanInfoContent = document.getElementById('loanInfoContent');
    const loadingSpinner = document.getElementById('loadingSpinner');

    loanInfoContent.innerHTML = '';      // Clear previous content
    loadingSpinner.style.display = 'block'; // Show loading spinner

    fetch(`config/fetch_loan_info.php?client_id=${clientId}`)
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
      })
      .then(data => {
        loadingSpinner.style.display = 'none';  // Hide loading
        loanInfoContent.innerHTML = data;        // Insert fetched loan info HTML
      })
      .catch(error => {
        loadingSpinner.style.display = 'none';
        loanInfoContent.innerHTML = `<p class="text-danger">Failed to load loan info: ${error.message}</p>`;
      });
  });
});
</script>


    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
