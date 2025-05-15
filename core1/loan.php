<?php
    include __DIR__.'/components/session.php';
    include "./components/config.php";
    //include "./components/settings.php";


    // Fetch client_id
    /*
$sql = "SELECT client_id FROM client_info WHERE client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $amount = $_POST['amount'];
            $month = $_POST['month'];
            $terms = $_POST['terms'];
            $purpose = $_POST['purpose'];

    $sql = "INSERT INTO loan_info (amount, month, terms, purpose, client_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $amount, $month, $terms, $purpose, $client_id);
    
    if ($stmt->execute()) {
        header("Location: ../testapp/dashboard.php?success=Loan Added");
    } else {
        echo "Error: " . $stmt->error;
    }
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="./css/app.css"rel="stylesheet">
    <link href="./css/sidebar.css"rel="stylesheet">
    <link href="resources/css/content.css"rel="stylesheet">
    <link href="resources/css/input_range.css"rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php 
        include __DIR__.'/components/sidebar copy.php'
    ?>
    <div id="main" class="ps-0 rounded-end visually-hidden">
        <?php 
            include __DIR__.'/components/header.php'
        ?>
        <div class="container-fluid px-0">
            <section class="home-section">
                <div class="dashboard-content">
                    <div class="info-section">
                            <h3>Setting up your Loan:</h3> <br>
                            <form id="loan" action="" method="POST">
                            <label for="amount" class="form-label">Amount: <output id="amountValue">2000</output></label> 
                            <input type="range" class="form-control" id="amount" name="amount" min="2000" max="10000" step="100" oninput="amountValue.value = this.value">
                            <div class="d-flex justify-content-between">
                                <span>2000</span>
                                <span>10000</span>
                            </div><br>

                            <label for="month" class="form-label">Months: <output id="monthValue">2</output></label>
                            <input type="range" class="form-control" id="month" name="month" min="2" max="6" oninput="updateTerms(); monthValue.value = this.value">
                            <div class="d-flex justify-content-between">
                                <span>2</span>
                                <span>6</span>
                            </div><br>

                            <label for="terms" class="form-label">Terms: <output id="termsValue">4</output></label>
                            <input type="range" class="form-control" id="terms" name="terms" min="4" max="12" step="2" readonly>
                            <div class="d-flex justify-content-between">
                                <span>4</span>
                                <span>12</span>
                            </div><br>

                            <label for="purpose" class="form-label">Purpose:</label>
                            <input type="text" class="form-control" name="purpose" id="purpose" placeholder="" required>

                            <script>
                                /*
                                function updateTerms() {
                                    const month = document.getElementById('month').value;
                                    const terms = document.getElementById('terms');
                                    const termsValue = document.getElementById('termsValue');

                                    terms.value = month * 2;
                                    termsValue.value = terms.value;
                                }
                                // Initialize terms on page load
                                updateTerms();
                                */
                            </script>
                            <br>
                            <button type="submit" class="btn w-100 rounded-pill">Submit</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script src="js/sidebar.js"></script>
</body>
</html>