<?php
include __DIR__.'/components/session.php';
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
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/app.css"/>
    <link rel="stylesheet" href="css/sidebar.css"/>
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php 
            include __DIR__.'/components/sidebar.php'
    ?>
    <div id="main" class="ps-0 rounded-end visually-hidden">
        <div class="wrapper">
            <div class="main">
                <?php 
                        include __DIR__.'/components/header.php'
                ?>
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
                                                <h4>Welcome Back, Comm 1 Officer</h4>
                                                <p class="mb-0">Communication 1 Dashboard</p>
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
        <h6 class="card-subtitle text-muted">Notify Status</h6>
    </div>
    <div class="card-body">
        <!-- Status Filter Dropdown -->
        <div class="mb-3">
            <label for="status-filter" class="form-label">Filter</label>
            <select class="form-select" id="status-filter">
                <option value="all">All</option>
                <option value="ACTIVE" class="status-ACTIVE" >Active</option>
                <option value="ACCEPTED" class="status-ACCEPTED" >Accepted</option>
                <option value="DENIED" class="status-DENIED">Denied</option>
                <option value="FLAGGED" class="status-FLAGGED">Flagged</option>
                <option value="INACTIVE" class="status-INACTIVE">Inactive</option>
                <option value="PENDING" class="status-PENDING">Pending</option>
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
                    <th>Action</th>
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
    <!-- Email Button -->
    <button class="btn btn-outline-secondary open-email-modal me-1" data-bs-toggle="modal"
            data-bs-target="#emailModal" 
            data-clientid="<?= $row['client_id'] ?>" 
            data-email="<?= $row['email'] ?>" 
            data-name="<?= $row['firstname'] . ' ' . $row['lastname'] ?>">
        <i class="bi bi-envelope-fill"></i>
    </button>
<button class="btn btn-outline-secondary open-clientref-modal"
        data-clientid="<?= $row['client_id'] ?>">
    <i class="bi bi-people-fill"></i>
</button>

</td>

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
<!-- Client References Modal -->
<div class="modal fade" id="clientRefModal" tabindex="-1" aria-labelledby="clientRefModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clientRefModalLabel">Client References</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Ref ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Relationship</th>
              <th>Contact</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody id="client-ref-body">
            <!-- Dynamically inserted via AJAX -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.open-clientref-modal').forEach(button => {
  button.addEventListener('click', function () {
    const clientId = this.getAttribute('data-clientid'); // Get the client ID from the button's data attribute

    // Show the modal before fetching data
    const clientRefModal = new bootstrap.Modal(document.getElementById('clientRefModal'));
    clientRefModal.show();

    // Fetch client references from the server
    fetch('fetch_client_references.php?client_id=' + clientId)
      .then(response => response.json())
      .then(data => {
        const tbody = document.getElementById('client-ref-body');
        tbody.innerHTML = ''; // Clear existing rows

        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="text-center">No references found</td></tr>';
          return;
        }

        // Loop through the data and dynamically create table rows
        data.forEach(ref => {
          const row = `<tr>
              <td>${ref.client_ref_id}</td>
              <td>${ref.firstname}</td>
              <td>${ref.lastname}</td>
              <td>${ref.relationship}</td>
              <td>${ref.contact}</td>
              <td>${ref.email}</td>
            </tr>`;
          tbody.insertAdjacentHTML('beforeend', row);
        });
      })
      .catch(error => {
        console.error('Error fetching references:', error);
      });
  });
});

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
    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

