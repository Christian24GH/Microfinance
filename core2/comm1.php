<?php
    include __DIR__.'/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication 1</title>
    <link href="./resources/css/app.css" rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
</head>
<body class="relative">
    <?php 
        include __DIR__.'/components/sidebar.php';
    ?>
    <div id="main" class="visually-hidden">
       
    <?php 
        include __DIR__.'/components/header.php';
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



  <!-- Table -->
   <div class="container my-5 p-4 bg-white rounded shadow">
  <h3 class="mb-4">Communication I</h3>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center">
      <thead class="table-light">
    
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
    <button class="btn btn-outline-dark open-email-modal me-1" data-bs-toggle="modal"
            data-bs-target="#emailModal" 
            data-clientid="<?= $row['client_id'] ?>" 
            data-email="<?= $row['email'] ?>" 
            data-name="<?= $row['firstname'] . ' ' . $row['lastname'] ?>">
        <i class="bi bi-envelope-fill"></i>
    </button>
    <!-- Show References Button -->
  <button type="button"
    class="btn btn-outline-dark show-ref "
    data-bs-toggle="popover"
    data-bs-trigger="focus"
    data-bs-placement="left"
    title="Client References"
    data-bs-html="true"
    data-bs-content="
        <?php
            $clientId = $row['client_id'];
            $refQuery = $conn->query("SELECT * FROM client_references WHERE client_id = $clientId");
            if ($refQuery->num_rows > 0) {
                while ($ref = $refQuery->fetch_assoc()) {
                    echo "<strong>Name:</strong> " . htmlspecialchars($ref['firstname'] . ' ' . $ref['lastname']) . "<br>";
                    echo "<strong>Relationship:</strong> " . htmlspecialchars($ref['relationship']) . "<br>";
                    echo "<strong>Contact:</strong> " . htmlspecialchars($ref['contact']) . "<br>";
                    echo "<strong>Email:</strong> " . htmlspecialchars($ref['email']) . "<hr>";
                }
            } else {
                echo "No references found.";
            }
        ?>
    ">
    <i class="bi bi-person-lines-fill"></i>
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
document.addEventListener("DOMContentLoaded", () => {
  const refButtons = document.querySelectorAll('.show-ref');

  // Enable popovers
  refButtons.forEach(btn => {
    new bootstrap.Popover(btn, {
      container: 'body',
      html: true,
      sanitize: false
    });

    // Center the popover when shown
    btn.addEventListener('shown.bs.popover', () => {
      const pop = document.querySelector('.popover:last-of-type');
      if (pop) pop.classList.add('center-screen');
    });
  });
});
</script>



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

    </div>

    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
