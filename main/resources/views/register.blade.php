<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="icon" href="{{asset('img/1.4.png')}}"/>
    <script>const registerUrl = "{{ route('register.store') }}";</script>
    @vite(['resources/css/app.css', 'resources/css/register.css', 
        'resources/css/media-query.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <main class="position-relative vh-100">
        <div class="container position-absolute top-0 start-0 " style="height: 7rem; width:fit-content">
            <a href="{{route('landing')}}"><img src="{{asset('img/1.3.png')}}" alt="" style="width:100%; height:100%"></a>
        </div>
        <div class="container card border position-absolute top-50 start-50 translate-middle p-5 authform shadow-sm">
            <div class="card-body">
                <h3 class="text-center">Welcome to {{ENV('APP_NAME')}}</h3>
                <br>
                <h6 class="text-center text-body-secondary">Register to create your first account and start exploring our app!</h6>

                <div class="mt-5 px-2">
                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{session('success')}}
                    </div>
                    @endif
                    <form id="register" action="{{ route('register.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control @error('email')is-invalid @enderror" name="email" id="email" placeholder="name@example.com">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Fullname</label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" id="fullname" placeholder="Firstname Lastname">
                            @error('fullname')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror @error('passwordmatch') is-invalid @enderror" name="password" id="password" placeholder="">
                            @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="confirmpassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control @error('confirmpassword') is-invalid @enderror @error('passwordmatch') is-invalid @enderror" name="confirmpassword" id="confirmpassword" placeholder="">
                            @error('confirmpassword')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            @error('passwordmatch')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn w-100 rounded-pill">Sign up</button>
                    </form>
                    <hr>
                    <p class="text-center">Already have an account?</p>
                    <a type="button" class="btn w-100 rounded-pill" href="{{ route('login.index') }}">Sign In</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>