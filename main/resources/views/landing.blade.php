<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trulend</title>
    <link rel="icon" href="{{asset('img/1.4.png')}}"/>
    @vite(['resources/css/app.css', 'resources/css/landing.css'])
    
</head>
<body id="trulend">
    
    <nav class="navbar navbar-expand-lg mt-2 mx-5 rounded-4 fixed-top" style="min-height:fit-content;  max-height: 10vh; background-color: #353c61;">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100">
            <div class="d-flex align-items-center">
                {{--
                <div class="container p-0" style="height: 7rem; width:fit-content">
                    <a href="#trulend"><img src="{{asset('img/2.png')}}" alt="" style="width:100%; height:100%"></a>
                </div>--}}
                
                <a class="navbar-brand text-light fw-bold" href="#trulend">
                    <h1 class="m-0 montserrat-header">{{ENV('APP_NAME')}}</h1>
                </a>
            </div>

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
                        <a class="nav-link text-light" href="#loan"><h6 class="m-0 lato-bold">Packages</h6></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light" href="#about"><h6 class="m-0 lato-bold">Work with Us</h6></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light" href="#about"><h6 class="m-0 lato-bold">About Us</h6></a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-light text-dark px-3 py-1 lato-bold" href="{{route('login.index')}}">Sign In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-0 flex-auto relative">
        <div id="heroSection" class="hero d-flex justify-content-between align-items-center mx-5" style="margin-top: 7rem;">
            <div class="w-50 h-100 d-flex flex-column justify-content-center ps-4">
                <h2 class="lato-bold">Your Digital Partner in Micro Lending</h2>
                <p class="lato-regular w-100">
                    Experience the power of microfinance made simple.<br>
                    Inclusive. Empowering. Built for your growth.
                </p>
                <a class="btn btn-lg btn-primary lato-bold" href="{{route('register.index')}}" style="width:fit-content">Get Started</a>
            </div>
            <div id="pictureCon" class="w-50 h-100">
                <img id="img1" src="{{asset('img/PicLarge.png')}}" alt="">
                <img id="img2" src="{{asset('img/PicSmall.png')}}" alt="">
            </div>
        </div>
        <div class="container-fluid" style="background:var(--mfc4)">
            <div id="loan" class="my-5" style="height: 7rem;"></div>
            <div  class="services mx-5 mt-5">
                <h3 class="text-center mb-4">Micro Loan Packages</h3>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">
                            <div class="card" style="height: 100%;">
                                <div class="card-body vstack justify-content-between">
                                    <div class="container">
                                        <h5 class="card-title mb-4">Personal Loan</h5>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Get ₱50,000</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>12% Interest per Year</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Terms of 24 Months</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Monthly Payment ₱2,500</p>
                                        </div>
                                        <hr>
                                        <h6 class="mb-4">Requirements</h6>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>₱25,000 monthly income, good credit</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Collateral: None</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Valid Until: 30 days</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Fee: Fee: ₱3,000</p>
                                        </div>
                                    </div>
                                    <div class="container-fluid d-flex justify-content-center">
                                        <a href="http://localhost/dashboard/Microfinance/core1/components/register.php" class="btn applyNow" style="background-color:var(--mf3)">Apply Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body vstack justify-content-between">
                                    <div class="container">
    
                                        <h5 class="card-title mb-4">Home Improvement Loan</h5>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Amount: ₱150,000</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Interest: 8% per year</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Term: 36 months</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Monthly Payment: ₱5,000</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Bonus: 1-month payment delay</p>
                                        </div>
                                        <hr>
                                        <h6 class="mb-4">Requirements</h6>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Requirements: Must own a home, high credit</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Collateral: Home</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Valid Until: 45 days</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Fee: Fee: ₱3,000</p>
                                        </div>
                                    </div>
                                    <div class="container-fluid d-flex justify-content-center">
                                        <a href="http://localhost/dashboard/Microfinance/core1/components/register.php" class="btn applyNow" style="background-color:var(--mf3)">Apply Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body vstack justify-content-between">
                                    <div class="container">
    
                                        <h5 class="card-title mb-4">Emergency Loan</h5>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Amount: ₱20,000</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Interest: 15% per year</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Term: 6 months</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Weekly Payment: ₱900</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Bonus: 1-month payment delay</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Bonus: Fast approval (24 hrs)</p>
                                        </div>
                                        <hr>
                                        <h6 class="mb-4">Requirements</h6>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Requirements: Must be employed</p>
                                        </div>
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>Collateral: None</p>
                                        </div>
                                        
                                        <div class="ms-1 d-flex gap-1">
                                            <i class="bi bi-check-lg"></i>
                                            <p>
                                            Fee: ₱500</p>
                                        </div>
                                    </div>
                                    <div class="container-fluid d-flex justify-content-center">
                                        <a href="http://localhost/dashboard/Microfinance/core1/components/register.php" class="btn applyNow" style="background-color:var(--mf3)">Apply Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="about" class="my-5" style="height: 7rem;"></div>
            <div id="" class="about mx-5 mt-5 py-5">
                <h3 class="text-center mb-4">About Us</h3>
                <h6 class="text-center mb-4">Departments</h6>
                <div class="container">
                    <div class="row">
                        <div class="col-3">
                            <div class="card" style="height: 10rem;">
                                <div class="card-body vstack justify-content-between">
                                    <h5 class="mb-0 card-title">Human Resource</h5>
                                    <div class="container">
                                        <a href="#" class="btn applyNow" style="background-color:var(--mf3); width:fit-content">Work with us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card" style="height: 10rem;">
                                <div class="card-body">
                                    <h5 class="mb-0 card-title">Core</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card" style="height: 10rem;">
                                <div class="card-body">
                                    <h5 class="mb-0 card-title">Financials</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card" style="height: 10rem;">
                                <div class="card-body vstack justify-content-between">
                                    <h5 class="mb-0 card-title">Logistics</h5>
                                    <a href="https://admin-domain.onrender.com/" class="btn applyNow" style="background-color:var(--mf3); width:fit-content">Become a partner</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container d-flex justify-content-center flex-column mt-5">
                    <h6 class="text-center mb-4">Contacts</h6>
                    <p class="body-text text-center">Get in touch with us at <strong>Trulend@gmail.com</strong></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center">
        <p class="mb-0">&copy; 2025 {{ ENV('APP_NAME') }}. All rights reserved.</p>
    </footer>


    @vite('resources/js/app.js')
</body>
</html>