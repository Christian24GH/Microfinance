<div id="sidebar" class="vstack d-flex justify-content-between visually-hidden scrollbar">
    <div class="top">
        <div class="d-flex align-items-center ps-2" style="height: 3rem;">
            <h4 class="mb-0">TruLend</h4>
        </div>
        <br>
        <div class="navs px-4 d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="./dashboard.php" >Dashboard</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="#" >Profile</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="./loan.php" >Loan</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="#" >Documents</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="#" >Loan Status</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="./users.php" >Client</a>
            </div>

            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="./loanmoni.php" >Loan Monitoring</a>
            </div>

            <div class="py-2  hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="#" >Loan Restructuring</a>
            </div>
            
            <div class="py-2 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-stack"></i>
                </h3>
                <a class="nunito-nav" href="#" >Document Management</a>
            </div>
        </div>
    </div>
    <div class="d-flex w-100 flex-column mt-2">
       <hr>
    </div>
    <div class="container ">
        <div id="profile" class="d-flex align-items-center justify-content-between px-4 pt-1"
            style="height: 5rem">

            <div class="lato-regular">
                <h6><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Undefined';?></h6>
                <small><?php echo isset($_SESSION['role']) ? $_SESSION['role']: 'Undefined';?></small>
            </div>

            <form action="" method="POST">
                <div class="dropdown">
                    <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </a>    
                    <ul class="dropdown-menu">
                        <button class="btn dropdown-item" type="submit" name="logout">
                            <i class="bi bi-box-arrow-left"></i>
                            Logout
                        </button>
                    </ul>
                </div>
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