<?php
include __DIR__.'/session.php';
include __DIR__.'/config/gl_transaction_conn.php';
include __DIR__.'/config/gl_transaction_btnfunc.php';

$result = display_data();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trulend</title>
    <link rel="icon" href="./img/1.4.png"/>
    <link href="./resources/app.css"rel="stylesheet">
    <link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./resources/css/accman.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-light relative">
    <?php 
        include  __DIR__.'/components/sidebar.php'
    ?>
    <div id="main" class="visually-hidden ">
        <?php 
            include __DIR__.'/components/header.php'
        ?>
    <div class="main-content">

        <!--edit modal-->
            <div class="modal fade" id="editdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editdataLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h1 class="modal-title fs-5" id="editdataLabel">Edit Transaction</h1>
                        </div>
                        <form action="../modal_function/gl_transactionfunc.php" method="POST">
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <input type="hidden" name="transaction_id" id="transaction_id" class="form-control" placeholder="Transaction ID">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="transactionAmount" class="form-label">Amount</label>
                                    <input type="text" name="transactionAmount" id="transactionAmount" class="form-control" placeholder="Amount">
                                </div>

                                <div class="mb-3">
                                    <label for="transactionItem" class="form-label">Item</label>
                                    <input type="text" name="transactionItem" id="transactionItem" class="form-control" placeholder="Item">
                                </div>

                                <div class="mb-3">
                                    <label for="transactionTime" class="form-label">Time</label>
                                    <input type="text" name="transactionTime" id="transactionTime" class="form-control" placeholder="Time">
                                </div>

                                <div class="mb-3">
                                    <label for="transactionDate" class="form-label">Date</label>
                                    <input type="text" name="transactionDate" id="transactionDate" class="form-control" placeholder="Date">
                                </div>

                                <div class="mb-3">
                                    <label for="transaction_type" class="form-label">Type</label>
                                    <input type="text" name="transaction_type" id="transaction_type" class="form-control" placeholder="Type">
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
                                <input type="hidden" id="delete_id" name="transaction_id">
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
                            <h2 class="text-center display-7">Transaction</h2>
                            <div class="search-button">
                            <button type="button" class="btn btn-success" style="background-color: #272c47; color: white;" data-bs-toggle="modal" data-bs-target="#add">Button</button>
                                <form class="d-flex search-form" role="search">
                                    <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                                    <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow:auto;">
                            <table class="table table-bordered table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Amount</th>
                                        <th>Item</th>
                                        <th>Time</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Operation</th>
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
                                                <td class="transaction_id"><?php echo $row["transaction_id"]; ?> </td>
                                                <td><?php echo $row["transactionAmount"]; ?> </td>
                                                <td><?php echo $row["transactionItem"]; ?> </td>
                                                <td><?php echo $row["transactionTime"]; ?> </td>
                                                <td><?php echo $row["transactionDate"]; ?> </td>
                                                <td><?php echo $row["transaction_type"]; ?> </td>
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
    <?php
            include './components/footer.php';
        ?>
    </div>
    <script src="./js/sidebar.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    /* delete data*/
    $(document).ready(function() {
        $('.delete_btn').click(function(e) {
            e.preventDefault();
            var transaction_id = $(this).closest('tr').find('.transaction_id').text();
            $('#delete_id').val(transaction_id);
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            var transaction_id = $('#delete_id').val();
            $.ajax({
                method: "POST",
                url: "../modal_function/gl_transactionfunc.php",
                data: {
                    'confirm_delete_btn': true,
                    'transaction_id': transaction_id,
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

            var transaction_id = $(this).closest('tr').find('.transaction_id').text();
            console.log(transaction_id);

            $.ajax({
                method: "POST",
                url: "../modal_function/gl_transactionfunc.php",
                data: {
                    'click_edit_btn': true,
                    'transaction_id': transaction_id,
                },
                success: function(response) {

                    $.each(response, function(key, value) {

                        $('#transaction_id').val(value['transaction_id']);
                        $('#transactionAmount').val(value['transactionAmount']);
                        $('#transactionItem').val(value['transactionItem']);
                        $('#transactionTime').val(value['transactionTime']);
                        $('#transactionDate').val(value['transactionDate']);
                        $('#transaction_type').val(value['transaction_type']);
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