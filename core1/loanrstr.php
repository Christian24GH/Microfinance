<?php
    //include __DIR__.'/session.php';
    session_start();
    include "components/config.php";
    include "components/settings.php";

    $result = $conn->query("SELECT li.client_id, li.amount, li.month, li.terms, li.purpose, li.interest, li.total,
                             a.id, lr.r_amount, lr.r_month, lr.r_interest
                        FROM loan_info li
                        INNER JOIN accounts a ON li.client_id = a.client_id
                        INNER JOIN loan_restrc lr ON li.client_id = lr.client_id
                        WHERE a.role_id = 1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microfinance</title>
    <link href="./resources/css/app.css"rel="stylesheet">
    <link href="./resources/css/content.css"rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">-->
</head>
<body class="bg-light relative">
    <?php 
        include __DIR__.'/components/sidebar.php'
    ?>
    <div id="main" class="visually-hidden">
        <?php 
            include __DIR__.'/components/header.php'
        ?>
        
        <!--Content Here -->
        <section class="home-section">
                    <div class="dashboard-content">
                        <div class="info-section">
                            <h3>Clients Restructuring Request</h3>
                                <table class="users-table">
                                    <thead>
                                        <tr>
                                            <th>Client ID</th>
                                            <th>Restructured Amount</th>
                                            <th>Restructured Month</th>
                                            <th>Restructured Interest</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        while ($row = $result->fetch_assoc()) :
                                        ?>             
                                            <tr>
                                                <td><?= $row["client_id"] ?></td>
                                                <td><?= $row["r_amount"] ?></td>
                                                <td><?= $row["r_month"] ?></td>
                                                <td><?= $row["r_interest"] ?></td>
                                                <td><?= $row["r_status"] ?></td>
                                                <td>
                                                <a href="../Core1/view_restrc.php?id=<?= $row['client_id'] ?>">View</a>
                                                <a href="../Core1/r_approve.php?id=<?= $row['client_id'] ?>">Approve</a>
                                                <a href="../Core1/r_decline.php?id=<?= $row['client_id'] ?>">Decline</a>
                                                </td>
                                            </tr><?php endwhile; ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>

                            <div class="info-section">
                            <h3>Loan Restructuring Request</h3>
                                <table class="users-table">
                                    <thead>
                                        <tr>
                                            <th>Loan ID</th>
                                            <th>Amount</th>
                                            <th>Month</th>
                                            <th>Interest</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        while ($row = $result->fetch_assoc()) :
                                        ?>             
                                            <tr>
                                                <td><?= $row["loan_id"] ?></td>
                                                <td><?= $row["amount"] ?></td>
                                                <td><?= $row["month"] ?></td>
                                                <td><?= $row["interest"] ?></td>
                                                <td><?= $row["r_status"] ?></td>
                                                <td>
                                                <a href="../Core1/view_loan.php?id=<?= $row['client_id'] ?>">View</a>
                                                <a href="../Core1/r_request.php?id=<?= $row['client_id'] ?>">Request</a>
                                                </td>
                                            </tr><?php endwhile; ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

        </div>

        <?php
            include __DIR__.'/components/footer.php';
        ?>
    </div>
    <script src="js/sidebar.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>