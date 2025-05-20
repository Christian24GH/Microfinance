<?php
    session_start();
    include "./components/config.php";
    include "./components/settings.php";

    $result = $conn->query("SELECT ci.client_id, 
                             a.id, a.email, cs.l_status, cll.ll_amount, cll.ll_month, cll.ll_interest
                        FROM client_info ci
                        INNER JOIN accounts a ON ci.client_id = a.client_id
                        INNER JOIN client_status cs ON ci.client_id = cs.client_id
                        INNER JOIN client_loan_limit cll ON ci.client_id = cll.client_id
                        WHERE a.role_id = 2");
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
                                <h3>Setting up Client's Loan:</h3> <br>
                                <table class="users-table">
                                    <thead>
                                        <tr>
                                            <th>Client ID</th>
                                            <th>Email</th>
                                            <th>Credit Amount</th>
                                            <th>Available Month</th>
                                            <th>Monthly Interest</th>
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
                                                <td><?= $row["email"] ?></td>
                                                <td><?= $row["ll_amount"] ?></td>
                                                <td><?= $row["ll_month"] ?></td>
                                                <td><?= $row["ll_interest"] ?></td>
                                                <td><?= $row["l_status"] ?></td>
                                                <td>
                                                <?php if ($row['l_status'] === 'Pending'): ?>
                                                    <a href="../Core1/loanp.php?id=<?= $row['client_id'] ?>">Loan Process</a>
                                                <?php else: ?>
                                                    Completed
                                                <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                            <?php 
                                // Fetch client_id
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
                                            $interest = $_POST['interest'];
                                            $total = $_POST['total'];

                                    $sql = "INSERT INTO loan_info (amount, month, terms, purpose, interest, total, client_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("iissssi", $amount, $month, $terms, $purpose, $interest, $total, $client_id);
                                    
                                    if ($stmt->execute()) {
                                        header("Location: ../testapp/dashboard.php?success=Loan Added");
                                    } else {
                                        echo "Error: " . $stmt->error;
                                    }
                                }

                            ?>

                            <div class="info-section" style="display: <?= ($role_id == 2) ? 'block' : 'none' ?>;">
                                <h3>Setting up your Loan:</h3> <br>
                            <form id="loan" action="" method="POST">
                                <label for="amount" class="form-label">Amount:</label>
                                <div class="range-wrap position-relative mb-2">
                                    <input type="range" class="form-control" id="amount" name="amount" min="2000" max="10000" step="100" oninput="updateLoanDetails(); updateRangeBubble(this, 'amountBubble')">
                                    <output id="amountBubble" class="range-bubble"></output>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>2000</span>
                                    <span>10000</span>
                                </div><br>

                                <label for="month" class="form-label">Months:</label>
                                <div class="range-wrap position-relative mb-2">
                                    <input type="range" class="form-control" id="month" name="month" min="2" max="6" oninput="updateLoanDetails(); updateRangeBubble(this, 'monthBubble')">
                                    <output id="monthBubble" class="range-bubble"></output>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>2</span>
                                    <span>6</span>
                                </div><br>

                                <label for="terms" class="form-label">Terms:</label>
                                <div class="range-wrap position-relative mb-2">
                                    <input type="range" class="form-control" id="terms" name="terms" min="4" max="12" step="2" 
                                        onmousedown="return false;" 
                                        onkeydown="return false;" 
                                        ontouchstart="return false;">
                                    <output id="termsBubble" class="range-bubble"></output>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>4</span>
                                    <span>12</span>
                                </div><br>

                                <label for="purpose" class="form-label">Purpose:</label>
                                <select name="purpose" id="purpose" required>
                                    <option value="Tuition">Tuition</option>
                                    <option value="Bills">Bills</option>
                                    <option value="Emergency">Emergency</option>
                                    <option value="Online Shopping">Online Shopping</option>
                                </select><br><br>

                                <label for="interest" class="form-label">Interest (5% per month):</label>
                                <input type="text" class="form-control" id="interest" name="interest" value="200" readonly><br>

                                <label for="total" class="form-label">Total Payable:</label>
                                <input type="text" class="form-control" id="total" name="total" value="2200" readonly><br>

                                <div id="scheduleSection" class="mt-3">
                                    <label class="form-label">Payment Schedule:</label>
                                    <ul id="scheduleList" class="list-group"></ul>
                                </div>

                                <style>
                                    .range-wrap {
                                        position: relative;
                                        width: 100%;
                                    }
                                    .range-bubble {
                                        position: absolute;
                                        top: -35px;
                                        left: 0;
                                        background: #fff;
                                        color: #333;
                                        padding: 2px 8px;
                                        border-radius: 4px;
                                        font-size: 0.9em;
                                        pointer-events: none;
                                        transform: translateX(-50%);
                                        white-space: nowrap;
                                        border: 1px solid #ccc;
                                        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
                                    }
                                </style>
                                <script>
                                    function updateRangeBubble(range, bubbleId) {
                                        const bubble = document.getElementById(bubbleId);
                                        const min = Number(range.min);
                                        const max = Number(range.max);
                                        const val = Number(range.value);

                                        // Calculate percent position
                                        const percent = (val - min) / (max - min);
                                        // Range input padding compensation
                                        const rangeWidth = range.offsetWidth;
                                        const bubbleWidth = bubble.offsetWidth || 40;
                                        const offset = percent * (rangeWidth - bubbleWidth) + bubbleWidth / 2;

                                        bubble.innerHTML = val;
                                        bubble.style.left = `calc(${percent * 100}% - ${bubbleWidth / 2}px)`;
                                    }

                                    function updateLoanDetails() {
                                        const amount = parseInt(document.getElementById('amount').value);
                                        const month = parseInt(document.getElementById('month').value);
                                        const terms = document.getElementById('terms');
                                        const termsBubble = document.getElementById('termsBubble');
                                        const interestInput = document.getElementById('interest');
                                        const totalInput = document.getElementById('total');
                                        const scheduleList = document.getElementById('scheduleList');

                                        // Terms is always 2 per month
                                        const computedTerms = month * 2;
                                        terms.value = computedTerms;
                                        termsBubble.innerHTML = computedTerms;
                                        updateRangeBubble(terms, 'termsBubble');

                                        // Interest: 5% per month
                                        const interest = Math.round(amount * 0.05 * month);
                                        interestInput.value = interest;

                                        // Total payable
                                        const total = amount + interest;
                                        totalInput.value = total;

                                        // Generate payment schedule
                                        scheduleList.innerHTML = '';
                                        const today = new Date();
                                        const perTermAmount = Math.round(total / computedTerms * 100) / 100;
                                        for (let i = 1; i <= computedTerms; i++) {
                                            let dueDate = new Date(today);
                                            dueDate.setMonth(today.getMonth() + Math.floor((i - 1) / 2));
                                            dueDate.setDate(today.getDate() + ((i % 2 === 1) ? 0 : 15));
                                            const yyyy = dueDate.getFullYear();
                                            const mm = String(dueDate.getMonth() + 1).padStart(2, '0');
                                            const dd = String(dueDate.getDate()).padStart(2, '0');
                                            const formattedDate = `${yyyy}-${mm}-${dd}`;
                                            const li = document.createElement('li');
                                            li.className = 'list-group-item';
                                            li.textContent = `Term ${i}: ₱${perTermAmount} - Due: ${formattedDate}`;
                                            scheduleList.appendChild(li);
                                        }
                                    }

                                    // Initialize on page load and set bubbles
                                    window.addEventListener('DOMContentLoaded', function() {
                                        updateLoanDetails();
                                        updateRangeBubble(document.getElementById('amount'), 'amountBubble');
                                        updateRangeBubble(document.getElementById('month'), 'monthBubble');
                                        updateRangeBubble(document.getElementById('terms'), 'termsBubble');
                                    });

                                    // Update bubbles on input
                                    document.getElementById('amount').addEventListener('input', function() {
                                        updateRangeBubble(this, 'amountBubble');
                                    });
                                    document.getElementById('month').addEventListener('input', function() {
                                        updateRangeBubble(this, 'monthBubble');
                                    });
                                    document.getElementById('terms').addEventListener('input', function() {
                                        updateRangeBubble(this, 'termsBubble');
                                    });
                                </script>
                                <br>
                                <button type="submit" class="btn w-100 rounded-pill">Submit</button>
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