<?php
    include __DIR__.'/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication 2</title>
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
<?php
require_once 'config/db.php'; // Connect to DB using db.php

// Updated SQL: join client_info to get referrer firstname and lastname
$sql = "
    SELECT 
        r.client_ref_id, r.firstname, r.lastname, r.relationship, r.contact, r.email, r.client_id,
        c.firstname AS referrer_firstname,
        c.lastname AS referrer_lastname,
        c.contact AS referrer_contact,
        c.email AS referrer_email,
        c.address AS referrer_address
    FROM client_references r
    LEFT JOIN client_info c ON r.client_id = c.client_id
    ORDER BY r.email ASC
";

$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!-- Bootstrap card -->
   <div class="container my-5 p-4 bg-white rounded shadow">
  <h3 class="mb-4">Communication II</h3>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center">
      <thead class="table-light">
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="client-table-body">
                <?php if ($result->num_rows > 0): 
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                              <input type="checkbox" class="select-user" 
                                     data-clientid="<?= htmlspecialchars($row['client_ref_id']) ?>" 
                                     data-email="<?= htmlspecialchars($row['email']) ?>" 
                                     data-name="<?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>">
                            </td>
                            <th scope="row"><?= htmlspecialchars($row['client_ref_id']) ?></th>
                            <td><?= htmlspecialchars($row['firstname']) ?></td>
                            <td><?= htmlspecialchars($row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['relationship']) ?></td>
                            <td><?= htmlspecialchars($row['contact']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <!-- Email Button -->
                                <button class="btn btn-outline-dark open-email-modal" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#emailModal" 
                                        data-clientid="<?= htmlspecialchars($row['client_ref_id']) ?>" 
                                        data-email="<?= htmlspecialchars($row['email']) ?>" 
                                        data-name="<?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>">
                                    <i class="bi bi-envelope-fill"></i> 
                                </button>

                                <!-- Popover Button for Referrer -->
                        <!-- Popover Button with Full Info -->
<button type="button" 
    class="btn btn-outline-dark" 
    data-bs-toggle="popover" 
    data-bs-placement="top" 
    data-bs-trigger="focus"
    title="Referrer Info"
    data-bs-html="true"
    data-bs-content="
        <strong>Name:</strong> <?= htmlspecialchars($row['referrer_firstname'] . ' ' . $row['referrer_lastname']) ?><br>
        <strong>Contact:</strong> <?= htmlspecialchars($row['referrer_contact']) ?><br>
        <strong>Email:</strong> <?= htmlspecialchars($row['referrer_email']) ?><br>
        <strong>Address:</strong> <?= htmlspecialchars($row['referrer_address']) ?>
    ">
    <i class="bi bi-person-lines-fill"></i>
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

<!-- Bootstrap 5 Popover Initialization -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    popoverTriggerList.forEach(function (el) {
        new bootstrap.Popover(el)
    })
});

</script>

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

    </div>

    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
