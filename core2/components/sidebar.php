<div id="sidebar" class="vstack visually-hidden justify-content-between border">
    <div class="top">
        <div class="d-flex align-items-center justify-content-between mt-2 ps-2" style="height: 3rem;">
            <div class="brand d-flex align-items-center">
                <div class="img_container">
                    <img src="./img/1.5.png" alt="">
                </div>
                <h4 class="mb-0 montserrat-header">TruLend</h4>
            </div>
            <div id="sidebarclose" class="d-flex align-items-center menu-btn small">
                <img src="./img/sidebar.2.svg" alt="">
            </div>
        </div>
        
        <br>
        <p class="px-4 lato-bold">General</p>
        <div class="navs px-4 d-flex flex-column gap-3 align-items-center justify-content-center position-relative">

            <!--Sidebar Anchor List-->
            <div class="py-3 hstack gap-3 nav-item">
                <a class="nunito-nav nunito-nav" href="index.php" >Dashboard</a>
            </div>
              <div class="py-3 hstack gap-3 nav-item">
                <a class="nunito-nav nunito-nav" href="comm1.php" >Comms Mgnt I</a>
            </div>
                   <div class="py-3 hstack gap-3 nav-item">
                <a class="nunito-nav nunito-nav" href="comm2.php" >Comms Mgnt II</a>
            </div>
                   <div class="py-3 hstack gap-3 nav-item">
                <a class="nunito-nav nunito-nav" href="conso.php" >Consolidation</a>
            </div>
            <div class="py-3 hstack gap-3 nav-item">
                <a class="nunito-nav nunito-nav" href="social.php" >Social Performance</a>
            </div>
            
            

        </div>
    </div>
    <div class="container">
        <div id="profile" class="d-flex align-items-center bg-light
                                justify-content-between p-1
                                border-top  rounded-2 
                                flex-column mx-1"
            
                                style="min-height: fit-content">

            <div class="container-fluid lato-regular mt-2">
                <h6><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Undefined'?></h6>
                <small><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Undefined'?></small>
            </div>

            <div id="logoutCon" class="container rounded-pill border d-flex justify-content-center my-2 lato-bold">
                <form action="" method="POST">
                    <button class="btn py-2" name="logout" type="submit">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</div>