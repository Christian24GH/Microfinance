<?php
$host = 'localhost';
$user = 'root';       
$password = '';      
$database = 'microfinance';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if ($search !== '') {
    $sql = "SELECT * FROM client_info 
            WHERE first_name LIKE '%$search%' 
               OR last_name LIKE '%$search%' 
               OR email LIKE '%$search%' 
               OR contact_number LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM client_info";
}

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Now you can use $result to fetch and display results
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
 <div id="main" class="container my-5 p-4 bg-white rounded shadow">
  <h3 class="mb-4">Social Performance</h3>
   <form method="GET" action="" class="mb-4" id="search-form">
    


<form method="GET" action="" class="mb-4" id="search-form">
    <div class="input-group">
        <input
            type="search"
            id="search-input"
            name="search"
            class="form-control"
            placeholder="Search by name, contact, or email"
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
        />
        <button class="btn btn-outline-dark" type="submit" id="search-btn">
            <i class="bi <?= isset($_GET['search']) && $_GET['search'] !== '' ? 'bi-x-circle' : 'bi-search' ?>"></i>
            <span id="search-btn-text"><?= isset($_GET['search']) && $_GET['search'] !== '' ? 'Clear' : 'Search' ?></span>
        </button>
    </div>
</form>

<script>
    document.getElementById('search-btn').addEventListener('click', function(e) {
        const searchInput = document.getElementById('search-input');
        if (searchInput.value !== '') {
            // If the button currently says "Clear", clear input and reload
            if (this.querySelector('#search-btn-text').textContent === 'Clear') {
                e.preventDefault();
                searchInput.value = '';
                // Reload the page without search query params
                window.location.href = window.location.pathname;
            }
            // else let the form submit to perform search
        }
    });
</script>




  <div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
      <thead class="table-header-custom">
        <tr>
          <th>Client ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Contact</th>
          <th>Email</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <?php
              $client_id = htmlspecialchars($row['client_id']);
              $first_name = htmlspecialchars($row['first_name']);
              $last_name = htmlspecialchars($row['last_name']);
              $contact = htmlspecialchars($row['contact_number']);
              $email = htmlspecialchars($row['email']);
              $status = htmlspecialchars($row['status']);
            ?>
            <tr>
              <td><?= $client_id ?></td>
              <td><?= $first_name ?></td>
              <td><?= $last_name ?></td>
              <td><?= $contact ?></td>
              <td><?= $email ?></td>
              <td><?= $status ?></td>
              <td>
                <button class="btn btn-outline-dark viewLoanBtn"
                        data-client-id="<?= $client_id ?>" 
                        data-bs-toggle="modal" 
                        data-bs-target="#viewModal">
                  <i class="bi bi-eye-fill"></i>
                </button>

                <button class="btn btn-outline-dark" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal<?= $client_id ?>">
                  <i class="bi bi-pencil-fill"></i>
                </button>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $client_id ?>" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" action="config/edit_status.php">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Client <?= $client_id ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="client_id" value="<?= $client_id ?>">
                      <div class="mb-3">
                        <label>Loan Status</label>
                        <select class="form-select" name="status" required>
                          <option value="Active" <?= $status == 'Active' ? 'selected' : '' ?>>Active</option>
                          <option value="Inactive" <?= $status == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                          <option value="Flagged" <?= $status == 'Flagged' ? 'selected' : '' ?>>Flagged</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7">No clients found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Loan View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Loan Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="loanInfoContent">Loading...</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.viewLoanBtn').forEach(btn => {
    btn.addEventListener('click', function () {
      const clientId = this.getAttribute('data-client-id');
      const loanInfoContainer = document.getElementById('loanInfoContent');
      loanInfoContainer.innerHTML = "Loading...";

      fetch(`config/loan_info.php?client_id=${clientId}`)
        .then(res => res.text())
        .then(data => {
          loanInfoContainer.innerHTML = data;
        })
        .catch(err => {
          loanInfoContainer.innerHTML = `<div class="text-danger">Error loading loan info.</div>`;
        });
    });
  });
</script>


    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
