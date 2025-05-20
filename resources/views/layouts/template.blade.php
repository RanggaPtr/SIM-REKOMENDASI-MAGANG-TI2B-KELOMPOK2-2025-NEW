<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Magang.In')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        <aside class="bg-light-softer ps-3 pe-3 border-end" style="width: 290px;">
            @include('layouts.sidebar', ['activeMenu' => $activeMenu ?? '']) <!-- Tambahkan variabel default -->
        </aside>

        <!-- Main Section: Header + Content -->
        <div class="d-flex flex-column flex-grow-1">
            <!-- Header -->
            <header class="px-4 pt-3 pb-3">
                @include('layouts.header')
            </header>

            <!-- Content -->
            <main class="flex-grow-1 pt-2 px-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
