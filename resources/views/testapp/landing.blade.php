<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microfinance</title>
    <link href="./resources/css/app.css"rel="stylesheet">
    <link href="./resources/css/landing.css"rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #353c61;">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100">

            <a class="navbar-brand text-light fw-bold" href="#">
                <h3 class="m-0 montserrat-header">Microfinance</h3>
            </a>

            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler bi bi-list text-light fs-2"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarNav">
                <div class="offcanvas-header justify-content-between">
                    <h5 class="offcanvas-title text-light" id="offcanvasNavbarLabel">Microfinance</h5>
                    <button type="button" class="btn" data-bs-dismiss="offcanvas">
                        <span class="bi bi-x text-light fs-2"></span>
                    </button>
                </div>
                <ul class="navbar-nav gap-3 align-items-center justify-content-end">

                    <li class="nav-item">
                        <a class="nav-link text-light" href="#"><h6 class="m-0 lato-bold">About Us</h6></a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-light text-dark px-3 py-1 lato-bold" href="login.html">Sign In</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <main class="container-fluid px-0 flex-auto">
        <section class="hero d-flex flex-column flex-lg-row justify-content-between align-items-center" style="margin-top: 7rem;">

            <div class="col-12 col-lg-6 text-center text-lg-start px-4 px-lg-5 py-5">
                <h2 class="lato-bold">Your Digital Partner in Micro Lending</h2>
                <p class="lato-regular">
                    Experience the power of microfinance made simple.<br>
                    Inclusive. Empowering. Built for your growth.
                </p>
                <a class="btn btn-lg btn-primary lato-bold" href="login.html">Get Started</a>
            </div>

            <div class="col-12 col-lg-6 d-flex justify-content-center align-items-center p-4">
                <!-- Add an image or illustration here if needed -->
            </div>

        </section>

        <section class="features">
            <!-- Feature content here -->
        </section>
    </main>

    <footer class="text-center">
        <p class="mb-0">&copy; 2025 Microfinance. All rights reserved.</p>
    </footer>
</body>
</html>
