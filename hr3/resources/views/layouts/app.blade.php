<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Microfinance')</title>
    <!-- Tailwind CSS for module styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/media-query.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('testapp.components.sidebar')
        <!-- Main Content Wrapper -->
        <div class="flex-grow-1" style="min-height:100vh;">
            <!-- Topbar/Header -->
            <div class="bg-white border-bottom px-4 d-flex align-items-center" style="height:56px;">
                <i class="bi bi-card-list fs-4 me-2"></i>
                <span class="fs-5 fw-semibold">Department Name</span>
            </div>
            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>
