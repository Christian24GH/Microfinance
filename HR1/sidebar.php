<div id="sidebar" class="vstack px-4">
    <hr>  
    <div id="profile" class="d-flex gap-3 align-items-center ps-1 py-1">
        <div class="border img_container">
            <img src="" class="rounded-circle" alt="">
        </div>
        <div class="lato-regular">
            <h5><?php echo $_SESSION['fullname'] ?? 'Username' ?>Username</h5>
            <h6><?php echo $_SESSION['role'] ?? 'Role' ?>Role</h6>
        </div>
    </div>
    <hr>
    <div class="navs d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
        <div class="pb-2 hstack gap-3 nav-item" >
            <h3 class="rounded-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-speedometer2"></i>
            </h3>
            <a class="nunito-nav" href="index.php?page=home" >Dashboard</a>
        </div>

        <div class="pb-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-calendar-event"></i>
            </h3>
            <a class="nunito-nav" href="index.php?page=vacancy" >Recruitment</a>
        </div>

        <div class="pb-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="index.php?page=applications" >Applicant Management</a>
        </div>

        <div class="pb-2  hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-backpack"></i>
            </h3>
            <a class="nunito-nav" href="index.php?page=sampleNewhired" >New Hired On Board</a>
        </div>  
        
        <?php if($_SESSION['login_type'] == 1): ?>

        <div class="pb-2  hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-backpack"></i>
            </h3>
            <a class="nunito-nav" href="index.php?page=samplePerformance" >Performance Management</a>
        </div>

        <div class="pb-2  hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-backpack"></i>
            </h3>
            <a class="nunito-nav" href="index.php?page=sampleSocial" >Social Recognition</a>
        </div>

        <?php endif; ?>
        
        <div class="d-flex w-100 flex-column">
            <hr>
        </div>
        <!--Logout-->
        <div class="pb-2 hstack gap-3 nav-item ">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center">
                <i class="bi bi-power"></i>
            </h3>
            
            <form method="POST">
                <button type="submit" name="logout" class="nunito-nav">Logout</button>
            </form>
        </div>
    </div>
</div>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>