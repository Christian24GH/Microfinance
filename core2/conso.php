<?php
    include __DIR__.'/session.php';
?>
<?php
// DB Connection (update as needed)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfinance";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Filtering
$search = $_GET['search'] ?? '';
$interest = $_GET['interest_rate'] ?? '';
$term = $_GET['term'] ?? '';

$sql = "
  SELECT 
    loan.*, 
    client_info.firstname, 
    client_info.lastname
  FROM loan
  JOIN client_info ON loan.client_id = client_info.client_id
  WHERE 1
";

// (Optionally add filtering if needed based on $_GET['search'] etc.)
$result = $conn->query($sql);

// Get distinct interest rates and terms
$interestRates = $conn->query("SELECT DISTINCT interest_rate FROM loan ORDER BY interest_rate");
$terms = $conn->query("SELECT DISTINCT term FROM loan ORDER BY term");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidation</title>
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
            --mfc5:rgb(215, 225, 236);
            --mfc6: #353c61;
            --mfc7: #272c47;
            --mfc8: white;
        }
           body {
            background-color: var(--mfc5);
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
  </style>
</head>


<div class="container my-5 p-4 bg-white rounded shadow">
  <h3 class="mb-4">Consolidation</h3>

  <!-- Search & Filter -->
<form id="filterForm" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search loan/client/type">
    </div>
    <div class="col-md-3">
      <select name="interest_rate" class="form-select">
        <option value="">All Interest Rates</option>
        <?php while ($i = $interestRates->fetch_assoc()): ?>
          <option value="<?= $i['interest_rate'] ?>" <?= $i['interest_rate'] == $interest ? 'selected' : '' ?>>
            <?= $i['interest_rate'] ?>%
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="term" class="form-select">
        <option value="">All Terms</option>
        <?php while ($t = $terms->fetch_assoc()): ?>
          <option value="<?= $t['term'] ?>" <?= $t['term'] == $term ? 'selected' : '' ?>>
            <?= $t['term'] ?> months
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
          <th>First name</th>
        <th>Last name</th>
          <th>Loan Type</th>
          <th>Start Date</th>
          <th>Interest Rate (%)</th>
          <th>Term</th>
          <th>Original Amount</th>
          <th>Current Balance</th>
          <th>Actions</th>
        </tr>
      </thead>
<tbody id="loanTableBody">
  <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row["loan_id"] ?></td>
        <td><?= htmlspecialchars($row["firstname"]) ?></td>
        <td><?= htmlspecialchars($row["lastname"]) ?></td>
        <td><?= $row["loan_type"] ?></td>
        <td><?= $row["start_date"] ?></td>
        <td><?= $row["interest_rate"] ?></td>
        <td><?= $row["term"] ?></td>
        <td>₱<?= number_format($row["original_amount"], 2) ?></td>
        <td>₱<?= number_format($row["current_balance"], 2) ?></td>
        <td>
          <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="showModalContent('View', <?= $row['loan_id'] ?>)">
            <i class="bi bi-eye-fill"></i>
          </button>
         <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#downloadModal" onclick="setLoanId(<?= $row['loan_id'] ?>)">
  <i class="bi bi-download"></i> 
</button>

        </td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr><td colspan="10">No loan records found.</td></tr>
  <?php endif; ?>
</tbody>

    </table>
  </div>
</div>

<!-- Modals -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <h5 class="modal-title">View Loan</h5>
      <div class="modal-body" id="viewContent"></div>
      <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
    </div>
  </div>
</div>
<!-- Bootstrap Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="downloadModalLabel">Download Loan Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to download the loan data PDF?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="downloadLink" class="btn btn-success">Download PDF</a>
      </div>
    </div>
  </div>
</div>
<script>
  function setLoanId(loanId) {
    // Update the download link URL with the selected loan_id
    const downloadLink = document.getElementById('downloadLink');
    downloadLink.href = 'fpdf/download.php?loan_id=' + loanId;
  }
</script>


<!-- Bootstrap Scripts -->
<script>
function showModalContent(action, loanId) {
  const targetId = action === 'View' ? 'viewContent' : 'downloadContent';
  const content = document.getElementById(targetId);

  // Show loading while fetching
  content.innerHTML = `<p>Loading ${action.toLowerCase()} details...</p>`;

  fetch(`config/loan_action.php?action=${action.toLowerCase()}&loan_id=${loanId}`)
    .then(response => response.text())
    .then(data => {
      content.innerHTML = data;
    })
    .catch(error => {
      content.innerHTML = `<p class="text-danger">Failed to load data: ${error}</p>`;
    });
}

</script>
<script>
document.getElementById("filterForm").addEventListener("submit", function(e) {
  e.preventDefault(); // prevent form reload

  const form = e.target;
  const search = form.search.value;
  const interest = form.interest_rate.value;
  const term = form.term.value;

  const params = new URLSearchParams({ search, interest_rate: interest, term });

  fetch('config/fetch_loans.php?' + params.toString())
    .then(response => response.text())
    .then(data => {
      document.getElementById("loanTableBody").innerHTML = data;
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });
});
</script>


</body>
</html>

<?php $conn->close(); ?>
    </div>

    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
