<?php
session_start();
include '../components/sidebar.php';
include '../config/disb_loan_conn.php';
include '../config/disb_loan_btnfunc.php';

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT * FROM disb_loan_tbl";
if ($status_filter) {
    $query .= " WHERE status = '$status_filter'";
}
$result = mysqli_query($connections, $query);

$borrower_query = "SELECT borrower_id FROM borrower_tbl";
$borrower_result = mysqli_query($connections, $borrower_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loan</title>

    <link href="../resources/css/app.css" rel="stylesheet">
    <link href="../resources/css/side.css" rel="stylesheet">
    <link href="../resources/css/accman.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="main-content">

        <!--edit modal-->
        <div class="modal fade" id="editdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editdataLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="editdataLabel">Edit Loan</h1>
                    </div>
                    <form action="../modal_function/disb_loanfunc.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <input type="hidden" name="loan_id" id="loan_id" class="form-control" placeholder="Loan ID">
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="tel"
                                    name="amount"
                                    id="amount"
                                    class="form-control"
                                    placeholder="Amount"
                                    maxlength="4"
                                    required
                                    title="Enter numbers only (max: 1000)"
                                    oninput="
                                    this.value = this.value.replace(/[^0-9]/g, '');
                                    if (parseInt(this.value) > 1000) this.value = '1000'; ">
                            </div>

                            <div class="mb-3">
                                <label for="interestRate" class="form-label">Interest Rate</label>
                                <input type="text"
                                    name="interestRate"
                                    id="interestRate"
                                    class="form-control"
                                    placeholder="Interest Rate"
                                    required
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            </div>

                            <div class="mb-3">
                                <label for="term" class="form-label">Term</label>
                                <input type="text" name="term" id="term" class="form-control" placeholder="Term">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" disabled selected>-- Select Status --</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_info" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--edit modal-->

        <!--delete-->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this record?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteForm" method="POST">
                            <input type="hidden" id="delete_id" name="id">
                            <button type="submit" name="confirm_delete_btn" class="btn btn-danger">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--delete-->

        <!--view modal-->
        <div class="modal fade" id="viewdata" tabindex="-1" aria-labelledby="viewdataLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="viewdataLabel">View Information</h1>
                    </div>
                    <div class="modal-body">
                        <div class="view_data"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--view modal-->

        <!--request modal-->
        <div class="modal fade" id="request" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="requestLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="requestLabel">Request Disbursement</h1>
                    </div>
                    <form action="../modal_function/disb_loanfunc.php" method="POST">
                        <div class="modal-body">

                            <div class="form-group mb-3">
                                <input type="hidden" name="loan_id" id="loan_id" class="form-control" placeholder="Loan ID" required>
                            </div>

                            <div class="mb-3">
                                <label for="borrower_id" class="form-label">Select Borrower ID</label>
                                <select name="borrower_id" id="borrower_id" class="form-select" required>
                                    <option value="" disabled selected>Select Borrower ID</option>
                                    <?php
                                    if (isset($borrower_result) && mysqli_num_rows($borrower_result) > 0) {
                                        while ($borrower = mysqli_fetch_assoc($borrower_result)) {
                                            echo "<option value='" . $borrower['borrower_id'] . "'>" . $borrower['borrower_id'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No loans available</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="tel"
                                    name="amount"
                                    id="amount"
                                    class="form-control"
                                    placeholder="Amount"
                                    maxlength="4"
                                    required
                                    title="Enter numbers only (max: 1000)"
                                    oninput="
                                    this.value = this.value.replace(/[^0-9]/g, '');
                                    if (parseInt(this.value) > 1000) this.value = '1000'; ">
                            </div>

                            <div class="form-group mb-3">
                                <label for="interestRate" class="form-label">Interest Rate</label>
                                <input type="text"
                                    name="interestRate"
                                    id="interestRate"
                                    class="form-control"
                                    placeholder="Interest Rate"
                                    required
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            </div>

                            <div class="form-group mb-3">
                                <label for="term" class="form-label">term</label>
                                <input type="date" name="term" id="term" class="form-control" placeholder="Term" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" disabled selected>-- Select Status --</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="request_disbursement" class="btn btn-success">Request Disbursement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--request modal-->

        <?php
        if (isset($_SESSION['status']) && is_array($_SESSION['status'])) {
            $status_message = $_SESSION['status']['message'];
            $status_class = $_SESSION['status']['class'];
        ?>
            <div class="alert <?php echo $status_class; ?> alert-dismissible fade show" role="alert" style="background-color: #f8f9fa; ">
                <strong>Hey!</strong> <?php echo $status_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
            unset($_SESSION['status']);
        }
        ?>

        <div class="container">
            <div class="row mt-5">
                <div class="col">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h2 class="text-center display-7">Loan</h2>
                            <div class="search-button">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#request"></i> Request</button>
                                &nbsp; &nbsp;
                                <div class="dropdown d-inline-block">
                                    <button class="btn dropdown-toggle" style="background-color: white; color: black;" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                        <li><a class="dropdown-item text-dark" href="?status=active">Active</a></li>
                                        <li><a class="dropdown-item text-dark" href="?status=inactive">Inactive</a></li>
                                        <li><a class="dropdown-item text-dark" href="?status=pending">Pending</a></li>
                                        <li><a class="dropdown-item text-dark" href="?status=approved">Approved</a></li>
                                        <li><a class="dropdown-item text-dark" href="?status=rejected">Rejected</a></li>
                                        <li><a class="dropdown-item text-dark" href="?status=closed">Closed</a></li>
                                    </ul>
                                </div>
                                <form class="d-flex search-form" role="search">
                                    <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                                    <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 600px; overflow:auto;">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Borrower ID</th>
                                        <th>Amount</th>
                                        <th>Interest Rate</th>
                                        <th>Term</th>
                                        <th>Status</th>
                                        <th class="text-center">Operation</th>
                                    </tr>
                                </thead>
                                <tr id="noSearchFoundRow" style="display: none;">
                                    <td colspan="10">
                                        <div class='text-center text-danger'>No record found.</div>
                                    </td>
                                </tr>
                                <tbody>
                                    <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td class="loan_id"><?php echo $row["loan_id"]; ?> </td>
                                                <td><?php echo $row["borrower_id"]; ?> </td>
                                                <td><?php echo $row["amount"]; ?> </td>
                                                <td><?php echo $row["interestRate"]; ?> </td>
                                                <td><?php echo $row["term"]; ?> </td>
                                                <td><?php echo $row["status"]; ?> </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-success approve_data"
                                                        title="Approve"
                                                        aria-label="Approve Request"
                                                        data-loan-id="<?php echo $row['loan_id']; ?>">
                                                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary edit_data" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete_btn" title="Delete">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr id="noSearchFoundRow" style="display: none;">
                                            <td colspan="10">
                                                <div class='text-center text-danger'>No record found.</div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    /* delete data*/
    $(document).ready(function() {
        $('.delete_btn').click(function(e) {
            e.preventDefault();
            var loan_id = $(this).closest('tr').find('.loan_id').text();
            $('#delete_id').val(loan_id);
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            var loan_id = $('#delete_id').val();
            $.ajax({
                method: "POST",
                url: "../modal_function/disb_loanfunc.php",
                data: {
                    'confirm_delete_btn': true,
                    'loan_id': loan_id,
                },
                success: function(response) {
                    location.reload();
                }
            });
        });
    });
    /*delete data*/

    /*edit data*/
    $(document).ready(function() {
        $('.edit_data').click(function(e) {
            e.preventDefault();

            var loan_id = $(this).closest('tr').find('.loan_id').text();
            console.log(loan_id);

            $.ajax({
                method: "POST",
                url: "../modal_function/disb_loanfunc.php",
                data: {
                    'click_edit_btn': true,
                    'loan_id': loan_id,
                },
                success: function(response) {

                    $.each(response, function(key, value) {
                        $('#loan_id').val(value['loan_id']);
                        $('#amount').val(value['amount']);
                        $('#interestRate').val(value['interestRate']);
                        $('#term').val(value['term']);
                        $('#status').val(value['status']);
                    });
                    $('#editdata').modal('show');
                }
            });
        });
    });
    /*edit data*/

    /*search */
    $(document).ready(function() {

        $('form.search-form').submit(function(e) {
            e.preventDefault();

            var searchQuery = $(this).find('input[type=search]').val().toLowerCase();

            var found = false;
            $('tbody tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchQuery) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                    found = true;
                }
            });

            if (!found) {
                $('#noSearchFoundRow').show();
            } else {
                $('#noSearchFoundRow').hide();
            }
        });
    });
    /* search */

    /* approve data */
    $(document).ready(function() {
        $('.approve_data').click(function(e) {
            e.preventDefault();

            // Retrieve the loan_id from the data attribute
            var loan_id = $(this).data('loan-id');
            console.log("Loan ID:", loan_id); // Debugging: Check if loan_id is retrieved correctly

            if (!loan_id) {
                alert("Loan ID is undefined. Please check the data-loan-id attribute.");
                return;
            }

            $.ajax({
                method: "POST",
                url: "../modal_function/disb_loanfunc.php",
                data: {
                    'approve_loan': true,
                    'loan_id': loan_id,
                    'interestRate': $('#interestRate').val() // Assuming you have an input for interest rate
                },
                success: function(response) {
                    alert(response.message); // Display success or error message
                    location.reload(); // Reload the page to reflect changes
                },
                error: function() {
                    alert("An error occurred while processing the request.");
                }
            });
        });

    });
    /* approve data */
</script>

</html>