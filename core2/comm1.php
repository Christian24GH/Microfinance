<?php
include __DIR__.'/session.php';
require_once 'config/db.php'; // Database connection

// Fetch clients from database
$sql = "SELECT client_id, first_name, middle_name, last_name, sex, civil_status, birthdate, contact_number, email, address, barangay, city, province, registration_date, status FROM client_info";
$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<?php
require_once 'config/db.php'; // Database connection

$searchTerm = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
}

// Prepare SQL query with search filter if any
if ($searchTerm !== '') {
    // Use LIKE to search in client_info fields and references (joined)
    $sql = "SELECT ci.client_id, ci.first_name, ci.middle_name, ci.last_name, ci.sex, ci.civil_status, ci.birthdate, ci.contact_number, ci.email, ci.address, ci.barangay, ci.city, ci.province, ci.registration_date, ci.status
            FROM client_info ci
            LEFT JOIN client_references cr ON ci.client_id = cr.client_id
            WHERE ci.first_name LIKE ? OR ci.middle_name LIKE ? OR ci.last_name LIKE ? OR ci.email LIKE ? 
               OR cr.fr_first_name LIKE ? OR cr.fr_last_name LIKE ? OR cr.sr_first_name LIKE ? OR cr.sr_last_name LIKE ?
            GROUP BY ci.client_id";
    $stmt = $conn->prepare($sql);
    $likeSearchTerm = "%$searchTerm%";
    $stmt->bind_param("ssssssss", $likeSearchTerm, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm, $likeSearchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT client_id, first_name, middle_name, last_name, sex, civil_status, birthdate, contact_number, email, address, barangay, city, province, registration_date, status FROM client_info";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Communication 1</title>

    <!-- Stylesheets -->
    <link href="./resources/css/app.css" rel="stylesheet" />
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

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
        .card-mfc1 { background-color: var(--mfc1); color: var(--mfc8); }
        .card-mfc2 { background-color: var(--mfc2); color: var(--mfc8); }
        .card-mfc3 { background-color: var(--mfc3); color: var(--mfc8); }
        .card-mfc4 { background-color: var(--mfc4); color: var(--mfc8); }

        .table-header-custom {
            background-color: var(--mfc3);
            color: var(--mfc8);
        }

        .card-shadow {
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        .status-active { color: green; font-weight: bold; text-transform: capitalize; }
        .status-pending { color: orange; font-weight: bold; text-transform: capitalize; }
        .status-inactive { color: gray; font-weight: bold; text-transform: capitalize; }
        .status-accepted { color: blue; font-weight: bold; text-transform: capitalize; }
        .status-denied { color: red; font-weight: bold; text-transform: capitalize; }
        .status-flagged { color: purple; font-weight: bold; text-transform: capitalize; }

        /* Optional: center popover */
        .popover.center-screen {
            left: 50% !important;
            transform: translateX(-50%) !important;
        }
    </style>
</head>
<body class="relative">

    <?php include __DIR__ . '/components/sidebar.php'; ?>

    <div id="main" class="visually-hidden">
        <?php include __DIR__ . '/components/header.php'; ?>


        <div class="container my-5 p-4 bg-white rounded shadow">
            <h3 class="mb-4">Communication I</h3>
<form method="GET" action="" class="mb-4" id="search-form">
    <div class="input-group">
        <input type="search" id="search-input" name="search" class="form-control" placeholder="Search by name, relationship, or email" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
        <button class="btn btn-outline-dark" type="submit" id="search-btn">
            <i class="bi <?= isset($_GET['search']) && $_GET['search'] !== '' ? 'bi-x-circle' : 'bi-search' ?>"></i>
            <span id="search-btn-text"><?= isset($_GET['search']) && $_GET['search'] !== '' ? 'Clear' : 'Search' ?></span>
        </button>
    </div>
</form>


            <!-- Filter Dropdown -->
            <div class="mb-3">
                <label for="status-filter" class="form-label">Filter</label>
                <select class="form-select" id="status-filter" aria-label="Filter clients by status">
                    <option value="all">All</option>
                    <option value="active" class="status-active">Active</option>
                    <option value="accepted" class="status-accepted">Accepted</option>
                    <option value="denied" class="status-denied">Denied</option>
                    <option value="flagged" class="status-flagged">Flagged</option>
                    <option value="inactive" class="status-inactive">Inactive</option>
                    <option value="pending" class="status-pending">Pending</option>
                </select>
            </div>

            <!-- Clients Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light table-header-custom">
                        <tr>
                            <th><input type="checkbox" id="select-all" aria-label="Select all clients"></th>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="client-table-body">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()):
                                $clientId = (int)$row['client_id'];
                                $status = strtolower(htmlspecialchars($row['status']));
                                $firstName = htmlspecialchars($row['first_name']);
                                $middleName = htmlspecialchars($row['middle_name']);
                                $lastName = htmlspecialchars($row['last_name']);
                                $email = htmlspecialchars($row['email']);

                                // Fetch references for this client
                                $refContent = "No references found.";
                                $stmt = $conn->prepare("SELECT * FROM client_references WHERE client_id = ?");
                                $stmt->bind_param("i", $clientId);
                                $stmt->execute();
                                $refResult = $stmt->get_result();
                                if ($refResult && $refResult->num_rows > 0) {
                                    $refContent = "";
                                    while ($ref = $refResult->fetch_assoc()) {
                                        $refContent .= "<strong>Primary Reference Name:</strong> " .
                                            htmlspecialchars($ref['fr_first_name'] . ' ' . $ref['fr_last_name']) . "<br>";
                                        $refContent .= "<strong>Relationship:</strong> " . htmlspecialchars($ref['fr_relationship']) . "<br>";
                                        $refContent .= "<strong>Contact Number:</strong> " . htmlspecialchars($ref['fr_contact_number']) . "<br><br>";

                                        $refContent .= "<strong>Secondary Reference Name:</strong> " .
                                            htmlspecialchars($ref['sr_first_name'] . ' ' . $ref['sr_last_name']) . "<br>";
                                        $refContent .= "<strong>Relationship:</strong> " . htmlspecialchars($ref['sr_relationship']) . "<br>";
                                        $refContent .= "<strong>Contact Number:</strong> " . htmlspecialchars($ref['sr_contact_number']) . "<hr>";
                                    }
                                }
                                $stmt->close();
                            ?>
                                <tr data-status="<?= $status ?>">
                                    <td>
                                        <input type="checkbox" class="select-user" data-clientid="<?= $clientId ?>" data-email="<?= $email ?>" aria-label="Select client <?= $firstName . ' ' . $lastName ?>">
                                    </td>
                                    <th scope="row"><?= $clientId ?></th>
                                    <td><?= $firstName ?></td>
                                    <td><?= $middleName ?></td>
                                    <td><?= $lastName ?></td>
                                    <td><?= $email ?></td>
                                    <td class="status-<?= $status ?>"><?= ucfirst($status) ?></td>
                                    <td>
                                        <!-- Email Button -->
                                        <button
                                            class="btn btn-outline-dark open-email-modal me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#emailModal"
                                            data-clientid="<?= $clientId ?>"
                                            data-email="<?= $email ?>"
                                            data-name="<?= $firstName . ' ' . $lastName ?>"
                                            aria-label="Send email to <?= $firstName . ' ' . $lastName ?>"
                                        >
                                            <i class="bi bi-envelope-fill"></i>
                                        </button>

                                        <!-- Reference Popover Button -->
                                        <button
                                            type="button"
                                            class="btn btn-outline-dark show-ref"
                                            data-bs-toggle="popover"
                                            data-bs-trigger="focus"
                                            data-bs-placement="left"
                                            title="Client References"
                                            data-bs-html="true"
                                            data-bs-content="<?= htmlspecialchars($refContent, ENT_QUOTES) ?>"
                                            aria-label="View references for <?= $firstName . ' ' . $lastName ?>"
                                        >
                                            <i class="bi bi-person-lines-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Send Bulk Email Button (hidden initially) -->
            <button id="send-email-btn" class="btn btn-outline-secondary mt-3" data-bs-toggle="modal" data-bs-target="#emailModal" style="display:none;">
                Send Bulk Email
            </button>
        </div>
    </div>
   <!-- Email Modal -->
<form id="email-form" action="mail.php" method="POST" enctype="multipart/form-data">
  <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="emailModalLabel">Send Email</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="email-to" class="form-label">Send to:</label>
            <input type="text" class="form-control" id="email-to" readonly>
            <input type="hidden" name="client_ids" id="recipient_ids">
          </div>

          <div class="mb-3">
            <label for="subject" class="form-label">Subject:</label>
            <input type="text" name="subject" id="subject" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Message:</label>
            <textarea name="message" id="message" class="form-control" rows="6" required></textarea>
          </div>

          <div class="mb-3">
            <label for="attachment" class="form-label">Attachment (optional):</label>
            <input type="file" name="attachment" id="attachment" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send Email</button>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const filter = document.getElementById("status-filter");
    const tableBody = document.getElementById("client-table-body");
    const selectAllCheckbox = document.getElementById("select-all");
    const sendEmailBtn = document.getElementById("send-email-btn");
    const emailModal = document.getElementById("emailModal");
    const emailToInput = document.getElementById("email-to");
    const recipientIdsInput = document.getElementById("recipient_ids");
    const emailForm = document.getElementById("email-form");

    // Initialize Bootstrap Popovers
    const popoverTriggerList = [...document.querySelectorAll(".show-ref")];
    popoverTriggerList.forEach(el => new bootstrap.Popover(el));

    // Filter table rows based on status
    filter.addEventListener("change", () => {
        const filterValue = filter.value;
        const rows = tableBody.querySelectorAll("tr");
        rows.forEach(row => {
            if (filterValue === "all" || row.dataset.status === filterValue) {
                row.style.display = "";
            } else {
                row.style.display = "none";
                row.querySelector(".select-user").checked = false; // Deselect filtered-out rows
            }
        });
        selectAllCheckbox.checked = false;
        updateSendButtonVisibility();
    });

    // Select all toggle
    selectAllCheckbox.addEventListener("change", () => {
        const visibleRows = [...tableBody.querySelectorAll("tr")].filter(row => row.style.display !== "none");
        visibleRows.forEach(row => {
            const checkbox = row.querySelector(".select-user");
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateSendButtonVisibility();
    });

    // Handle individual checkbox toggles
    tableBody.addEventListener("change", (e) => {
        if (e.target.classList.contains("select-user")) {
            const visibleRows = [...tableBody.querySelectorAll("tr")].filter(row => row.style.display !== "none");
            const allChecked = visibleRows.every(row => row.querySelector(".select-user").checked);
            selectAllCheckbox.checked = allChecked;
            updateSendButtonVisibility();
        }
    });

    // Email button for single users
    document.querySelectorAll(".open-email-modal").forEach(button => {
        button.addEventListener("click", () => {
            const email = button.getAttribute("data-email");
            const clientId = button.getAttribute("data-clientid");

            emailToInput.value = email;
            recipientIdsInput.value = clientId;
        });
    });

    // Prepare bulk email modal
    sendEmailBtn.addEventListener("click", () => {
        const selectedCheckboxes = document.querySelectorAll(".select-user:checked");
        const emails = Array.from(selectedCheckboxes).map(cb => cb.dataset.email);
        const ids = Array.from(selectedCheckboxes).map(cb => cb.dataset.clientid);

        emailToInput.value = emails.join(", ");
        recipientIdsInput.value = ids.join(",");
    });

    // Update send button visibility
    function updateSendButtonVisibility() {
        const anyChecked = [...tableBody.querySelectorAll(".select-user")].some(cb => cb.checked && cb.closest("tr").style.display !== "none");
        sendEmailBtn.style.display = anyChecked ? "inline-block" : "none";
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('search-form');
    const input = document.getElementById('search-input');
    const btn = document.getElementById('search-btn');
    const btnText = document.getElementById('search-btn-text');
    const icon = btn.querySelector('i');

    btn.addEventListener('click', function (e) {
        if (btnText.textContent.trim() === 'Clear') {
            e.preventDefault(); // Prevent normal form submit
            input.value = '';   // Clear the input
            form.submit();      // Submit form with no `search`
        }
        // Otherwise, it's "Search" and should submit normally
    });
});
</script>



    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>