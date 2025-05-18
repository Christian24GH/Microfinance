<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="icon" href="{{asset('img/1.4.png')}}"/>
    @vite(['resources/css/app.css', 'resources/css/register.css', 
        'resources/css/media-query.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <main class="position-relative vh-100">
        <div class="container position-absolute top-0 start-0 " style="height: 7rem; width:fit-content">
            <a href="{{route('landing')}}"><img src="{{asset('img/1.3.png')}}" alt="" style="width:100%; height:100%"></a>
        </div>
        <div class="container card border position-absolute top-50 start-50 translate-middle p-5 authform">
            <div class="card-body" style="width: 30rem">
                <h3 class="text-center">Welcome to {{ENV('APP_NAME')}}</h3>
                <br>
                <h6 class="text-center text-body-secondary">Login and start exploring our app!</h6>

                <div class="mt-5 px-2">
                    @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{session('success')}}
                    </div>
                    @endif
                    @if(session('fail'))
                    <div class="alert alert-danger" role="alert">
                        {{session('fail')}}
                    </div>
                    @endif
                    <form id="login" action="{{ route('login.login') }}" method="POST">
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
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror @error('passwordmatch') is-invalid @enderror" name="password" id="password" placeholder="">
                            @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <button type="submit" class="btn w-100 rounded-pill">Sign in</button>
                    </form>
                    <hr>
                    <p class="text-center">Don't have an account?</p>
                    <a type="button" class="btn w-100 rounded-pill" href="{{ route('register.index') }}">Sign up</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>