<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Magang.in</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-image: url('/images/bg.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            font-family: 'Istok Web', sans-serif;
        }

        header {
            background-image: url('/images/bg.png');
            position: sticky;
            top: 0;
            width: 100%;
            /* Bisa disesuaikan */
            padding: 10px 20px;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header img {
            height: 50px;
        }

        /* Misal kamu punya card yang butuh background */
        .card {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        section {
            background: transparent;
        }

        .content * {
            background: transparent;
        }

        a {
            text-decoration: none;
        }

        .btn-hover-white:hover {
            color: white !important;
        }
    </style>
</head>

<body>
    <header class="d-flex justify-between align-items-center pt-3 pb-4 px-4 border-bottom border-primary" style="">
        <!-- Logo di kiri -->
        <img src="{{ url('/images/logo.png') }}" alt="Logo" style="height: 50px;">

        <!-- Navigation di tengah -->
        <nav class="d-flex justify-center gap-5 mt-3">
            <a href="#" class="text-secondary fw-bold fs-5 text-decoration-none">Beranda</a>
            <a href="#tentang" class="text-secondary fw-bold fs-5 text-decoration-none">Tentang Kami</a>
            <a href="#panduan" class="text-secondary fw-bold fs-5 text-decoration-none">Panduan</a>
            <a href="#mitra" class="text-secondary fw-bold fs-5 text-decoration-none">Mitra</a>
        </nav>

        <!-- Tombol di kanan -->
        <div class="d-flex gap-3 mt-3">
            <button class="btn fw-bold btn-primary text-light-softer ps-4 pe-2 btn-sm">Daftar <span class="ps-3"
                    style="font-size: 13px; background: transparent;">></span></button>
            <button class="btn fw-bold btn-outline-primary btn-sm px-3 btn-hover-white">Masuk</button>
        </div>
    </header>

    <!-- Beranda Section -->
    <section class="content d-flex align-items-center justify-content-center"
        style="height: 85vh; background: transparent;">
        <div class="container">
            <div class="d-flex flex-column align-items-center text-center">
                <!-- Kolom Pertama: Judul -->
                <div class="" style="line-height: 75px">
                    <p class="fw-bold text-primary" style="font-size: 60px">Portal <span
                            class="text-secondary">Anda</span><br> <span class="text-secondary">Menuju</span> Kesuksesan
                    </p>
                </div>

                <!-- Kolom Kedua: Deskripsi -->
                <div class="mb-4 text-dark" style="max-width: 650px;">
                    <p>
                        Jelajahi Puluhan Kesempatan Magang Impianmu dengan Rekomendasi Paling Cocok,
                        Terpercaya, dan Sesuai Minatmu Bersama Magang.in!
                    </p>
                </div>

                <!-- Kolom Ketiga: Tombol -->
                <div class="d-flex gap-5">
                    <button class="btn btn-primary text-light-softer ps-5 pe-4 btn-lg fw-bold">Daftar <span
                            class="ps-3" style="font-size: 15px">></span></button>
                    <button class="btn btn-outline-primary btn-lg px-5 btn-hover-white fw-bold">Masuk</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section style="height: 100vh" id="tentang">

    </section>

    <!-- Panduan Section -->
    <section style="height: 100vh" id="panduan">

    </section>

    <!-- Mitra Section -->
    <section style="height: 100vh" id="mitra">

    </section>
</body>

</html>
