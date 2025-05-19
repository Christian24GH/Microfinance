<?php 
// Start output buffering and session safely
ob_start();

include 'db_connect.php';

$i = 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';;
$successMsg = '';
$errorMsg = '';

if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// Fetch New Hired Applicants
$sql = "
    SELECT 
        CONCAT(a.firstname, ' ', a.lastname) AS full_name, 
        p.position_name AS position, 
        a.date_created 
    FROM application a
    JOIN positions p ON a.position_id = p.position_id 
    JOIN recruitment_status rs ON a.process_id = rs.id 
    WHERE rs.status_label = 'Hired' 
";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= "
        AND (
            a.firstname LIKE '%$search%' 
            OR a.lastname LIKE '%$search%'
            OR p.position_name LIKE '%$search%'
        )
    ";
}

$sql .= " ORDER BY a.date_created DESC";

$result = mysqli_query($conn, $sql);
$Newhired = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle form submission to insert new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userEmail'])) {
    $fullName = $conn->real_escape_string($_POST['userFullName']);
    $email = $conn->real_escape_string($_POST['userEmail']);
    $username = $conn->real_escape_string($_POST['userUsername']);
    $password = password_hash($_POST['userPassword'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['userRole']);

    $query = "INSERT INTO employee_users (full_name, email, username, password, role)
              VALUES ('$fullName', '$email', '$username', '$password', '$role')";

    if ($conn->query($query)) {
        $successMsg = 'Account created successfully.';
    } else {
        $errorMsg = 'Error: ' . $conn->error;
    }   
}

// Fetch users
$users = [];
$getquery = "SELECT id, full_name, email, username, role, status FROM employee_users";
$result = $conn->query($getquery);
if ($result && $result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}

// Document/resume list
$docQuery = "
SELECT 
  a.id,
  a.resume_path,
  a.date_created,
  CONCAT(a.firstname, ' ', a.lastname) AS full_name
FROM application a
WHERE a.resume_path != ''
ORDER BY a.date_created DESC
";
$docResult = $conn->query($docQuery);

$a = 1;

// Handle resume deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_id'])) {
    $resumeId = intval($_POST['resume_id']);

    // Get the file path
    $getPathQuery = "SELECT resume_path FROM application WHERE id = $resumeId";
    $result = $conn->query($getPathQuery);

    if ($result && $result->num_rows > 0) {
        $resume = $result->fetch_assoc();
        $filePath = $resume['resume_path'];

        // Delete file from server
        if (!empty($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove resume_path from database
        $updateQuery = "UPDATE application SET resume_path = '' WHERE id = $resumeId";
        $conn->query($updateQuery);
    }

    // Safely redirect after deleting
    header("Location: sampleNewhired.php");
    exit;
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
  $deleteUserId = intval($_POST['delete_user_id']);

  $deleteQuery = "DELETE FROM employee_users WHERE id = $deleteUserId";

  if ($conn->query($deleteQuery)) {
      $successMsg = "User deleted successfully.";
  } else {
      $errorMsg = "Failed to delete user: " . $conn->error;
  }

  // Prevent form resubmission on refresh
  header("Location: index.php?page=sampleNewhired");
  exit;
}

$conn->close();
ob_end_flush();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Hired On Board</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    .right-panel-scrollable {
      max-height: 100vh;
      overflow-y: auto;
      position: sticky;
      top: 0;
    }
  </style>
</head>
<body class="bg-light">

<div class="container my-5">
  <div class="row">

    <!-- New Hired List Table -->
    <div class="col-md-7 left-panel-scrollable">
      <div class="card p-4 shadow-sm mb-4">
        <h5 class="mb-4">List of New Hired</h5>
        <div class="card z-depth-0">
          <!-- Search Form -->
          <div class="container my-3">
            <form method="GET" class="row g-3 align-items-center" action="index.php">
              <input type="hidden" name="page" value="sampleNewhired">
              <div class="col-auto">
                <input type="text" name="search" class="form-control" placeholder="Search by name or position" value="<?php echo htmlspecialchars($search); ?>">
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
            </form>
          </div>
          <table class="table table-bordered">
            <thead class="table-dark text-center">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Position</th>
                <th>Date Hired</th>
              </tr>
            </thead>
            <tbody class="text-center">
            <?php foreach($Newhired as $hired) { ?>
            <tr>
              <td><?php echo $i++ ?></td>
              <td><?php echo ($hired['full_name']); ?></td>
              <td><?php echo ($hired['position']); ?></td>
              <td><?php echo ($hired['date_created']); ?></td>
            </tr>
            <?php } ?>

            </tbody>
          </table>   
        </div>  
      </div>
    </div>

    <!-- Right Panel -->
    <div class="col-md-5 right-panel-scrollable">
      <!-- Training Schedule Card -->
      <div class="card mb-4 p-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold mb-0">Training Schedule</h6>
          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trainingModal">View</button>
        </div>
        <table class="table table-sm table-bordered mb-0">
          <thead class="table-dark text-center">
            <tr>
              <th>Title</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td>Onboarding Orientation</td>
              <td>Ongoing</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Learning Plan Card -->
      <div class="card p-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold mb-0">Learning Plan</h6>
          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#learningModal">View</button>
        </div>
        <table class="table table-sm table-bordered mb-0">
          <thead class="table-dark text-center">
            <tr>
              <th>Title</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td>Workplace Safety</td>
              <td>In Progress</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Document Management -->
        <div class="card p-3 shadow-sm mb-4">
          <h6 class="fw-bold mb-3">Document Management</h6>
          <div class="table-responsive"> <!-- Enables horizontal scroll on smaller screens -->
            <table class="table table-sm table-bordered mb-2">
              <thead class="table-dark text-center">
                <tr>
                  <th style="width: 40px;">#</th>
                  <th style="max-width: 300px;">Document</th>
                  <th style="width: 120px;">Date</th>
                  <th style="width: 250px;">Actions</th>
                </tr>
              </thead>
              <tbody class="align-middle text-center">
                <?php if ($docResult && $docResult->num_rows > 0): ?>
                  <?php while ($doc = $docResult->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo $a++; ?></td>
                      <td style="word-break: break-word; max-width: 300px; white-space: normal;">
                        <?php echo htmlspecialchars(basename($doc['resume_path'])); ?>
                      </td>
                      <td><?php echo date('Y-m-d', strtotime($doc['date_created'])); ?></td>
                      <td>
                        <a href="<?php echo $doc['resume_path']; ?>" class="btn btn-sm btn-primary mb-1" download>Download</a>
                        <button 
                          class="btn btn-sm btn-info mb-1" 
                          data-bs-toggle="modal" 
                          data-bs-target="#viewInfoModal" 
                          data-name="<?php echo htmlspecialchars($doc['full_name']); ?>"
                          data-date="<?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($doc['date_created']))); ?>"
                        >View Info</button>

                        <form action="delete_resume.php" method="POST" class="d-inline">
                          <input type="hidden" name="resume_id" value="<?php echo $doc['id']; ?>">
                          <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Delete this resume?')">Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="4">No resumes found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- View Info Modal -->
        <div class="modal fade" id="viewInfoModal" tabindex="-1" aria-labelledby="viewInfoLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
            
              <div class="modal-header">
                <h5 class="modal-title" id="viewInfoLabel">Document Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              
              <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalDocName"></span></p>
                <p><strong>Date Uploaded:</strong> <span id="modalDocDate"></span></p>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div>

      <!-- Employee User Accounts -->
      <div class="card p-3 shadow-sm">
        <h6 class="fw-bold mb-3">Employee User Accounts</h6>
        <table class="table table-sm table-bordered mb-2">
          <thead class="table-dark text-center">
            <tr>
              <th>Name</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="">
          <?php foreach ($users as $user) { ?>
            <tr>
              <td><?php echo htmlspecialchars($user['full_name']); ?></td>
              <td><?php echo htmlspecialchars($user['role']); ?></td>
              <td><?php echo ($user['status']); ?></td>
              <td>
                <button class="btn btn-sm btn-warning btn-edit-user"
                  data-user='<?= json_encode($user, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>Edit</button>
                  <form method="POST" action="sampleNewhired.php" onsubmit="return confirm('Are you sure you want to delete this user?');" class="d-inline">
                    <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAccountModal">Create User Account</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit User Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm">
          <input type="hidden" id="editUserId">
          <div class="mb-3">
            <label for="editUserFullName" class="form-label">Full Name:</label>
            <input type="text" class="form-control" id="editUserFullName">
          </div>
          <div class="mb-3">
            <label for="editUserEmail" class="form-label">Email:</label>
            <input type="email" class="form-control" id="editUserEmail">
          </div>
          <div class="mb-3">
            <label for="editUserUsername" class="form-label">Username:</label>
            <input type="text" class="form-control" id="editUserUsername">
          </div>
          <div class="mb-3">
            <label for="editUserRole" class="form-label">Role:</label>
            <select class="form-select" id="editUserRole">
              <option value="admin">Admin</option>
              <option value="employee">Employee</option>
              <option value="manager">Manager</option>
              <option value="hr">HR</option>
            </select>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Create Account Modal -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createAccountModalLabel">Create User Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card p-4 mb-4 shadow-sm">
          <form method="POST">
            <div class="mb-3">
              <label for="userFullName" class="form-label">Full Name:</label>
              <input type="text" class="form-control" id="userFullName" name="userFullName" required>
            </div>
            <div class="mb-3">
              <label for="userEmail" class="form-label">Email:</label>
              <input type="email" class="form-control" id="userEmail" name="userEmail" required>
            </div>
            <div class="mb-3">
              <label for="userUsername" class="form-label">Username:</label>
              <input type="text" class="form-control" id="userUsername" name="userUsername" required>
            </div>
            <div class="mb-3">
              <label for="userPassword" class="form-label">Password:</label>
              <input type="password" class="form-control" id="userPassword" name="userPassword" required>
            </div>
            <div class="mb-3">
              <label for="userRole" class="form-label">Role:</label>
              <select class="form-select" id="userRole" name="userRole" required>
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
                <option value="manager">Manager</option>
                <option value="hr">HR</option>
              </select>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-success">Create Account</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Training Modal -->
<div class="modal fade" id="trainingModal" tabindex="-1" aria-labelledby="trainingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="trainingModalLabel">Training Participants</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Training Title</th>
              <th>Participant</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Onboarding Orientation</td>
              <td>Maria Santos</td>
              <td>2025-05-20</td>
              <td>Scheduled</td>
            </tr>
            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Learning Modal -->
<div class="modal fade" id="learningModal" tabindex="-1" aria-labelledby="learningModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="learningModalLabel">Learning Plan Participants</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Learning Title</th>
              <th>Participant</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Workplace Safety</td>
              <td>Maria Santos</td>
              <td>2025-05-25</td>
              <td>In Progress</td>
            </tr>
            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Event delegation to handle Edit button click
  document.addEventListener('click', function (e) {
    if (e.target && e.target.matches('.btn-edit-user')) {
      const user = JSON.parse(e.target.getAttribute('data-user'));

      document.getElementById('editUserId').value = user.id;
      document.getElementById('editUserFullName').value = user.full_name;
      document.getElementById('editUserEmail').value = user.email;
      document.getElementById('editUserUsername').value = user.username;
      document.getElementById('editUserRole').value = user.role;

      const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
      editModal.show();
        }
      });

      document.addEventListener('DOMContentLoaded', function () {
        const viewInfoModal = document.getElementById('viewInfoModal');
        const modalName = document.getElementById('modalDocName');
        const modalDate = document.getElementById('modalDocDate');

        viewInfoModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          const name = button.getAttribute('data-name');
          const date = button.getAttribute('data-date');

          modalName.textContent = name;
          modalDate.textContent = date;
        });
      });
      
</script>

</body>
</html>
