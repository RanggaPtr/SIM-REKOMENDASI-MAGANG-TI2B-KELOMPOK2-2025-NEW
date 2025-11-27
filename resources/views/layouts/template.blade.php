<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ url('/images/kopermagang.png') }}">
    <title>@yield('title', 'Magang.In')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (session('api_token'))
        <meta name="api-token" content="{{ session('api_token') }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('css')
</head>

<body>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Prevent body scroll */
        }

        .main-layout {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        .sidebar-fixed {
            width: 20%;
            min-width: 220px;
            max-width: 320px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1030;
            border-right: 1px solid #eee;
            background: #FFF8F1;
            overflow-y: auto;
        }

        .main-content-area {
            margin-left: 20%;
            width: 80%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header-fixed {
            position: sticky;
            top: 0;
            z-index: 1040;
            background: #FFF6DF;
        }

        .scrollable-main {
            flex: 1 1 auto;
            overflow-y: auto;
            min-height: 0;
        }

        .modal-backdrop {
            opacity: 0.5 !important;
            z-index: -1050 !important;
        }
    </style>
    <div class="main-layout">
        <!-- Sidebar -->
        <aside class="sidebar-fixed">
            @include('layouts.sidebar', ['activeMenu' => $activeMenu ?? ''])
        </aside>

        <!-- Main Section: Header + Content -->
        <div class="main-content-area">
            <!-- Header -->
            <header class="header-fixed px-4 pb-3" style="margin-top: 2.5%">
                @include('layouts.header')
            </header>

            <!-- Content -->
            <main class="scrollable-main pt-2 px-4 pb-4">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- di bagian <head> atau sebelum </body> -->

    @stack('scripts')
</body>

</html>
