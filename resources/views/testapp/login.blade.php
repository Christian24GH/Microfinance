<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link href="./resources/css/app.css"rel="stylesheet">
    <link href="./resources/css/sidebar.css"rel="stylesheet">
    <link href="./resources/css/media-query.css"rel="stylesheet">
    <link href="./resources/css/form.css"rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <main class="position-relative vh-100">
        <div class="container card border position-absolute top-50 start-50 translate-middle p-5" style="width: fit-content;">
            <div class="card-body">
                <h3 class="text-center">Welcome to Microfinance</h3>
                <br>
                <h6 class="text-center text-body-secondary">Login and start exploring our app!</h6>

                <div class="mt-5 px-2">
                    <!--Success Alert
                    <div class="alert alert-success" role="alert">
                        Message
                    </div>
                    -->
                    <!--Fail Alert
                    <div class="alert alert-danger" role="alert">
                         Message
                    </div>
                    -->
                    <form id="login" action="" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                            <!--Fail Alert
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            -->
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="">
                            <!--Fail Alert
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            -->
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
                    <a type="button" class="btn w-100 rounded-pill" href="">Sign up</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
