<?php
    session_start();
    include "./components/config.php";
    include "./components/settings.php";
    
    $client_id = $_GET["id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ll_amount = $_POST['ll_amount'];
        $ll_month = $_POST['ll_month'];
        $ll_interest = $_POST['ll_interest'];

        $sql_loanlimit = "UPDATE client_loan_limit SET ll_amount = ?, ll_month = ?, ll_interest = ? WHERE client_id = ?";
        $stmt_loanlimit = $conn->prepare($sql_loanlimit);
        $stmt_loanlimit->bind_param("ssss", $ll_amount, $ll_month, $ll_interest, $client_id);
        $stmt_loanlimit->execute();
        $stmt_loanlimit->close();

    // Update l_status to 'approved' in client_status table for this client_id
    $sql_update_status = "UPDATE client_status SET l_status = 'Approved' WHERE client_id = ?";
    $stmt_update_status = $conn->prepare($sql_update_status);
    $stmt_update_status->bind_param("s", $client_id);
    $stmt_update_status->execute();
    $stmt_update_status->close();

    header("Location: loan.php");
    exit();
}
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
                        <div class="info-section" style="display: <?= ($role_id == 1 || $role_id == 3) ? 'block' : 'none' ?>;">
                            <h3>Setting up <?= htmlspecialchars($client_id) ?>'s Loan Limit:</h3> <br>
                            <form id="loan" action="" method="POST">
                                <label for="ll_amount" class="form-label">Credit Amount:</label>
                                <input type="number" class="form-control" name="ll_amount" id="ll_amount" placeholder="" required>

                                <label for="ll_month" class="form-label">Available Month:</label>
                                <input type="number" class="form-control" name="ll_month" id="ll_month" placeholder="" required>

                                <label for="ll_interest" class="form-label">Monthly Interest:</label>
                                <input type="number" class="form-control" name="ll_interest" id="ll_interest" placeholder="" required>

                                <br>
                                <button type="submit" class="btn w-100 rounded-pill">Save</button>
                            </form>
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