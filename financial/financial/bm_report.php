<?php
session_start();
include '../components/sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report</title>

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
                            <h1 class="modal-title fs-5" id="editdataLabel">Edit Budget Management Report</h1>
                        </div>
                        <form action="operation.php" method="POST">
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <input type="hidden" name="id" id="id" class="form-control" placeholder="Student ID">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" name="studentname" id="studentname" class="form-control" placeholder="Surname, First Name M.I">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" name="birthdate" id="birthdate" class="form-control" placeholder="Birth Date">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" name="gender" id="gender" class="form-control" placeholder="Gender">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="tel" placeholder="Phone Number" class="form-control" id="phonenumber" name="phonenumber" pattern="[0-9]{11}" maxlength="11" title="Please enter a valid 11-digit phone number." oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" name="password" id="password" class="form-control" placeholder="Password">
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
                            <h2 class="text-center display-7">Report</h2>
                            <div class="search-button">
                            <button type="button" class="btn btn-success" style="background-color: #272c47; color: white;" data-bs-toggle="modal" data-bs-target="#add">Button</button>
                                <form class="d-flex search-form" role="search">
                                    <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                                    <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow:auto;">
                            <table class="table table-bordered table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Report Type</th>
                                        <th>Report Date</th>
                                        <th>Analysis</th>
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
                                                <td class=""><?php echo $row[""]; ?> </td>
                                                <td><?php echo $row[""]; ?> </td>
                                                <td><?php echo $row[""]; ?> </td>
                                                <td><?php echo $row[""]; ?> </td>
                                                <td class='text-center'>
                                                    <a href="#" class="btn btn-success text-center btn-sm edit_data">Edit</a>
                                                    <a href='#' class='btn btn-danger text-center btn-sm delete_btn'>Delete</a>
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
            var student_id = $(this).closest('tr').find('.student_id').text();
            $('#delete_id').val(student_id);
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            var student_id = $('#delete_id').val();
            $.ajax({
                method: "POST",
                url: "operation.php",
                data: {
                    'confirm_delete_btn': true,
                    'student_id': student_id,
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

            var student_id = $(this).closest('tr').find('.student_id').text();
            console.log(student_id);

            $.ajax({
                method: "POST",
                url: "config/connections.php",
                data: {
                    'click_edit_btn': true,
                    'student_id': student_id,
                },
                success: function(response) {

                    $.each(response, function(key, value) {

                        $('#id').val(value['id']);
                        $('#studentname').val(value['studentname']);
                        $('#birthdate').val(value['birthdate']);
                        $('#gender').val(value['gender']);
                        $('#phonenumber').val(value['phonenumber']);
                        $('#email').val(value['email']);
                        $('#password').val(value['password']);
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