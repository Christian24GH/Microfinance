<div id="sidebar" class="vstack d-flex justify-content-between visually-hidden">
    <div class="top">
        <div class="d-flex align-items-center ps-2" style="height: 3rem;">
            <h4 class="mb-0">TruLend</h4>
        </div>
        <br>
        <p class="px-4">Admin Control</p>
        <div class="navs px-4 d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
            <div class="py-0 hstack gap-3 nav-item" >
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-box"></i>
                </h3>
                <a class="nunito-nav" href="index.php">Dashboard</a>
            </div>
            <div class="py-0 hstack gap-3 nav-item">
                <h3 class="rounded-3 d-flex align-items-center justify-content-center"  style="height: 3rem; width:3rem">
                    <i class="bi bi-box"></i>
                </h3>
                <a class="nunito-nav" data-bs-toggle="collapse" role="button" href="#commscollapse" >Communication</a>
            </div>
            <div class="collapse" id="commscollapse">
                <div class="hstack gap-3 nav-item">
                    <a href="comm1.php" class="sidebar-link">Comm Mgmt1</a>
                </div>
                <div class="hstack gap-3 nav-item">
                    <a href="comm2.php" class="sidebar-link">Comm Mgmt2</a>
                </div>
            </div>
            <div class="py-0 hstack gap-3 nav-item" >
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-box"></i>
                </h3>
                <a class="nunito-nav" href="">Savings Tracking</a>
            </div>
            <div class="py-0 hstack gap-3 nav-item" >
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-box"></i>
                </h3>
                <a class="nunito-nav" href="">Consolidation</a>
            </div>
            <div class="py-0 hstack gap-3 nav-item" >
                <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                    <i class="bi bi-box"></i>
                </h3>
                <a class="nunito-nav" href="spm.php">Social Performance</a>
            </div>
        </div>
    </div>
    <div class="container border-top">
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