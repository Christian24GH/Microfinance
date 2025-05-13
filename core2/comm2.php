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
                        <h4>Communication Dashboard</h4>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 d-flex">
                            <div class="card flex-fill border-0 illustration">
                                <div class="card-body p-0 d-flex flex-fill">
                                    <div class="row g-0 w-100">
                                        <div class="col-6">
                                            <div class="p-3 m-1">
                                                <h4>Welcome Back, Comm 2 Officer</h4>
                                                <p class="mb-0">Communication 2 Dashboard</p>
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
                        Total Sent Emails Today
                    </p>
                        <div class="mb-2">
    <span class="badge text-success me-2 fs-5">
    <?php echo $totalSentToday; ?>
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
<?php
require_once 'config/db.php'; // Connect to DB using db.php

// Query to fetch users based on the updated database fields
$sql = "SELECT client_ref_id, firstname, lastname, relationship, contact, email, client_id FROM client_references ORDER BY email ASC";

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
        <h6 class="card-subtitle text-muted">Notify Client Reference</h6>
    </div>
    <div class="card-body">
        <!-- User Table -->
        <table class="table align-middle">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Client Ref ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Relationship</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Mail</th>
                </tr>
            </thead>
            <tbody id="client-table-body">
                <?php if ($result->num_rows > 0): 
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" class="select-user" data-clientid="<?= $row['client_ref_id'] ?>" data-email="<?= $row['email'] ?>" data-name="<?= $row['firstname'] . ' ' . $row['lastname'] ?>"></td>
                            <th scope="row"><?= htmlspecialchars($row['client_ref_id']) ?></th>
                            <td><?= htmlspecialchars($row['firstname']) ?></td>
                            <td><?= htmlspecialchars($row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['relationship']) ?></td>
                            <td><?= htmlspecialchars($row['contact']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                               <button class="btn btn-outline-secondary open-email-modal" data-bs-toggle="modal"
    data-bs-target="#emailModal" 
    data-clientid="<?= $row['client_ref_id'] ?>" 
    data-email="<?= $row['email'] ?>" 
    data-name="<?= $row['firstname'] . ' ' . $row['lastname'] ?>">
    <i class="bi bi-envelope-fill"></i> 
</button>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="9" class="text-center">No records found</td></tr>
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
                            <option value="Repayment Reminder">Repayment Reminder</option>
                            <option value="System Update">System Update</option>
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


<!-- JS (to handle select all functionality and email modal opening) -->
<script>
    // Select/Deselect All Checkboxes
document.getElementById('select-all').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('.select-user');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    toggleBulkEmailButton();
});

// Toggle Bulk Email Button
document.querySelectorAll('.select-user').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkEmailButton);
});

function toggleBulkEmailButton() {
    const selected = document.querySelectorAll('.select-user:checked');
    document.getElementById('send-email-btn').style.display = selected.length > 0 ? 'inline-block' : 'none';
}

// Open Individual Email Modal
document.querySelectorAll('.open-email-modal').forEach(button => {
    button.addEventListener('click', function () {
        const email = this.getAttribute('data-email');
        const name = this.getAttribute('data-name');
        const clientId = this.getAttribute('data-clientid');

        document.getElementById('modal-email').value = email;
        document.getElementById('modal-client-ids').value = clientId;
        document.getElementById('modal-message').value = `Dear ${name},\n\n`;
        document.getElementById('modal-subject').selectedIndex = 0;
    });
});

// Open Bulk Email Modal
document.getElementById('send-email-btn').addEventListener('click', function () {
    const selected = document.querySelectorAll('.select-user:checked');
    const emails = Array.from(selected).map(cb => cb.getAttribute('data-email')).join(', ');
    const ids = Array.from(selected).map(cb => cb.getAttribute('data-clientid')).join(',');

    document.getElementById('modal-email').value = emails;
    document.getElementById('modal-client-ids').value = ids;
    document.getElementById('modal-message').value = `Dear Clients,\n\n`;
    document.getElementById('modal-subject').selectedIndex = 0;
});

// Subject Template Handler
document.getElementById('modal-subject').addEventListener('change', function () {
    const subject = this.value;
    const emailInput = document.getElementById('modal-email').value;
    const names = emailInput.split(',').length === 1
        ? emailInput.split('@')[0] // crude name fallback
        : "Clients";

    const messageBox = document.getElementById('modal-message');

switch (subject) {
    case 'Repayment Reminder':
        messageBox.value = `Dear client,\n\nThis is a reminder regarding the loan repayment. Please note that you have been listed as a reference contact in case we are unable to reach the borrower. Kindly remind them to fulfill their repayment obligations on time.\n\nBest regards,\n[Your Company Name]`;
        break;
    case 'System Update':
        messageBox.value = `Dear ${names},\n\nPlease be informed about an upcoming system update. Downtime may occur during maintenance. Thank you for your patience.\n\nBest regards,\n[Your Company Name]`;
        break;
    default:
        messageBox.value = `Dear ${names},\n\n`;
}

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

