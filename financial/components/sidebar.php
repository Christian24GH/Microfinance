<div id="sidebar" class="vstack px-4 scrollbar">
    <div class="py-3">
        <h3 class="montserrat-header">Financial</h3>
    </div>

    <div id="profile" class="d-flex gap-3 align-items-center ps-1 py-4">
        <div class="border img_container">
            <img src="../resources/gabut.jpg" alt="" style="width: 80px; height: 100px;">
        </div>
        <div class="lato-regular">
            <h5><?php echo $_SESSION['fullname'] ?? 'Username' ?></h5>
            <h6><?php echo $_SESSION['role'] ?? 'Role' ?></h6>
        </div>
    </div>
    <hr>
    <div class="navs d-flex flex-column gap-1 justify-content-center position-relative">

        <!-- General Ledger Header -->
        <div class="py-2">
            <h5 class="text-uppercase fw-bold nunito-nav">General Ledger</h5>
        </div>
        <!-- General Ledger Submodules -->
        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-book"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/general_ledger.php">General Ledger</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-journal-text"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/gl_book_keeper.php">Book Keeper</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-diagram-3"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/gl_chart_of_acc.php">Chart of Accounts</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/gl_statement.php">Statement</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-arrow-left-right"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/gl_transaction.php">Transaction</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-graph-up"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/gl_report.php">Report</a>
        </div>


        <!-- Collection Header -->
        <div class="py-2">
            <h5 class="text-uppercase fw-bold nunito-nav">Collection</h5>
        </div>

        <!-- Collection Submodules -->
        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-person-lines-fill"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/col_ar.php">AR</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-credit-card"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/col_account_payment.php">Account Payment</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-journal-check"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/collection_management.php">Collection Management</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-graph-up"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/col_report.php">Report</a>
        </div>

        <!-- Disbursement Header -->
        <div class="py-2">
            <h5 class="text-uppercase fw-bold nunito-nav">Disbursement</h5>
        </div>

        <!-- Disbursement Submodules -->
            <div class="py-2 hstack gap-3 nav-item">
                <i class="bi bi-cash-stack"></i>
                <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/disb_loan.php">Loan</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <i class="bi bi-send"></i>
                <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/disbursement.php">Disbursement</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <i class="bi bi-bank"></i>
                <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/disb_repayment.php">Repayment</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <i class="bi bi-graph-up"></i>
                <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/disb_report.php">Report</a>
            </div>

        <!-- AR Header -->
        <div class="py-2">
            <h5 class="text-uppercase fw-bold nunito-nav">AR</h5>
        </div>

        <!-- AR Submodules -->
        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-person-lines-fill"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ar_loanacc.php">Loan Account</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-journal-text"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ar_disb_tracking.php">Disbursement Tracking</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-cash-coin"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ar_repayment.php">Repayment Management</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-graph-up"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ar_report.php">Report</a>
        </div>

        <!-- AP Header -->
        <div class="py-2">
            <h5 class="text-uppercase fw-bold nunito-nav">AP</h5>
        </div>

        <!-- AP Submodules -->
        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-credit-card-2-back"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ap_funder_repayment.php">Funder Payment</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-exclamation-circle"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ap_liability.php">Liability</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-bank"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ap_funding_source.php">Funding Source</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-graph-up"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/ap_report.php">Report</a>
        </div>

        <!-- Budget Management Header -->
        <div class="py-2">
            <h5 class="text-uppercase fw-bold nunito-nav">Budget Management</h5>
        </div>

        <!-- Budget Management Submodules -->
        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-sliders"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/bm_budget_adjustment.php">Budget Adjustment</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-journal-text"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/bm_budget_planning.php">Budget Planning</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-graph-up-arrow"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/bm_budget_monitoring.php">Budget Monitoring</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <i class="bi bi-graph-up"></i>
            <a class="nunito-nav" href="/dashboard/Microfinance/testapp/financial/financial/bm_report.php">Report</a>
        </div>

        <div class="d-flex w-100 flex-column">
            <hr>
        </div>

        <!--Logout-->
        <div class="py-2 hstack gap-3 nav-item ">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-power"></i>
            </h3>

            <form action="/dashboard/Microfinance/testapp/session.php" method="POST">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>
</div>

<!-- save scroll position -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const savedScroll = localStorage.getItem("sidebarScrollTop");

        if (savedScroll !== null) {
            sidebar.scrollTop = parseInt(savedScroll, 10);
        }

        document.querySelectorAll("#sidebar a").forEach(link => {
            link.addEventListener("click", () => {
                localStorage.setItem("sidebarScrollTop", sidebar.scrollTop);
            });
        });
    });
</script>