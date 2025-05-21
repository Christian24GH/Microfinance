<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <script>const registerUrl = "{{ route('register.store') }}";</script>
    @vite(['resources/css/app.css', 'resources/css/register.css',
        'resources/css/media-query.css', 'resources/js/app.js'])
</head>
<body>
    <main class="position-relative vh-100">
        <div class="container card border position-absolute top-50 start-50 translate-middle p-5 authform">
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
                            <label for="name" class="form-label">Fullname</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Firstname Lastname">
                            @error('name')
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
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="">
                            @error('password_confirmation')
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
