<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIM Magang')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.header')
    <div class="app-container">
        @include('layouts.sidebar')
        <div class="content">
            @include('layouts.breadcrumb')
            @yield('content')
        </div>
    </div>
    @include('layouts.footer')
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>