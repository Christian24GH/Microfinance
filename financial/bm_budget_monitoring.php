<?php
include __DIR__.'/session.php';
include __DIR__.'/config/bm_budmon_conn.php';
include __DIR__.'/config/bm_budmon_btnfunc.php';

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
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
                            <h1 class="modal-title fs-5" id="editdataLabel">Edit Budget Monitoring</h1>
                        </div>
                        <form action="../modal_function/bm_budmonfunc.php" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="hidden" name="monitor_id" id="monitor_id" class="form-control" placeholder="Monitor ID">
                                </div>

                                <div class="mb-3">
                                    <label for="actualSpend" class="form-label">Actual Spend</label>
                                    <input type="text" name="actualSpend" id="actualSpend" class="form-control" placeholder="Actual Spend">
                                </div>

                                <div class="mb-3">
                                    <label for="monitoringDate" class="form-label">Monitoring Date</label>
                                    <input type="text" name="monitoringDate" id="monitoringDate" class="form-control" placeholder="Monitoring Date">
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
                                <input type="hidden" id="delete_id" name="monitor_id">
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
                            <h2 class="text-center display-7">Budget Monitoring</h2>
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
                                        <th>Actual Spend</th>
                                        <th>Monitoring Date</th>
                                        <th>Status</th>
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
                                                <td class="monitor_id"><?php echo $row["monitor_id"]; ?> </td>
                                                <td><?php echo $row["actualSpend"]; ?> </td>
                                                <td><?php echo $row["monitoringDate"]; ?> </td>
                                                <td><?php echo $row["status"]; ?> </td>
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
            var monitor_id = $(this).closest('tr').find('.monitor_id').text();
            $('#delete_id').val(monitor_id);
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            var monitor_id = $('#delete_id').val();
            $.ajax({
                method: "POST",
                url: "../modal_function/bm_budmonfunc.php",
                data: {
                    'confirm_delete_btn': true,
                    'monitor_id': monitor_id,
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

            var monitor_id = $(this).closest('tr').find('.monitor_id').text();
            console.log(monitor_id);

            $.ajax({
                method: "POST",
                url: "../modal_function/bm_budmonfunc.php",
                data: {
                    'click_edit_btn': true,
                    'monitor_id': monitor_id,
                },
                success: function(response) {

                    $.each(response, function(key, value) {

                        $('#monitor_id').val(value['monitor_id']);
                        $('#actualSpend').val(value['actualSpend']);
                        $('#monitoringDate').val(value['monitoringDate']);
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