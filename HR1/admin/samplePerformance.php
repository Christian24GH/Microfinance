<?php

  include('db_connect.php');

  $b = 1;
  $search = '';
  if (isset($_GET['search'])) {
      $search = $conn->real_escape_string($_GET['search']);
  }

// Query to fetch employees with "Hired" status and their respective positions
  $sql = "SELECT 
          a.id AS application_id, 
          CONCAT(a.firstname, ' ', a.lastname) AS full_name, 
          a.email, 
          p.position_name AS position, 
          a.date_created  
        FROM application a
        JOIN positions p ON a.position_id = p.position_id
        JOIN recruitment_status rs ON a.process_id = rs.id
        WHERE rs.status_label = 'Hired' 
        AND (
            a.firstname LIKE '%$search%' 
            OR a.lastname LIKE '%$search%' 
            OR a.email LIKE '%$search%' 
            OR p.position_name LIKE '%$search%'
          )
        ORDER BY a.date_created DESC";
  
  $result = mysqli_query($conn, $sql);
  $Nhired = mysqli_fetch_all($result, MYSQLI_ASSOC);


  // Fetch ratings with employee names
  $rankings = [];
  $top_performer = null;

  $perf_sql = "
    SELECT a.id, CONCAT(a.firstname, ' ', a.lastname) AS full_name, ep.rating
    FROM employee_performance ep
    JOIN application a ON ep.application_id = a.id
    ORDER BY ep.rating DESC
    LIMIT 10
  ";

  $res = $conn->query($perf_sql);
  if ($res && $res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
          $rankings[] = $row;
      }
      $top_performer = $rankings[0];
  }


  
  $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Performance Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  #mveCard h3 {
    font-family: 'Georgia', serif;
    text-shadow: 1px 1px 3px #333;
  }
</style>
</head>
<body class="bg-light">

<div class="container my-5">

  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">🏅 Performance Ranking</h5>
        </div>
        <div class="card-body">
          <?php if (empty($rankings)): ?>
            <p class="text-muted">No ratings yet.</p>
          <?php else: ?>
            <?php foreach ($rankings as $index => $emp): ?>
              <div class="d-flex justify-content-between border-bottom py-1">
                <span><?= ($index + 1) ?>. <?= $emp['full_name'] ?></span>
                <span><strong><?= $emp['rating'] ?> ⭐</strong></span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm border-warning">
        <div class="card-header bg-warning text-dark">
          <h5 class="mb-0">👑 Most Valuable Employee</h5>
        </div>
        <div class="card-body text-center">
          <?php if ($top_performer): ?>
            <h3 class="fw-bold text-warning">👑 <?= $top_performer['full_name'] ?></h3>
            <p class="mt-2">with <strong><?= $top_performer['rating'] ?> stars</strong></p>
          <?php else: ?>
            <p class="text-muted">Waiting for ratings...</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>




  <!-- Table Section -->
  <div class="card p-4 shadow-sm">
    <div class="card mt-4 p-4 shadow-sm">
      
      <div class="container my-3">
        <form method="GET" class="row g-3 align-items-center" action="index.php">
          <input type="hidden" name="page" value="samplePerformance">
          <div class="col-auto">
              <input type="text" name="search" class="form-control" placeholder="Search by name or position" value="<?php echo htmlspecialchars($search); ?>">
          </div>
          <div class="col-auto">
              <button type="submit" class="btn btn-primary">Search</button>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <form action="submit_performance.php" method="post">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Position</th>
                <th class="text-center">Rating</th>
                <th class="text-center">Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($Nhired as $hired): ?>
              <tr>
                <td><?= $b++ ?></td>
                <td><?= $hired['full_name'] ?></td>
                <td><?= $hired['email'] ?></td>
                <td><?= $hired['position'] ?></td>
                <td>
                  <select class="form-select" name="ratings[<?= $hired['application_id'] ?>]">
                    <option disabled selected>Select</option>
                    <?php for ($i = 1; $i <= 10; $i++) echo "<option value='$i'>$i</option>"; ?>
                  </select>
                </td>
                <td>
                  <input type="text" class="form-control" name="notes[<?= $hired['application_id'] ?>]" placeholder="Enter notes">
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="text-end">
            <button type="submit" class="btn btn-success">Submit Ratings</button>
          </div>
        </form>
      </div>
    </div>

      <!-- Report Submission Section -->
      <div class="card mt-4 p-4 shadow-sm">
        <h5 class="mb-3">Submit Performance Report</h5>
        <form action="upload_report.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="reportFile" class="form-label">Upload Report File (PDF, DOCX, etc.)</label>
            <input class="form-control" type="file" name="reportFile" id="reportFile" accept=".pdf,.doc,.docx" required>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Submit Report</button>
          </div>
        </form>
      </div>
  </div>
</div>

<script>
  // Search function
  const searchInput = document.getElementById("searchInput");
  searchInput.addEventListener("keyup", function () {
    const filter = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll("#performanceTable tr");
    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(filter) ? "" : "none";
    });
  });

  // Dynamically insert rating options for demo row
  document.querySelectorAll("select").forEach(select => {
    if (select.options.length <= 1) {
      for (let i = 1; i <= 10; i++) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        select.appendChild(option);
      }
    }
  });

  function updatePerformanceCards() {
  const rows = document.querySelectorAll("#performanceTable tr");
  let performanceData = [];

  rows.forEach(row => {
    const name = row.cells[1]?.textContent.trim();
    const rating = parseInt(row.querySelector("select")?.value || 0);

    if (name && rating > 0) {
      performanceData.push({ name, rating });
    }
  });

  performanceData.sort((a, b) => b.rating - a.rating);

  const rankingCard = document.getElementById("rankingCard");
  const mveCard = document.getElementById("mveCard");

  if (performanceData.length === 0) {
    rankingCard.innerHTML = `<p class="text-muted">No ratings yet. Select ratings to see rankings.</p>`;
    mveCard.innerHTML = `<p class="text-muted">Waiting for star...</p>`;
    return;
  }

  rankingCard.innerHTML = performanceData.map((emp, index) => `
    <div class="d-flex justify-content-between border-bottom py-1">
      <span>${index + 1}. ${emp.name}</span>
      <span><strong>${emp.rating} ⭐</strong></span>
    </div>
  `).join("");

  const topPerformer = performanceData[0];
  mveCard.innerHTML = `
    <h3 class="text-warning fw-bold">
      👑 ${topPerformer.name}
    </h3>
    <p class="mt-2">with a stellar rating of <strong>${topPerformer.rating} ⭐</strong></p>
  `;
  }

  // Attach event listeners after the page loads
  window.addEventListener('load', () => {
    document.querySelectorAll("#performanceTable select").forEach(select => {
      select.addEventListener("change", updatePerformanceCards);
    });
  });

</script>

</body>
</html>

