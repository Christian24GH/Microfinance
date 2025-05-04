<div id="sidebar" class="vstack px-4">
    <div class="py-3">
        <h3 class="montserrat-header">Module Name</h3>
    </div>
    
    <div id="profile" class="d-flex gap-3 align-items-center ps-1 py-4">
        <div class="border img_container">
            <img src="" class="rounded-circle" alt="">
        </div>
        <div class="lato-regular">
            <h5><?php echo $_SESSION['fullname'] ?? 'Username' ?></h5>
            <h6><?php echo $_SESSION['role'] ?? 'Role' ?></h6>
        </div>
    </div>
    <hr>
    <div class="navs d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
        <div class="py-2 hstack gap-3 nav-item" >
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-speedometer2"></i>
            </h3>
            <a class="nunito-nav" href="" >Dashboard</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-calendar-event"></i>
            </h3>
            <a class="nunito-nav" href="" >Page</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="" >Page</a>
        </div>

        <div class="py-2  hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-backpack"></i>
            </h3>
            <a class="nunito-nav" href="" >Page</a>
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
                <button type="submit" name="logout" class="nunito-nav">Logout</button>
            </form>
        </div>
    </div>
</div>