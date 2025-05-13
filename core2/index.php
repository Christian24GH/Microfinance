<?php
// Include your database connection
require_once 'config/db.php';

// Get today's date in the format 'Y-m-d' (year-month-day)
$today = date('Y-m-d');

// Query to count the total sent emails today
$query = "SELECT COUNT(*) as total_sent_today FROM email_logs WHERE status = 'SENT' AND DATE(send_date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $today);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalSentToday = $row['total_sent_today'] ?? 0; // Default to 0 if no emails sent today
?>



<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="index.php">Core 2</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Admin Control
                    </li>
                    <li class="sidebar-item">
                        <a href="index.php" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-file-lines pe-2"></i>
                    Communication
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="comm1.php" class="sidebar-link">Comm Mgmt1</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="comm2.php" class="sidebar-link">Comm Mgmt2</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="savings.php" class="sidebar-link collapsed"
                            aria-expanded="false"><i class="fa-solid fa-sliders pe-2"></i>Savings Tracking
                        </a>
                    </li>
                           <li class="sidebar-item">
                        <a href="cons.php" class="sidebar-link collapsed"
                            aria-expanded="false"><i class="fa-solid fa-sliders pe-2"></i>Consolidation
                        </a>
                    </li>
                   <li class="sidebar-item">
                        <a href="spm.php" class="sidebar-link collapsed" data-bs-target="#auth"
                            aria-expanded="false"><i class="fa-regular fa-user pe-2"></i>
                         Social Performance 
                        </a>
                    </li>
                    <li class="sidebar-header">
                  Settings
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#multi" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-share-nodes pe-2"></i>
                 Authority
                        </a>
                        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-target="#level-1"
                                    data-bs-toggle="collapse" aria-expanded="false">Account</a>
                                <ul id="level-1" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Logout</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Register</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="image/profile.jpg" class="avatar img-fluid rounded" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h4>Admin Dashboard</h4>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 d-flex">
                            <div class="card flex-fill border-0 illustration">
                                <div class="card-body p-0 d-flex flex-fill">
                                    <div class="row g-0 w-100">
                                        <div class="col-6">
                                            <div class="p-3 m-1">
                                                <h4>Welcome Back, Admin</h4>
                                                <p class="mb-0">Admin Dashboard</p>
                                            </div>
                                        </div>
                                        <div class="col-6 align-self-end text-end">
                                            <img src="image/customer-support.jpg" class="img-fluid illustration-img"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="col-12 col-md-6 d-flex">
    <div class="card flex-fill border-0">
        <div class="card-body py-4">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <h4 class="mb-2">
                    </h4>
                    <p class="mb-2">
                        Total Sent Emails 
                    </p>
                    <div class="mb-2">
    <span class="badge text-success me-2 fs-5">
    <?php echo $totalSentToday; ?>
</span>


                        </span>
                        <span class="text-muted">
                            Today
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

      <div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-header">
                <h6 class="card-title mb-0">Total Paid</h6>
            </div>
            <div class="card-body">
                <canvas id="chart1" height="150"></canvas>
            </div>
            <div class="card-body">
                <!-- Dropdown Toggle Button for Table 1 -->
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#table1" aria-expanded="false" aria-controls="table1">
          View All
                </button>
                
                <!-- Collapsible Table 1 -->
                <div class="collapse" id="table1">
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total Loan Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be inserted dynamically here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-header">
                <h6 class="card-title mb-0">Total Loan</h6>
            </div>
            <div class="card-body">
                <canvas id="chart2" height="150"></canvas>
            </div>
            <div class="card-body">
                <!-- Dropdown Toggle Button for Table 2 -->
                <button class="btn btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#table2" aria-expanded="false" aria-controls="table2">
                   View All
                </button>
                
                <!-- Collapsible Table 2 -->
                <div class="collapse" id="table2">
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Loan Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be inserted dynamically here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Example data based on your SQL query
    const monthlyData = [
        { month: 6, total_amount: 50000 },
        { month: 10, total_amount: 75000 },
        { month: 12, total_amount: 100000 },
        { month: 24, total_amount: 150000 },
        { month: 36, total_amount: 200000 }
    ];

    // Populate the first table (Chart 1)
    const table1Body = document.getElementById('table1').getElementsByTagName('tbody')[0];
    monthlyData.forEach(data => {
        const row = table1Body.insertRow();
        row.insertCell(0).textContent = `Month ${data.month}`;
        row.insertCell(1).textContent = data.total_amount;
    });

    // Chart 1 (Bar chart)
    const ctx1 = document.getElementById('chart1').getContext('2d');
    const chart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: monthlyData.map(data => `Month ${data.month}`),
            datasets: [{
                label: 'Loan Amount Monthly',
                data: monthlyData.map(data => data.total_amount),
                backgroundColor: 'rgba(59, 42, 136, 0.6)',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Chart 2 (Line chart)
    const ctx2 = document.getElementById('chart2').getContext('2d');
    const chart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'], // Example data, change as needed
            datasets: [{
                label: 'Data B',
                data: [5, 15, 10, 20, 8], // Example data, change as needed
          backgroundColor: 'rgba(59, 42, 136, 0.6)',
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Populate the second table (Chart 2)
    const table2Body = document.getElementById('table2').getElementsByTagName('tbody')[0];
    const monthlyLoanCount = [
        { month: 'Jan', loan_count: 5 },
        { month: 'Feb', loan_count: 10 },
        { month: 'Mar', loan_count: 8 },
        { month: 'Apr', loan_count: 12 },
        { month: 'May', loan_count: 7 }
    ];

    monthlyLoanCount.forEach(data => {
        const row = table2Body.insertRow();
        row.insertCell(0).textContent = data.month;
        row.insertCell(1).textContent = data.loan_count;
    });
</script>


                    
                    <!-- Table Element -->
    <?php
require_once 'config/db.php'; // Connect to DB using db.php

// Query to fetch users
$sql = "SELECT client_id, firstname, lastname, email FROM client_info";
$result = $conn->query($sql);

// Check for query error
if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!-- Table Element -->
<?php
require_once 'config/db.php'; // Connect to DB using db.php

// Query to fetch users
$sql = "SELECT client_id, firstname, lastname, email FROM client_info";
$result = $conn->query($sql);

// Check for query error
if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!-- Table Element -->
<?php
require_once 'config/db.php'; // Connect to DB using db.php

// Query to fetch users
$sql = "SELECT client_id, firstname, lastname, email, status FROM client_info";
$result = $conn->query($sql);

// Check for query error
if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<!-- Bootstrap card -->
<div class="card border-0">
    <div class="card-header">
        <h5 class="card-title">Communication</h5>
        <h6 class="card-subtitle text-muted">Recent Clients</h6>
    </div>
    <div class="card-body">
        <!-- Status Filter Dropdown -->
        <div class="mb-3">
            <label for="status-filter" class="form-label">Filter by Status</label>
            <select class="form-select" id="status-filter">
                <option value="all">All</option>
                <option value="ACTIVE" class="status-ACTIVE">Active</option>
                <option value="PENDING" class="status-PENDING">Pending</option>
                <option value="INACTIVE" class="status-INACTIVE">Inactive</option>
                <option value="ACCEPTED" class="status-ACCEPTED" >Accepted</option>
                <option value="DENIED" class="status-DENIED">Denied</option>
                <option value="FLAGGED" class="status-FLAGGED">Flagged</option>
            </select>
        </div>
<style>
    .status-ACTIVE {
        color: green;
        font-weight: bold;
    }

    .status-PENDING {
        color: orange;
        font-weight: bold;
    }

    .status-INACTIVE {
        color: gray;
        font-weight: bold;
    }

    .status-ACCEPTED {
        color: blue;
        font-weight: bold;
    }

    .status-DENIED {
        color: red;
        font-weight: bold;
    }

    .status-FLAGGED {
        color: purple;
        font-weight: bold;
    }
</style>

        <!-- User Table -->
        <table class="table align-middle">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>First</th>
                    <th>Last</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Mail</th>
                </tr>
            </thead>
            <tbody id="client-table-body">
                <?php if ($result->num_rows > 0): 
                    while ($row = $result->fetch_assoc()): ?>
                        <tr data-status="<?= htmlspecialchars($row['status']) ?>">
                            <td><input type="checkbox" class="select-user" data-clientid="<?= $row['client_id'] ?>" data-email="<?= $row['email'] ?>"></td>
                            <th scope="row"><?= htmlspecialchars($row['client_id']) ?></th>
                            <td><?= htmlspecialchars($row['firstname']) ?></td>
                            <td><?= htmlspecialchars($row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                <td class="status-<?= htmlspecialchars($row['status']) ?>">
    <?= htmlspecialchars($row['status']) ?>
</td>

                            <td>
                                <button class="btn btn-outline-secondary open-email-modal" data-bs-toggle="modal"
                                        data-bs-target="#emailModal" 
                                        data-clientid="<?= $row['client_id'] ?>" 
                                        data-email="<?= $row['email'] ?>" 
                                        data-name="<?= $row['firstname'] . ' ' . $row['lastname'] ?>">
                                    <i class="bi bi-envelope-fill"></i>
                                </button>
                            </td>
                        </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="7" class="text-center">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Send Email Button -->
        <button id="send-email-btn" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#emailModal" style="display:none;">
            Send Bulk Email
        </button>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="mail.php" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Send Email to Clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="client_ids" id="modal-client-ids">
                    <div class="mb-3">
                        <label for="modal-email" class="form-label">To</label>
                        <input type="text" class="form-control" id="modal-email" name="email" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="modal-subject" class="form-label">Subject</label>
                        <select class="form-select" id="modal-subject" name="subject" required>
                            <option value="" disabled selected>Select a subject</option>
                            <option value="Loan Application">Loan Application</option>
                            <option value="Account Flag">Account Flag</option>
                            <option value="Compliance Update">Compliance Update</option>
                            <option value="Scheduled Payment Reminder">Scheduled Payment Reminder</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal-message" class="form-label">Message</label>
                        <textarea class="form-control" id="modal-message" name="message" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="modal-attachment" class="form-label">Attach an Image (optional)</label>
                        <input type="file" class="form-control" id="modal-attachment" name="attachment" accept="image/*">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-secondary">Send Email</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailModal = document.getElementById('emailModal');
    const subjectDropdown = document.getElementById('modal-subject');
    const messageBox = document.getElementById('modal-message');
    const selectAllCheckbox = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.select-user');
    const sendEmailBtn = document.getElementById('send-email-btn');
    const tableBody = document.getElementById('client-table-body');
    const statusFilter = document.getElementById('status-filter');

    // Templates for each subject
    const templates = {
        "Loan Application": "Dear Client,\n\nThank you for your interest in our loan program. We are processing your application and will contact you shortly.\n\nRegards,\nSupport Team",
        "Account Flag": "Dear Client,\n\nWe have flagged your account due to recent activities. Please contact support immediately to resolve this.\n\nThank you.",
        "Compliance Update": "Dear Client,\n\nWe have updated our compliance policies. Kindly review the new changes on your account dashboard.\n\nSincerely,\nCompliance Team",
        "Scheduled Payment Reminder": "Dear Client,\n\nThis is a reminder for your upcoming scheduled payment. Please ensure your account has sufficient funds.\n\nRegards,\nBilling Department"
    };

    // On subject change, load corresponding template
    subjectDropdown.addEventListener('change', function () {
        const selected = subjectDropdown.value;
        messageBox.value = templates[selected] || '';
    });

    // Show modal with proper recipients
    document.querySelectorAll('.open-email-modal').forEach(button => {
        button.addEventListener('click', function () {
            const email = this.getAttribute('data-email');
            const clientId = this.getAttribute('data-clientid');

            document.getElementById('modal-email').value = email;
            document.getElementById('modal-client-ids').value = clientId;
            subjectDropdown.selectedIndex = 0;
            messageBox.value = '';
        });
    });

    emailModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;

        // If bulk button triggered modal
        if (trigger && trigger.id === 'send-email-btn') {
            const selectedClients = [];
            userCheckboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    selectedClients.push({
                        id: checkbox.getAttribute('data-clientid'),
                        email: checkbox.getAttribute('data-email')
                    });
                }
            });

            const clientEmails = selectedClients.map(client => client.email).join(', ');
            const clientIds = selectedClients.map(client => client.id).join(',');

            document.getElementById('modal-email').value = clientEmails;
            document.getElementById('modal-client-ids').value = clientIds;
            subjectDropdown.selectedIndex = 0;
            messageBox.value = '';
        }
    });

    // Toggle Send Bulk Email button
    function toggleSendButton() {
        const anySelected = Array.from(userCheckboxes).some(cb => cb.checked);
        sendEmailBtn.style.display = anySelected ? 'block' : 'none';
    }

    selectAllCheckbox.addEventListener('change', function () {
        userCheckboxes.forEach(cb => cb.checked = this.checked);
        toggleSendButton();
    });

    userCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked) selectAllCheckbox.checked = false;
            toggleSendButton();
        });
    });

    statusFilter.addEventListener('change', function () {
        const selectedStatus = statusFilter.value;
        Array.from(tableBody.getElementsByTagName('tr')).forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            row.style.display = selectedStatus === 'all' || rowStatus === selectedStatus ? '' : 'none';
        });
    });
});
</script>



            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a href="#" class="text-muted">
                                    <strong></strong>
                                </a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted"></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted"></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted"></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="text-muted"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
