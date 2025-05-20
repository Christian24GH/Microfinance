<?php
// Database connection
include __DIR__.'/session.php';
$conn = new mysqli("localhost", "root", "", "microfinance");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to count emails sent today (with status = 'sent')
    $sql = "SELECT COUNT(*) AS total_today FROM email_logs WHERE DATE(send_date) = CURDATE() AND status = 'sent'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $totalEmailsToday = $row['total_today'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="./resources/css/app.css" rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <!-- Greetings Row -->
                <div class="container-fluid py-4" style="min-height: 100vh;">
                <div class="row mb-4">
                    <div class="col-md-6">
                    <div class="p-4 bg-white rounded shadow h-100">
                        <h2 class="mb-2">Welcome Back, Admin!</h2>
                        <p class="text-muted mb-10">Manage your dashboard efficiently. Let’s make today productive!</p>
                    </div>
                    </div>
                <div class="col-md-6">
                <div class="p-4 bg-white rounded shadow h-100 text-center">
                    <h4 class="mb-2">Emails Sent Today</h4>
                    <h2 class="display-4 text-primary">
                    <?php echo $totalEmailsToday; ?>
                    </h2>
                    <p class="text-muted mb-0">Total emails successfully sent today</p>
                </div>
                </div>
                </div>

                <!-- Main Dashboard Content -->
                <div class="row mb-4">
                    <div class="col-md-3">
                    <div class="card" style="background-color: var(--mfc7); color: var(--mfc8);">
                        <div class="card-body text-center">
                        <i class="bi bi-people-fill fs-1 mb-2"></i>
                        <h5 class="card-title">Users</h5>
                        <p class="card-text display-6">1,245</p>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-3">
                    <div class="card" style="background-color: var(--mfc7); color: var(--mfc8);">
                        <div class="card-body text-center">
                        <i class="bi bi-envelope-paper-fill fs-1 mb-2"></i>
                        <h5 class="card-title">Sent Emails</h5>
                        <p class="card-text display-6">580</p>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-3">
                    <div class="card" style="background-color: var(--mfc7); color: var(--mfc8);">
                        <div class="card-body text-center">
                        <i class="bi bi-piggy-bank-fill fs-1 mb-2"></i>
                        <h5 class="card-title">Savings</h5>
                        <p class="card-text display-6">32 XXX XXX</p>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-3">
                    <div class="card" style="background-color: var(--mfc7); color: var(--mfc8);">
                        <div class="card-body text-center">
                        <i class="bi bi-person-x-fill fs-1 mb-2"></i>
                        <h5 class="card-title">Flagged Users</h5>
                        <p class="card-text display-6">40</p>
                        </div>
                    </div>
                    </div>
                </div>


                <!-- Chart Placeholder -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-shadow">
                            <div class="card-header" style="background-color: var(--mfc7); color: var(--mfc8);">
                                <h5 class="mb-0">Monthly Client Registration</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Connect to DB
                                $mysqli = new mysqli("localhost", "root", "", "microfinance");

                                // Fetch client registration data per month
                                $query = "
                                    SELECT 
                                        DATE_FORMAT(registration_date, '%Y-%m') AS reg_month, 
                                        COUNT(*) AS client_count 
                                    FROM client_info 
                                    GROUP BY reg_month 
                                    ORDER BY reg_month ASC
                                ";

                                $result = $mysqli->query($query);

                                $regMonths = [];
                                $clientCounts = [];

                                while ($row = $result->fetch_assoc()) {
                                    $regMonths[] = $row['reg_month'];
                                    $clientCounts[] = $row['client_count'];
                                }

                                $mysqli->close();
                                ?>

                                <div style="height: 300px; background-color: #f5f5f5;" class="d-flex align-items-center justify-content-center">
                                    <canvas id="clientRegChart" style="max-height: 260px;"></canvas>
                                </div>
                                <div class="text-end mt-3">
                        <button id="downloadClientRegChart" class="btn btn-outline-dark" title="Download Chart">Download
                <i class="bi bi-download"></i>
                </button>
                </div>

                <script>
                    const clientRegMonths = <?= json_encode($regMonths); ?>;
                    const clientCounts = <?= json_encode($clientCounts); ?>;

                    const ctx = document.getElementById('clientRegChart').getContext('2d');
                    const clientRegChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: clientRegMonths,
                            datasets: [{
                                label: 'Registered Clients per Month',
                                data: clientCounts,
                                fill: false,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                tension: 0.3,
                                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                                pointBorderColor: '#fff',
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Monthly Client Registrations'
                                }
                            }
                        }
                    });

                    // Download chart as PNG
                    document.getElementById('downloadClientRegChart').addEventListener('click', function () {
                        const link = document.createElement('a');
                        link.download = 'monthly_client_registration_chart.png';
                        link.href = clientRegChart.toBase64Image();
                        link.click();
                    });
                </script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Ensure Chart.js is available -->
            </div>
        </div>
    </div>
</div>

<?php
        // Database connection (ensure credentials are correct)
        $conn = new mysqli("localhost", "root", "", "microfinance");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch recent email logs
        $sql = "SELECT * FROM email_logs ORDER BY send_date DESC LIMIT 5";
        $result = $conn->query($sql);
        ?>
        <!-- Table for Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="card card-shadow">
                    <div class="card-header" style="background-color: var(--mfc7); color: var(--mfc8);">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead class="table-header-custom">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Activity</th>
                                    <th scope="col">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php $counter = 1; ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <th scope="row"><?php echo $counter++; ?></th>
                                            <td><?php echo htmlspecialchars($row['recipient_email']); ?></td>
                                            <td>
                                                <i class="bi bi-envelope-fill text-primary"></i>
                                                <strong><?php echo htmlspecialchars($row['subject']); ?></strong>
                                            </td>
                                            <td><?php echo date("Y-m-d h:i A", strtotime($row['send_date'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No recent email activity found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php $conn->close(); ?>
    </div>
    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
