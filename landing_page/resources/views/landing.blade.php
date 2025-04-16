<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microfinance</title>
    @vite(['resources/css/app.css', 'resources/css/landing.css'])
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #353c61;">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100">

            <a class="navbar-brand text-light fw-bold" href="#">
                <h3 class="m-0">Microfinance</h3>
            </a>

            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler bi bi-list text-light fs-2"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarNav">
                <div class="offcanvas-header justify-content-between">
                    <h5 class="offcanvas-title text-light" id="offcanvasNavbarLabel">{{ ENV('APP_NAME') }}</h5>
                    <button type="button" class="btn" data-bs-dismiss="offcanvas">
                        <span class="bi bi-x text-light fs-2"></span>
                    </button>
                </div>
                <ul class="navbar-nav gap-3 align-items-center justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#"><h6 class="m-0">Features</h6></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="#"><h6 class="m-0">Contact</h6></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{route('login.index')}}"><h6 class="m-0">Sign In</h6></a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light text-dark px-3 py-1" href="{{route('register.index')}}">Sign Up</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <main class="container-fluid px-0 flex-auto">
        <section class="hero d-flex flex-column flex-lg-row justify-content-between align-items-center" style="margin-top: 7rem;">
            
            <div class="col-12 col-lg-6 text-center text-lg-start px-4 px-lg-5 py-5">
                <h2>Your Digital Partner in Micro Lending</h2>
                <p>
                    Experience the power of microfinance made simple.<br>
                    Inclusive. Empowering. Built for your growth.
                </p>
                <a class="btn btn-lg btn-primary" href="{{route('register.index')}}">Get Started</a>
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
        <p class="mb-0">&copy; 2025 {{ ENV('APP_NAME') }}. All rights reserved.</p>
    </footer>


    @vite('resources/js/app.js')
</body>
</html>