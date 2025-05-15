<?php
session_start();
include '../components/sidebar.php';
include '../config/col_accpay_conn.php';
include '../config/col_accpay_btnfunc.php';

$query = "SELECT fk_loan_id FROM ar_loan_account_tbl";
$loan_result = mysqli_query($connections, $query);

$result = display_data();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Payment</title>

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
                        <h1 class="modal-title fs-5" id="editdataLabel">Edit Account Payment</h1>
                    </div>
                    <form action="../modal_function/col_accpayfunc.php" method="POST">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <input type="hidden" name="payment_id" id="payment_id" class="form-control" placeholder="Payment ID">
                            </div>

                            <div class="mb-3">
                                <label for="amountPaid" class="form-label">Amount Paid</label>
                                <input type="text" name="amountPaid" id="amountPaid" class="form-control" placeholder="Amount Paid">
                            </div>

                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <input type="text" name="paymentMethod" id="paymentMethod" class="form-control" placeholder="Payment Method">
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" name="date" id="date" class="form-control" placeholder="Date">
                            </div>

                            <div class="mb-3">
                                <label for="history" class="form-label">History</label>
                                <input type="text" name="history" id="history" class="form-control" placeholder="History">
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

        <!--repay modal-->
            <div class="modal fade" id="repay" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="repayLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h1 class="modal-title fs-5" id="repayLabel">Repayment</h1>
                        </div>
                        <form action="../modal_function/col_accpayfunc.php" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="loan_id" class="form-label">Select Loan ID</label>
                                    <select name="loan_id" id="loan_id" class="form-select" required>
                                        <option value="" disabled selected>Select Loan ID</option>
                                        <?php
                                        if (isset($loan_result) && mysqli_num_rows($loan_result) > 0) {
                                            while ($loan = mysqli_fetch_assoc($loan_result)) {
                                                echo "<option value='" . $loan['fk_loan_id'] . "'>" . $loan['fk_loan_id'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No loans available</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="paymentMethod" class="form-label">Payment Method</label>
                                    <select name="paymentMethod" id="paymentMethod" class="form-control" required>
                                        <option value="" disabled selected>Select Payment Method</option>
                                        <option value="Cash">Cash</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="amountPaid" class="form-label">Amount Paid</label>
                                    <input type="text" name="amountPaid" id="amountPaid" class="form-control" placeholder="Amount Paid" required>
                                </div>

                                <div class="mb-3">
                                    <label for="repaymentDate" class="form-label">Repayment Date</label>
                                    <input type="date" name="repaymentDate" id="repaymentDate" class="form-control" placeholder="Repayment Date" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="repay" class="btn btn-success text-white">Repay</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!--repay modal-->

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
                            <h2 class="text-center display-7">Account Payment</h2>
                            <div class="search-button">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#repay">Repay</button>
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
                                        <th>Amount Paid</th>
                                        <th>Payment Method</th>
                                        <th>Date</th>
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
                                                <td class="payment_id"><?php echo $row["payment_id"]; ?> </td>
                                                <td><?php echo $row["amountPaid"]; ?> </td>
                                                <td><?php echo $row["paymentMethod"]; ?> </td>
                                                <td><?php echo $row["date"]; ?> </td>
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
    /* delete data*/
    $(document).ready(function() {
        $('.delete_btn').click(function(e) {
            e.preventDefault();
            var payment_id = $(this).closest('tr').find('.payment_id').text();
            $('#delete_id').val(payment_id);
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            var payment_id = $('#delete_id').val();
            $.ajax({
                method: "POST",
                url: "../modal_function/col_accpayfunc.php",
                data: {
                    'confirm_delete_btn': true,
                    'payment_id': payment_id,
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

            var payment_id = $(this).closest('tr').find('.payment_id').text();
            console.log(payment_id);

            $.ajax({
                method: "POST",
                url: "../modal_function/col_accpayfunc.php",
                data: {
                    'click_edit_btn': true,
                    'payment_id': payment_id,
                },
                success: function(response) {

                    $.each(response, function(key, value) {

                        $('#payment_id').val(value['payment_id']);
                        $('#amountPaid').val(value['amountPaid']);
                        $('#paymentMethod').val(value['paymentMethod']);
                        $('#date').val(value['date']);
                        $('#history').val(value['history']);
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