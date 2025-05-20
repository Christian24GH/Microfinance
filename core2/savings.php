<?php
include __DIR__.'/session.php';
    // Database connection setup
    $host = 'localhost';          // your DB host
    $user = 'root';       // your DB username
    $password = ''; // your DB password
    $database = 'microfinance';        // your DB name

    // Create connection
    $conn = mysqli_connect($host, $user, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch Loan Summary
    $loanSummaryQuery = "SELECT COUNT(*) as loan_count, SUM(amount) as total_loan_amount FROM loan_info";
    $loanSummaryResult = mysqli_query($conn, $loanSummaryQuery);
    $loanSummary = mysqli_fetch_assoc($loanSummaryResult);

    // Loan State breakdown for pie chart
    $loanStateQuery = "SELECT loan_state, COUNT(*) as count FROM loan_info GROUP BY loan_state";
    $loanStateResult = mysqli_query($conn, $loanStateQuery);
    $loanStateData = [];
    while ($row = mysqli_fetch_assoc($loanStateResult)) {
        $loanStateData[] = $row;
    }

    // Monthly Loan Amount Trends (line chart)
    $monthlyLoanQuery = "SELECT month, SUM(amount) as total_amount FROM loan_info GROUP BY month ORDER BY month";
    $monthlyLoanResult = mysqli_query($conn, $monthlyLoanQuery);
    $monthlyLoanData = [];
    while ($row = mysqli_fetch_assoc($monthlyLoanResult)) {
        $monthlyLoanData[] = $row;
    }

    // Total Disbursed Amount
    $disbursementSummaryQuery = "SELECT SUM(amount) as total_disbursed FROM disbursement_tbl";
    $disbursementSummaryResult = mysqli_query($conn, $disbursementSummaryQuery);
    $disbursementSummary = mysqli_fetch_assoc($disbursementSummaryResult);

    // Recent Disbursements - last 5
    $recentDisbursementQuery = "SELECT d.disbursement_id, d.amount, d.disbursementDate, d.loan_id FROM disbursement_tbl d ORDER BY d.disbursementDate DESC LIMIT 5";
    $recentDisbursementResult = mysqli_query($conn, $recentDisbursementQuery);

    // Client count
    $clientCountQuery = "SELECT COUNT(*) as client_count FROM client_info";
    $clientCountResult = mysqli_query($conn, $clientCountQuery);
    $clientCount = mysqli_fetch_assoc($clientCountResult);

    // Client list (limit 10)
    $clientListQuery = "SELECT firstname, middlename, lastname, contact FROM client_info LIMIT 10";
    $clientListResult = mysqli_query($conn, $clientListQuery);

    // Loan purpose breakdown for pie chart
    $loanPurposeQuery = "SELECT purpose, COUNT(*) as count FROM loan_info GROUP BY purpose";
    $loanPurposeResult = mysqli_query($conn, $loanPurposeQuery);
    $loanPurposeData = [];
    while ($row = mysqli_fetch_assoc($loanPurposeResult)) {
        $loanPurposeData[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Savings Tracking</title>

    <link href="./resources/css/app.css" rel="stylesheet" />
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --mfc1: #4bc5ec;
            --mfc2: #94dcf4;
            --mfc3: #2c3c8c;
            --mfc4: #5cbc9c;
            --mfc5: rgb(215, 225, 236);
            --mfc6: #353c61;
            --mfc7: #272c47;
            --mfc8: white;
        }
        body {
            background-color: var(--mfc5);
        }
        /* Bento box style for cards */
        .card {
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        /* Scroll for client list */
        .client-list {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="relative">
    <?php include __DIR__.'/components/sidebar.php'; ?>

    <div id="main" class="container1 my-0" style="margin-left: 250px;">
        <?php include __DIR__.'/components/header.php'; ?>

    
  <div class="container my-5 p-4 bg-white rounded shadow">
  <h3 class="mb-4">Savings Tracking</h3>
  <button id="downloadPDF" class="btn btn-outline-dark mt-4">Download
  <i class="bi bi-download"></i>
</button>

  <div class="table-responsive">

  <div class="row g-4 mb-9 align-items-stretch">
    

<div class="row">
  <!-- Download PDF Button -->
  <div class="col-12 mb-3 text-end">
    <button id="downloadPDF" class="btn btn-primary"></button>
  </div>

  <!-- Loan Summary Bento Box -->
  <div class="col-md-4">
    <div class="card p-3 bg-white h-100">
      <h5 class="card-title mb-3" style="color: var(--mfc3)">Loan Summary</h5>
      <p>Total Loans: <strong><?= $loanSummary['loan_count'] ?></strong></p>
      <p>Total Loan Amount: <strong>₱<?= number_format($loanSummary['total_loan_amount'], 2) ?></strong></p>
      <canvas id="loanStateChart" height="200"></canvas>
    </div>
  </div>

  <!-- Monthly Loan Trends Bento Box -->
  <div class="col-md-8">
    <div class="card p-3 bg-white h-100">
      <h5 class="card-title mb-3" style="color: var(--mfc3)">Monthly Loan Amount Trends</h5>
      <canvas id="monthlyLoanChart" width="800" height="400"></canvas>
    </div>
  </div>

  <!-- Disbursement Overview Bento Box -->
  <div class="col-md-6 mt-3">
    <div class="card p-3 bg-white h-100">
      <h5 class="card-title mb-3" style="color: var(--mfc3)">Disbursement Overview</h5>
      <p>Total Disbursed Amount: <strong>₱<?= number_format($disbursementSummary['total_disbursed'], 2) ?></strong></p>
      <h6>Recent Disbursements</h6>
      <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Loan ID</th>
              <th>Amount</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_assoc($recentDisbursementResult)): ?>
            <tr>
              <td><?= htmlspecialchars($row['disbursement_id']) ?></td>
              <td><?= htmlspecialchars($row['loan_id']) ?></td>
              <td>₱<?= number_format($row['amount'], 2) ?></td>
              <td><?= htmlspecialchars($row['disbursementDate']) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Client Summary Bento Box -->
  <div class="col-md-6 mt-3">
    <div class="card p-3 bg-white h-100">
      <h5 class="card-title mb-3" style="color: var(--mfc3)">Client Summary</h5>
      <p>Total Clients: <strong><?= $clientCount['client_count'] ?></strong></p>
      <h6>Client Contacts</h6>
      <ul class="list-group list-group-flush client-list">
        <?php while($client = mysqli_fetch_assoc($clientListResult)):
          $fullName = trim($client['firstname'] . ' ' . $client['middlename'] . ' ' . $client['lastname']);
        ?>
        <li class="list-group-item">
          <strong><?= htmlspecialchars($fullName) ?></strong><br />
          <small><?= htmlspecialchars($client['contact']) ?></small>
        </li>
        <?php endwhile; ?>
      </ul>
    </div>
  </div>
</div> <!-- End row -->

    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
  // Data from PHP (make sure your PHP outputs proper JSON arrays)
  const loanStateLabels = <?= json_encode(array_column($loanStateData, 'loan_state')) ?>;
  const loanStateCounts = <?= json_encode(array_column($loanStateData, 'count')) ?>;

  const monthlyLoanLabels = <?= json_encode(array_column($monthlyLoanData, 'month')) ?>;
  const monthlyLoanTotals = <?= json_encode(array_map(fn($e) => (float)$e['total_amount'], $monthlyLoanData)) ?>;

  const loanPurposeLabels = <?= json_encode(array_column($loanPurposeData, 'purpose')) ?>;
  const loanPurposeCounts = <?= json_encode(array_column($loanPurposeData, 'count')) ?>;

  // Convert arrays into objects for raw data to send to backend
  const loanStateRawData = loanStateLabels.reduce((obj, label, i) => {
    obj[label] = loanStateCounts[i];
    return obj;
  }, {});

  const monthlyLoanRawData = monthlyLoanLabels.reduce((obj, label, i) => {
    obj[label] = monthlyLoanTotals[i];
    return obj;
  }, {});

  const loanPurposeRawData = loanPurposeLabels.reduce((obj, label, i) => {
    obj[label] = loanPurposeCounts[i];
    return obj;
  }, {});

  // Initialize Charts
  const loanStateChart = new Chart(document.getElementById('loanStateChart').getContext('2d'), {
    type: 'pie',
    data: {
      labels: loanStateLabels,
      datasets: [{
        label: 'Loan States',
        data: loanStateCounts,
        backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6c757d'],
        hoverOffset: 20
      }]
    }
  });

  const monthlyLoanChart = new Chart(document.getElementById('monthlyLoanChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: monthlyLoanLabels,
      datasets: [{
        label: 'Total Loan Amount',
        data: monthlyLoanTotals,
        fill: false,
        borderColor: '#4bc5ec',
        tension: 0.3
      }]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  const loanPurposeChart = new Chart(document.getElementById('loanPurposeChart')?.getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: loanPurposeLabels,
      datasets: [{
        label: 'Loan Purpose',
        data: loanPurposeCounts,
        backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545', '#6f42c1', '#fd7e14'],
        hoverOffset: 20
      }]
    }
    
  });

  // PDF Download Handler
  document.addEventListener('DOMContentLoaded', () => {
    const downloadBtn = document.getElementById('downloadPDF');
    if (!downloadBtn) {
      console.error('Download button (#downloadPDF) not found.');
      return;
    }

    downloadBtn.addEventListener('click', async () => {
      try {
        const charts = [];

        // Get chart images
        document.querySelectorAll('canvas').forEach(canvas => {
          const chartInstance = Chart.getChart(canvas);
          if (!chartInstance) return;

          const imgData = canvas.toDataURL('image/png');
          const card = canvas.closest('.card');
          const title = card?.querySelector('.card-title')?.innerText.trim() || 'Untitled Chart';

          let rawData = null;
          switch (canvas.id) {
            case 'loanStateChart': rawData = loanStateRawData; break;
            case 'monthlyLoanChart': rawData = monthlyLoanRawData; break;
            case 'loanPurposeChart': rawData = loanPurposeRawData; break;
          }

          charts.push({ name: title, data: imgData, rawData });
        });

        // Find the Disbursement Overview card and capture its table
        const cards = document.querySelectorAll('.card');
        let disbursementCard = null;
        for (const card of cards) {
          const title = card.querySelector('.card-title');
          if (title && title.textContent.includes('Disbursement Overview')) {
            disbursementCard = card;
            break;
          }
        }

        if (disbursementCard) {
          const tableContainer = disbursementCard.querySelector('.table-responsive');
          if (tableContainer) {
            const canvas = await html2canvas(tableContainer);
            const imgData = canvas.toDataURL('image/png');
            charts.push({
              name: 'Disbursement Table',
            });
          }
        }

        const response = await fetch('fpdf/generate.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ charts })
        });

        if (!response.ok) throw new Error(`Failed to generate PDF: ${response.statusText}`);

        const blob = await response.blob();
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = 'loan_charts_report.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);

      } catch (err) {
        console.error(err);
        alert(`Error generating PDF: ${err.message}`);
      }
    });
  });
  
</script>

    </div>
    </html>
    </body>