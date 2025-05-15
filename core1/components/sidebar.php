<div id="sidebar" class="vstack px-4" style="overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
    <div class="py-3">
        <h3 class="montserrat-header">Core 1</h3>
    </div>
    
    <div id="profile" class="d-flex gap-3 align-items-center ps-1 py-4">
        <div class="border img_container">
            <img src="" class="rounded-circle" alt="">
        </div>
        <div class="lato-regular">
            <h5><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Undefined';?></h5>
            <h6><?php echo isset($_SESSION['role']) ? $_SESSION['role']: 'Undefined';?></h6>
        </div>
    </div>
    <hr>
    <div class="navs d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/dashboard.php" >Dashboard</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/profile.php" >Profile</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/loan.php" >Loan</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/documents.php" >Documents</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/loanstat.php" >Loan Status</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/users.php" >Users</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/loanmoni.php" >Loan Monitoring</a>
        </div>

        <div class="py-2  hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/loanrestr.php" >Loan Restructuring</a>
        </div>
           
        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="../testapp/documng.php" >Document Management</a>
        </div>
        
        <div class="d-flex w-100 flex-column">
            <hr>
        </div>
        <!--Logout-->
        <div class="py-2 hstack gap-3 nav-item ">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-power"></i>
            </h3>
            
            <form method="POST">
                <button onclick="window.location.href='components/logout.php'" name="logout" class="nunito-nav">Logout</button>
            </form>
        </div>
    </div>
</div>