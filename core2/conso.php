<?php
include __DIR__.'/session.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfinance";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Filtering
$search = $_GET['search'] ?? '';
$interest = $_GET['interest'] ?? '';
$terms = $_GET['terms'] ?? '';

// Main query (loan_info + client_info)
$sql = "
  SELECT 
    loan_info.*, 
    client_info.first_name, 
    client_info.last_name
  FROM loan_info
  JOIN client_info ON loan_info.client_id = client_info.client_id
  WHERE 1
";
// Optional filters
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (client_info.first_name LIKE '%$search%' 
              OR client_info.last_name LIKE '%$search%' 
                 OR client_info.client_id LIKE '%$search%' 
                 OR loan_info.loan_id LIKE '%$search%' 
              OR loan_info.purpose LIKE '%$search%')";
}
if (!empty($interest)) {
    $interest = (int)$interest;  // Cast to int for integer field
    $sql .= " AND loan_info.interest = $interest";
}
if (!empty($terms)) {
    $terms = (int)$terms;  // Cast to int for integer field
    $sql .= " AND loan_info.terms = $terms";
}

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Dropdown filters
$interestRates = $conn->query("SELECT DISTINCT interest FROM loan_info ORDER BY interest");
$termsList = $conn->query("SELECT DISTINCT terms FROM loan_info ORDER BY terms");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Consolidation</title>
  <link href="./resources/css/app.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    :root {
      --mfc1: #4bc5ec;
      --mfc2: #94dcf4;
      --mfc3: #2c3c8c;
      --mfc4: #5cbc9c;
      --mfc5: #d7e1ec;
      --mfc6: #353c61;
      --mfc7: #272c47;
      --mfc8: white;
    }
    body {
      background-color: var(--mfc5);
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/components/sidebar.php'; ?>

  <div id="main">
    <?php include __DIR__ . '/components/header.php'; ?>

    <div class="container my-5 p-4 bg-white rounded shadow">
      <h3 class="mb-4">Consolidation</h3>

      <!-- Search & Filter -->
      <form class="row g-3 mb-4" method="get" id="filterForm">
        <div class="col-md-4">
          <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            class="form-control"
            placeholder="Search client or purpose"
          />
        </div>
        <div class="col-md-3">
          <select name="interest" class="form-select">
            <option value="">All Interest Rates</option>
            <?php while ($i = $interestRates->fetch_assoc()) : ?>
              <option value="<?= $i['interest'] ?>" <?= $i['interest'] == $interest ? 'selected' : '' ?>>
                <?= $i['interest'] ?>%
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-3">
          <select name="terms" class="form-select">
            <option value="">All Terms</option>
            <?php while ($t = $termsList->fetch_assoc()) : ?>
              <option value="<?= $t['terms'] ?>" <?= $t['terms'] == $terms ? 'selected' : '' ?>>
                <?= $t['terms'] ?> months
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-dark w-100"><i class="bi bi-search"></i></button>
        </div>
      </form>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Loan ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Purpose</th>
              <th>Amount</th>
              <th>Terms (months)</th>
              <th>Monthly Payment</th>
              <th>Interest (%)</th>
              <th>Total Payable</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0) : ?>
              <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                  <td><?= $row["loan_id"] ?></td>
                  <td><?= htmlspecialchars($row["first_name"]) ?></td>
                  <td><?= htmlspecialchars($row["last_name"]) ?></td>
                  <td><?= htmlspecialchars($row["purpose"]) ?></td>
                  <td>₱<?= number_format($row["amount"], 2) ?></td>
                  <td><?= $row["terms"] ?></td>
                  <td>₱<?= number_format($row["month"], 2) ?></td>
                  <td><?= $row["interest"] ?>%</td>
                  <td>₱<?= number_format($row["total"], 2) ?></td>
                  <td>
                    <button
                      class="btn btn-sm btn-outline-dark"
                      data-bs-toggle="modal"
                      data-bs-target="#viewModal"
                      onclick="showModalContent('View', <?= $row['loan_id'] ?>)"
                    >
                      <i class="bi bi-eye-fill"></i>
                    </button>
                    <button
                      class="btn btn-sm btn-outline-dark"
                      data-bs-toggle="modal"
                      data-bs-target="#downloadModal"
                      onclick="setLoanId(<?= $row['loan_id'] ?>)"
                    >
                      <i class="bi bi-download"></i>
                    </button>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else : ?>
              <tr>
                <td colspan="10">No loan records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-4">
        <h5 class="modal-title">View Loan</h5>
        <div class="modal-body" id="viewContent"></div>
        <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>

  <!-- Download Modal -->
  <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Download Loan Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Are you sure you want to download the loan data PDF?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="#" id="downloadLink" class="btn btn-success">Download PDF</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function setLoanId(loanId) {
      const downloadLink = document.getElementById('downloadLink');
      downloadLink.href = 'fpdf/download.php?loan_id=' + loanId;
    }

    function showModalContent(action, loanId) {
      const targetId = action === 'View' ? 'viewContent' : 'downloadContent';
      const content = document.getElementById(targetId);

      content.innerHTML = `<p>Loading ${action.toLowerCase()} details...</p>`;

      fetch(`config/loan_action.php?action=${action.toLowerCase()}&loan_id=${loanId}`)
        .then((response) => response.text())
        .then((data) => {
          content.innerHTML = data;
        })
        .catch((error) => {
          content.innerHTML = `<p class="text-danger">Failed to load data: ${error}</p>`;
        });
    }
  </script>

  <script src="js/sidebar.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php $conn->close(); ?>
</body>
</html>
