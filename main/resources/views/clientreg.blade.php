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
                        <a class="nav-link text-light" href="{{url('/#loan');}}">
                            <h6 class="m-0 lato-bold">Packages</h6>
                        </a>
                    </li>

                    <li class="nav-item dropdown position-relative">
                        <div class="nav-link text-light lato-bold" id="workWithUsDropdown">
                            Work with Us
                        </div>
                        <ul class="custom-dropdown-menu list-unstyled m-0 p-2 bg-light rounded shadow position-absolute">
                            <li><a class="dropdown-item px-2 py-1" href="{{route('client.register.index')}}">Customer</a></li>
                            <li><a class="dropdown-item px-2 py-1" href="">Employee</a></li>
                            <li><a class="dropdown-item px-2 py-1" href="https://pub-domain.onrender.com/docs/dashboard/vendor_signup/">Vendor</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{url('/#about');}}">
                            <h6 class="m-0 lato-bold">About Us</h6>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-light text-dark px-3 py-1 lato-bold" href="{{ route('login.index') }}">Sign In</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <main class="container-fluid px-0 flex-auto relative">
        <div class="px-5" style="margin-top: 7rem;">
            <h1>Client Registration</h1>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-warning" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            <form id="register" action="{{route('client.register.store')}}" method="POST" enctype="multipart/form-data" class="p-4">
                @csrf
                <h3 class="mb-3">Client Information</h3>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email address:</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="example@gmail.com" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                 
                    <div class="col-md-4">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="middle_name" class="form-label">Middle Name:</label>
                        <input type="text" class="form-control" name="middle_name" id="middle_name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="sex" class="form-label">Sex:</label>
                        <select name="sex" id="sex" class="form-select" required>
                            <option disabled selected value="">Choose...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="civil_status" class="form-label">Civil Status:</label>
                        <select name="civil_status" id="civil_status" class="form-select" required>
                            <option disabled selected value="">Choose...</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Seperated">Seperated</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="birthdate" class="form-label">Birthdate:</label>
                        <input type="date" class="form-control" name="birthdate" id="birthdate" required>
                    </div>
                    <div class="col-md-4">
                        <label for="contact_number" class="form-label">Contact Number:</label>
                        <input type="number" class="form-control" name="contact_number" id="contact_number" required>
                    </div>
                    <div class="col-md-4">
                        <label for="address" class="form-label">Address:</label>
                        <input type="text" class="form-control" name="address" id="address" required>
                    </div>
                    <div class="col-md-4">
                        <label for="barangay" class="form-label">Barangay:</label>
                        <input type="text" class="form-control" name="barangay" id="barangay" required>
                    </div>
                    <div class="col-md-4">
                        <label for="city" class="form-label">City:</label>
                        <input type="text" class="form-control" name="city" id="city" placeholder="Quezon City" required>
                    </div>
                    <div class="col-md-4">
                        <label for="province" class="form-label">Province:</label>
                        <input type="text" class="form-control" name="province" id="province" placeholder="Metro Manila" required>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="mb-3">Employment Information</h3>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="employer_name" class="form-label">Employer Name:</label>
                        <input type="text" class="form-control" name="employer_name" id="employer_name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="position" class="form-label">Position:</label>
                        <input type="text" class="form-control" name="position" id="position" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Employer Address:</label>
                        <input type="text" class="form-control" name="address" id="address" required>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="mb-3">Contact References</h3>
                <h5>First Reference</h5>
                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        <label for="fr_first_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="fr_first_name" id="fr_first_name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fr_last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="fr_last_name" id="fr_last_name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fr_relationship" class="form-label">Relationship:</label>
                        <select name="fr_relationship" id="fr_relationship" class="form-select" required>
                            <option disabled selected value="">Choose...</option>
                            <option value="Mother">Mother</option>
                            <option value="Father">Father</option>
                            <option value="Siblings">Siblings</option>
                            <option value="Friends">Friends</option>
                            <option value="Collegue">Collegue</option>
                            <option value="Relatives">Relatives</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="fr_contact_number" class="form-label">Contact Number:</label>
                        <input type="number" class="form-control" name="fr_contact_number" id="fr_contact_number" required>
                    </div>
                    <div class="col-md">
                        <label for="fr_email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="fr_email" id="fr_email" required>
                    </div>
                </div>
                 <h5>Second Reference</h5>
                <div class="row mb-3 g-3">
                   
                    <!-- Second reference -->
                    <div class="col-md-4">
                        <label for="sr_first_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="sr_first_name" id="sr_first_name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="sr_last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="sr_last_name" id="sr_last_name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="sr_relationship" class="form-label">Relationship:</label>
                        <select name="sr_relationship" id="sr_relationship" class="form-select" required>
                            <option disabled selected value="">Choose...</option>
                            <option value="Mother">Mother</option>
                            <option value="Father">Father</option>
                            <option value="Siblings">Siblings</option>
                            <option value="Friends">Friends</option>
                            <option value="Collegue">Collegue</option>
                            <option value="Relatives">Relatives</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="sr_contact_number" class="form-label">Contact Number:</label>
                        <input type="number" class="form-control" name="sr_contact_number" id="sr_contact_number" required>
                    </div>
                    <div class="col-md">
                        <label for="sr_email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="sr_email" id="sr_email" required>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="mb-3">Financial Information</h3>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="source_of_funds" class="form-label">Source of Funds:</label>
                        <select name="source_of_funds" id="source_of_funds" class="form-select" required>
                            <option disabled selected value="">Choose...</option>
                            <option value="Employment">Employment</option>
                            <option value="Savings">Savings</option>
                            <option value="Allowance">Allowance</option>
                            <option value="Business">Business</option>
                            <option value="Pension">Pension</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="monthly_income" class="form-label">Monthly Income:</label>
                        <input type="number" class="form-control" name="monthly_income" id="monthly_income" required>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="mb-3">Document Information</h3>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="docu_type" class="form-label">Valid ID:</label>
                        <select name="docu_type" id="docu_type" class="form-select" required>
                            <option disabled selected value="">Choose...</option>
                            <option value="Driver's License">Driver's License</option>
                            <option value="Passport">Passport</option>
                            <option value="SSS">SSS</option>
                            <option value="UMID">UMID</option>
                            <option value="PhilID">PhilID</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Front ID Picture:</label>
                        <input type="file" class="form-control" name="document_one" id="document_one" accept="image/png, image/jpeg" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Back ID Picture:</label>
                        <input type="file" class="form-control" name="document_two" id="document_two" accept="image/png, image/jpeg" required>
                    </div>
                </div>
                
            <div class="d-flex flex-column align-items-center justify-content-cneter gap-2 mt-4">
                <button type="submit" class="btn btn-primary rounded-pill w-50">Register</button>
            </div>
        </form>

        </div>
    </main>

    {{--<footer class="text-center">
        <p class="mb-0">&copy; 2025 {{ ENV('APP_NAME') }}. All rights reserved.</p>
    </footer>--}}


    @vite('resources/js/app.js')
</body>
</html>