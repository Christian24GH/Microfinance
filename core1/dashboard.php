<?php
    //include __DIR__.'/session.php';
    session_start();
    include "components/config.php";
    include "components/settings.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORE 1</title>
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
        <div class="text"><?= htmlspecialchars($role_name) ?> Dashboard</div>
        <div class="dashboard-content">
            <div class="row">
                <?php
                // Fetch total unique client accounts
                $totalClients = 0;
                $totalPending = 0;
                $totalApproved = 0;
                $totalLoans = 0;

                // Total unique client accounts
                $result = $conn->query("SELECT COUNT(DISTINCT client_id) AS total FROM accounts");
                if ($row = $result->fetch_assoc()) {
                    $totalClients = $row['total'];
                }

                // Total pending accounts (unique client_id with c_status or l_status 'Pending')
                $result = $conn->query("SELECT COUNT(DISTINCT client_id) AS total FROM client_status WHERE c_status = 'Pending' OR l_status = 'Pending'");
                if ($row = $result->fetch_assoc()) {
                    $totalPending = $row['total'];
                }

                // Total approved accounts (unique client_id with c_status or l_status 'Approved')
                $result = $conn->query("SELECT COUNT(DISTINCT client_id) AS total FROM client_status WHERE c_status = 'Approved' OR l_status = 'Approved'");
                if ($row = $result->fetch_assoc()) {
                    $totalApproved = $row['total'];
                }

                // Total loans (unique loan_id in loan_info)
                $result = $conn->query("SELECT COUNT(DISTINCT loan_id) AS total, SUM(amount) AS total_amount FROM loan_info");
                if ($row = $result->fetch_assoc()) {
                    $totalLoans = $row['total'];
                    $totalLoanAmount = $row['total_amount'] ?? 0;
                }
                ?>
                                <div class="column" style="display: <?= ($role_id == 1 || $role_id == 3) ? 'block' : 'none' ?>;">
                                    <div class="card">
                                        <h3>Total of Client Accounts: <?= $totalClients ?></h3>
                                    </div>
                                </div>
                                <div class="column" style="display: <?= ($role_id == 1 || $role_id == 3) ? 'block' : 'none' ?>;">
                                    <div class="card">
                                        <h3>Total of Pending Accounts: <?= $totalPending ?></h3>
                                    </div>
                                </div>
                                <div class="column" style="display: <?= ($role_id == 1 || $role_id == 3) ? 'block' : 'none' ?>;">
                                    <div class="card">
                                        <h3>Total of Approved Accounts: <?= $totalApproved ?></h3>
                                    </div>
                                </div>
                                <div class="column" style="display: <?= ($role_id == 1 || $role_id == 3) ? 'block' : 'none' ?>;">
                                    <div class="card">
                                        <h3>Total Loan Disbursed: <?= $totalLoanAmount ?></h3>
                                    </div>
                                </div>
                <div class="column" style="display: <?= ($role_id == 2) ? 'block' : 'none' ?>;">
                    <div class="card">
                        <h3>Account Status:</h3>
                    </div>
                </div>    
                <div class="column" style="display: <?= ($role_id == 2) ? 'block' : 'none' ?>;">
                    <div class="card">
                        <h3>Loan Status:</h3>
                    </div>
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