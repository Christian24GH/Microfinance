<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    @vite(['resources/css/app.css','resources/css/sidebar.css', 'resources/js/app.js'])
</head>
<body>
    @include('sweetalert2::index')
    @yield('modal')
    <div class="container-fluid px-0">
        @include('components.sidebar')
        <div class="" style="margin-left: 25rem">
            @yield('content_header')
            @yield('content_pagination')
            @yield('content')
        </div>
    </div>
</body>
</html>