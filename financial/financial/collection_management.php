<?php
session_start();
include '../components/sidebar.php';
include '../config/collman_conn.php';
include '../config/collman_btnfunc.php';
include '../config/disb_loan_conn.php';
include '../config/disb_conn.php';

// current date
$currentDate = date('Y-m-d');
// select overdue accounts
$query = "SELECT d.loan_id, b.disbursement_id, d.borrower_id, d.amount, d.term, d.status, 
                 b.disbursementDate,
                 DATE_ADD(b.disbursementDate, INTERVAL DATEDIFF(d.term, b.disbursementDate) DAY) AS overdueDate
          FROM disb_loan_tbl d
          JOIN disbursement_tbl b ON d.loan_id = b.loan_id
          WHERE DATE_ADD(b.disbursementDate, INTERVAL DATEDIFF(d.term, b.disbursementDate) DAY) < ? 
          AND d.amount > 0"; // check if the outstanding balance is not paid

$stmt = mysqli_prepare($connections, $query);
mysqli_stmt_bind_param($stmt, "s", $currentDate);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Collection Management</title>

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
                            <h1 class="modal-title fs-5" id="editdataLabel">Edit Collection Management</h1>
                        </div>
                        <form action="../modal_function/col_colmanfunc.php" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="hidden" name="collection_id" id="collection_id" class="form-control" placeholder="Collection ID">
                                </div>
                                <div class="mb-3">
                                    <label for="overdueAccount" class="form-label">Overdue Account</label>
                                    <input type="text" name="overdueAccount" id="overdueAccount" class="form-control" placeholder="Overdue Account">
                                </div>

                                <div class="mb-3">
                                    <label for="overdueDate" class="form-label">Overdue Date</label>
                                    <input type="text" name="overdueDate" id="overdueDate" class="form-control" placeholder="Overdue Date">
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <input type="text" name="status" id="status" class="form-control" placeholder="Status">
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
                                <input type="hidden" id="delete_id" name="collection_id">
                                <button type="submit" name="confirm_delete_btn" class="btn btn-danger">Confirm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!--delete-->

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
                            <h2 class="text-center display-7">Collection Management</h2>
                            <div class="search-button">
                                <form class="d-flex search-form" role="search">
                                    <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                                    <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 600px; overflow:auto;">
                            <table class="table table-bordered table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Loan ID</th>
                                        <th>Borrower ID</th>
                                        <th>Amount</th>
                                        <th>Term (Days)</th>
                                        <th>Status</th>
                                        <th>Disbursement Date</th>
                                        <th>Due Date</th>
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

                                                <td><?php echo $row["loan_id"]; ?></td> <!-- Loan ID from disb_loan_tbl -->
                                                <td><?php echo $row["disbursement_id"]; ?></td> <!-- Disbursement ID from disbursement_tbl -->
                                                <td><?php echo $row["borrower_id"]; ?></td>
                                                <td><?php echo $row["amount"]; ?></td>
                                                <td><?php echo $row["term"]; ?></td>
                                                <td><?php echo $row["status"]; ?></td>
                                                <td><?php echo $row["disbursementDate"]; ?></td>
                                                <td><?php echo $row["overdueDate"]; ?></td>
                                                <!--
                                                <td>
                                                    <button class="btn btn-sm btn-outline-info view_data" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary edit_data" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete_btn" title="Delete">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </td>
                                        -->
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
            var collection_id = $(this).closest('tr').find('.collection_id').text();
            $('#delete_id').val(collection_id);
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            var collection_id = $('#delete_id').val();
            $.ajax({
                method: "POST",
                url: "../modal_function/col_colmanfunc.php",
                data: {
                    'confirm_delete_btn': true,
                    'collection_id': collection_id,
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

            var collection_id = $(this).closest('tr').find('.collection_id').text();
            console.log(collection_id);

            $.ajax({
                method: "POST",
                url: "../modal_function/col_colmanfunc.php",
                data: {
                    'click_edit_btn': true,
                    'collection_id': collection_id,
                },
                success: function(response) {

                    $.each(response, function(key, value) {

                        $('#collection_id').val(value['collection_id']);
                        $('#overdueAccount').val(value['overdueAccount']);
                        $('#overdueDate').val(value['overdueDate']);
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
</script>

</html>