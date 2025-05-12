<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title}}</title>
    @vite(['resources/css/app.css',
        'resources/css/sidebar.css',
        'resources/js/app.js',
        'resources/js/sidebar.js'])
</head>
<body class="bg-light">
    @include('sweetalert2::index')
    @yield('modal')
    @include('components.sidebar')
    <div id="main" class="ps-0 rounded-end visually-hidden">
        @include('components.header')
        @include('components.content_header')
        @yield('content_pagination')
        @yield('content')
    </div>
</body>
</html>