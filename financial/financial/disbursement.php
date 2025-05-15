<?php
session_start();
include '../components/sidebar.php';
include '../config/disb_conn.php';
include '../config/disb_btnfunc.php';

$query = "SELECT loan_id FROM disb_loan_tbl";
$loan_result = mysqli_query($connections, $query);

$result = display_data();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Disbursement</title>
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
                        <h1 class="modal-title fs-5" id="editdataLabel">Edit Disbursement</h1>
                    </div>
                    <form action="../modal_function/disbfunc.php" method="POST">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <input type="hidden" name="disbursement_id" id="disbursement_id" class="form-control" placeholder="Disbursement ID">
                            </div>

                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Disbursement Date">
                            </div>

                            <div class="form-group mb-3">
                                <label for="disbursementDate" class="form-label">Disbursement Date</label>
                                <input type="text" name="disbursementDate" id="disbursementDate" class="form-control" placeholder="Disbursement Date">
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
                            <h2 class="text-center display-7">Disbursement</h2>
                            <div class="search-button">
                                <form class="d-flex search-form" role="search">
                                    <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                                    <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body" style="max-height: 600px; overflow:auto;">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Loan ID</th>
                                        <th>Disbursement Amount</th>
                                        <th>Disbursement Date</th>
                                        <th class="text-center">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td class="disbursement_id" style="display: none;"><?php echo $row["disbursement_id"]; ?> </td>
                                                <td><?php echo $row['loan_id']; ?></td>
                                                <td><?php echo $row['amount']; ?></td>
                                                <td><?php echo $row['disbursementDate']; ?></td>
                                                <td class="text-center">
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
    /* delete data */
    $(document).ready(function() {
        $('.delete_btn').click(function(e) {
            e.preventDefault();
            var disbursement_id = $(this).closest('tr').find('.disbursement_id').text();
            $('#delete_id').val(disbursement_id);
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            var disbursement_id = $('#delete_id').val();
            $.ajax({
                method: "POST",
                url: "../modal_function/disbfunc.php",
                data: {
                    'confirm_delete_btn': true,
                    'disbursement_id': disbursement_id,
                },
                success: function(response) {
                    location.reload();
                }
            });
        });
    });
    /* delete data */

    /* edit data */
    $(document).ready(function() {
        $('.edit_data').click(function(e) {
            e.preventDefault();
            var disbursement_id = $(this).closest('tr').find('.disbursement_id').text();
            $.ajax({
                method: "POST",
                url: "../modal_function/disbfunc.php",
                data: {
                    'click_edit_btn': true,
                    'disbursement_id': disbursement_id,
                },
                success: function(response) {
                    $.each(response, function(key, value) {
                        $('#disbursement_id').val(value['disbursement_id']);
                        $('#amount').val(value['amount']);
                        $('#disbursementDate').val(value['disbursementDate']);
                    });
                    $('#editdata').modal('show');
                }
            });
        });
    });
    /* edit data */

    /* search */
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