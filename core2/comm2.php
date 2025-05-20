<?php
include __DIR__.'/session.php';
require_once 'config/db.php'; // Database connection

$today = date("Y-m-d");

$sql = "SELECT COUNT(*) AS total_sent FROM email_logs WHERE send_date >= ? AND send_date <= ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$start = $today . " 00:00:00";
$end = $today . " 23:59:59";

$stmt->bind_param("ss", $start, $end);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$emailCount = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $emailCount = $row['total_sent'] ?? 0;
} else {
    die("Get result failed: " . $stmt->error);
}

$stmt->close();
$sql = "
    SELECT 
        r.client_ref_id,
        r.client_id,
        r.fr_first_name, r.fr_last_name, r.fr_relationship, r.fr_contact_number, r.fr_email,
        r.sr_first_name, r.sr_last_name, r.sr_relationship, r.sr_contact_number, r.sr_email,
        c.first_name, c.middle_name, c.last_name, c.email, c.contact_number, c.city
    FROM client_references r
    LEFT JOIN client_info c ON r.client_id = c.client_id
    ORDER BY r.sr_relationship ASC
";
$result = $conn->query($sql);
?>
<?php
require_once 'config/db.php';

// Get search term from GET, sanitize it for SQL
$search = '';
$where = '';
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search = $conn->real_escape_string(trim($_GET['search']));
    
    // Search fields in both tables: client_references and client_info
    $where = " WHERE 
        r.fr_first_name LIKE '%$search%' OR r.fr_last_name LIKE '%$search%' OR r.fr_relationship LIKE '%$search%' OR r.fr_email LIKE '%$search%' OR
        r.sr_first_name LIKE '%$search%' OR r.sr_last_name LIKE '%$search%' OR r.sr_relationship LIKE '%$search%' OR r.sr_email LIKE '%$search%' OR
        c.first_name LIKE '%$search%' OR c.middle_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR c.email LIKE '%$search%' OR c.city LIKE '%$search%'
    ";
}

// Build SQL with optional WHERE
$sql = "
    SELECT 
        r.client_ref_id,
        r.client_id,
        r.fr_first_name, r.fr_last_name, r.fr_relationship, r.fr_contact_number, r.fr_email,
        r.sr_first_name, r.sr_last_name, r.sr_relationship, r.sr_contact_number, r.sr_email,
        c.first_name, c.middle_name, c.last_name, c.email, c.contact_number, c.city
    FROM client_references r
    LEFT JOIN client_info c ON r.client_id = c.client_id
    $where
    ORDER BY r.sr_relationship ASC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Communication 2</title>
    <link href="./resources/css/app.css" rel="stylesheet" />
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
    </style>
</head>
<body class="relative">
    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div id="main" class="visually-hidden">
        <?php include __DIR__ . '/components/header.php'; ?>


        <div class="container my-5 p-4 bg-white rounded shadow">
            <h3 class="mb-4">Communication II</h3>

<form method="GET" action="" class="mb-4" id="search-form">
    <div class="input-group">
        <input type="search" id="search-input" name="search" class="form-control" placeholder="Search by name, relationship, or email" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
        <button class="btn btn-outline-dark" type="submit" id="search-btn">
            <i class="bi <?= isset($_GET['search']) && $_GET['search'] !== '' ? 'bi-x-circle' : 'bi-search' ?>"></i>
            <span id="search-btn-text"><?= isset($_GET['search']) && $_GET['search'] !== '' ? 'Clear' : 'Search' ?></span>
        </button>
    </div>
</form>

        
<!-- First References Table -->
<div class="table-responsive mb-5">
    <h4>First References</h4>
    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
            <tr>
                <th><input type="checkbox" id="select-all-fr" /></th>
                <th>Client Ref ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Relationship</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="fr-table-body">
            <?php if ($result->num_rows > 0): ?>
                <?php
                // Reset result pointer to start for second loop
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td>
                        <input
                            type="checkbox"
                            class="select-user-fr"
                            data-clientid="<?= htmlspecialchars($row['client_ref_id']) ?>"
                            data-email="<?= htmlspecialchars($row['fr_email']) ?>"
                            data-name="<?= htmlspecialchars($row['fr_first_name'] . ' ' . $row['fr_last_name']) ?>"
                        />
                    </td>
                    <th scope="row"><?= htmlspecialchars($row['client_ref_id']) ?></th>
                    <td><?= htmlspecialchars($row['fr_first_name']) ?></td>
                    <td><?= htmlspecialchars($row['fr_last_name']) ?></td>
                    <td><?= htmlspecialchars($row['fr_relationship']) ?></td>
                    <td><?= htmlspecialchars($row['fr_contact_number']) ?></td>
                    <td><?= htmlspecialchars($row['fr_email']) ?></td>
                    <td>
                        <!-- Email Button -->
                        <button
                            class="btn btn-outline-dark open-email-modal"
                            data-bs-toggle="modal"
                            data-bs-target="#emailModal"
                            data-clientid="<?= htmlspecialchars($row['client_ref_id']) ?>"
                            data-email="<?= htmlspecialchars($row['fr_email']) ?>"
                            data-name="<?= htmlspecialchars($row['fr_first_name'] . ' ' . $row['fr_last_name']) ?>"
                            title="Send Email"
                        >
                            <i class="bi bi-envelope-fill"></i>
                        </button>

                        <!-- Client Info Popover -->
                        <button
                            type="button"
                            class="btn btn-outline-dark"
                            data-bs-toggle="popover"
                            data-bs-html="true"
                            data-bs-trigger="focus"
                            title="Client Info"
                            data-bs-content="
                                <strong>Name:</strong> <?= htmlspecialchars($row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name']) ?><br>
                                <strong>Email:</strong> <?= htmlspecialchars($row['email']) ?><br>
                                <strong>Contact:</strong> <?= htmlspecialchars($row['contact_number']) ?><br>
                                <strong>City:</strong> <?= htmlspecialchars($row['city']) ?><br>
                                <strong>Client ID:</strong> <?= htmlspecialchars($row['client_id']) ?>
                            "
                        >
                            <i class="bi bi-person-circle"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Second References Table -->
<div class="table-responsive mb-5">
    <h4>Second References</h4>
    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
            <tr>
                <th><input type="checkbox" id="select-all-sr" /></th>
                <th>Client Ref ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Relationship</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="sr-table-body">
            <?php if ($result->num_rows > 0): ?>
                <?php
                // Reset result pointer again for second loop
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td>
                        <input
                            type="checkbox"
                            class="select-user-sr"
                            data-clientid="<?= htmlspecialchars($row['client_ref_id']) ?>"
                            data-email="<?= htmlspecialchars($row['sr_email']) ?>"
                            data-name="<?= htmlspecialchars($row['sr_first_name'] . ' ' . $row['sr_last_name']) ?>"
                        />
                    </td>
                    <th scope="row"><?= htmlspecialchars($row['client_ref_id']) ?></th>
                    <td><?= htmlspecialchars($row['sr_first_name']) ?></td>
                    <td><?= htmlspecialchars($row['sr_last_name']) ?></td>
                    <td><?= htmlspecialchars($row['sr_relationship']) ?></td>
                    <td><?= htmlspecialchars($row['sr_contact_number']) ?></td>
                    <td><?= htmlspecialchars($row['sr_email']) ?></td>
                    <td>
                        <!-- Email Button -->
                        <button
                            class="btn btn-outline-dark open-email-modal"
                            data-bs-toggle="modal"
                            data-bs-target="#emailModal"
                            data-clientid="<?= htmlspecialchars($row['client_ref_id']) ?>"
                            data-email="<?= htmlspecialchars($row['sr_email']) ?>"
                            data-name="<?= htmlspecialchars($row['sr_first_name'] . ' ' . $row['sr_last_name']) ?>"
                            title="Send Email"
                        >
                            <i class="bi bi-envelope-fill"></i>
                        </button>

                        <!-- Client Info Popover -->
                        <button
                            type="button"
                            class="btn btn-outline-dark"
                            data-bs-toggle="popover"
                            data-bs-html="true"
                            data-bs-trigger="focus"
                            title="Client Info"
                            data-bs-content="
                                <strong>Name:</strong> <?= htmlspecialchars($row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name']) ?><br>
                                <strong>Email:</strong> <?= htmlspecialchars($row['email']) ?><br>
                                <strong>Contact:</strong> <?= htmlspecialchars($row['contact_number']) ?><br>
                                <strong>City:</strong> <?= htmlspecialchars($row['city']) ?><br>
                                <strong>Client ID:</strong> <?= htmlspecialchars($row['client_id']) ?>
                            "
                        >
                            <i class="bi bi-person-circle"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bulk Email Button -->
<button id="send-email-btn" class="btn btn-outline-secondary mt-3" data-bs-toggle="modal" data-bs-target="#emailModal" style="display:none;">
    Send Bulk Email
</button>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="mail.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Send Email to Clients</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="client_ids" id="modal-client-ids" />

                    <div class="mb-3">
                        <label for="modal-email" class="form-label">To</label>
                        <input type="text" class="form-control" id="modal-email" name="email" readonly required />
                    </div>

                    <div class="mb-3">
                        <label for="modal-subject" class="form-label">Subject</label>
                        <select class="form-select" id="modal-subject" name="subject" required>
                            <option value="" disabled selected>Select a subject</option>
                            <option value="Repayment Reminder">Repayment Reminder</option>
                            <option value="System Update">System Update</option>
                        </select>
                        <div class="invalid-feedback">Please select a subject.</div>
                    </div>

                    <div class="mb-3">
                        <label for="modal-message" class="form-label">Message</label>
                        <textarea class="form-control" id="modal-message" name="message" rows="4" required></textarea>
                        <div class="invalid-feedback">Please enter your message.</div>
                    </div>

                    <div class="mb-3">
                        <label for="modal-attachment" class="form-label">Attach an Image (optional)</label>
                        <input type="file" class="form-control" id="modal-attachment" name="attachment" accept="image/*" />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-secondary">Send Email</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/sidebar.js"></script>

    
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize all Bootstrap popovers
    const popoverTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(el => new bootstrap.Popover(el));

    const sendEmailBtn = document.getElementById('send-email-btn');
    const modalEmailInput = document.getElementById('modal-email');
    const modalClientIds = document.getElementById('modal-client-ids');

    const checkboxesFr = document.querySelectorAll('.select-user-fr');
    const checkboxesSr = document.querySelectorAll('.select-user-sr');

    // Select All functionality
    document.getElementById('select-all-fr').addEventListener('change', function () {
        checkboxesFr.forEach(cb => cb.checked = this.checked);
        updateSelectedUsers();
    });

    document.getElementById('select-all-sr').addEventListener('change', function () {
        checkboxesSr.forEach(cb => cb.checked = this.checked);
        updateSelectedUsers();
    });

    // Handle individual checkbox changes
    [...checkboxesFr, ...checkboxesSr].forEach(cb => {
        cb.addEventListener('change', updateSelectedUsers);
    });

    // Update email and client ID fields in the modal
    function updateSelectedUsers() {
        const selected = [...checkboxesFr, ...checkboxesSr].filter(cb => cb.checked);
        const emails = selected.map(cb => cb.dataset.email).filter(Boolean);
        const clientIds = selected.map(cb => cb.dataset.clientid).filter(Boolean);

        if (emails.length > 0) {
            sendEmailBtn.style.display = 'inline-block';
            sendEmailBtn.onclick = () => {
                modalEmailInput.value = emails.join(', ');
                modalClientIds.value = clientIds.join(', ');
            };
        } else {
            sendEmailBtn.style.display = 'none';
            modalEmailInput.value = '';
            modalClientIds.value = '';
        }
    }

    // Handle direct open modal buttons (for single users)
    document.querySelectorAll('.open-email-modal').forEach(button => {
        button.addEventListener('click', () => {
            modalEmailInput.value = button.dataset.email;
            modalClientIds.value = button.dataset.clientid;
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('search-form');
    const input = document.getElementById('search-input');
    const btn = document.getElementById('search-btn');
    const btnText = document.getElementById('search-btn-text');
    const icon = btn.querySelector('i');

    const isSearchFilled = input.value.trim() !== '';

    if (isSearchFilled) {
        // Already in "Clear" mode on load
        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-outline-secondary');
    }

    btn.addEventListener('click', function (e) {
        if (btnText.textContent === 'Clear') {
            // Prevent form submission
            e.preventDefault();

            // Clear input and resubmit the form without the search query
            input.value = '';
            form.submit(); // Triggers GET without `search` param
        }
        // If it's "Search", let the form submit normally
    });
});
</script>


</body>
</html> 
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/sidebar.js"></script>