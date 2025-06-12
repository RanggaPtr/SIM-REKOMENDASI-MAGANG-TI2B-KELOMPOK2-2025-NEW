<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ url('/images/kopermagang.png') }}">
    <title>@yield('title', 'Magang.In')</title>
    <link rel="icon" href="{{ url('/images/icon.png') }}" type="image/png">
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
    <header class="d-flex justify-between align-items-center pt-3 pb-4 px-4 border-bottom border-primary"
        style="">
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
            <a href="login" class="btn fw-bold btn-outline-primary btn-sm px-3 btn-hover-white">Masuk</a>
            <a href="register" class="btn fw-bold btn-primary text-light-softer ps-4 pe-2 btn-sm">Daftar <span
                    class="ps-3" style="font-size: 13px; background: transparent;">></span></a>
        </div>
    </header>

    <!-- Beranda Section -->
    <section class="content d-flex align-items-center justify-content-center"
        style="height: 90vh; background: transparent;">
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
                    <a href="register" class="btn btn-primary bg-primary text-light-softer ps-5 pe-4 btn-lg fw-bold">Daftar <span
                            class="ps-3" style="font-size: 15px">></span></a>
                    <a href="login" class="btn btn-outline-primary btn-lg px-5 btn-hover-white fw-bold">Masuk</a>
                </div>
            </div> 
        </div>
    </section>

    <!-- Tentang Section -->
    <section class="content d-flex align-items-center justify-content-center"
        style="height: 100vh; background: transparent; scroll-margin-top: 6vh;" id="tentang">
        <div class="container">
            <div class="row align-items-center justify-content-center gap-5">
                <!-- Kolom Kiri: Kotak besar dengan quotes -->
                <div class="col-md-5 mb-4 mb-md-0 d-flex align-items-center justify-content-center"
                    style="height: 48vh;">
                    <div
                        class="col bg-primary rounded-4 px-5 pt-4 pb-0 shadow-sm d-flex flex-column align-items-center justify-content-center w-100 h-100">
                        <blockquote
                            class="bg-primary border border-3 border-light rounded-1 px-5 pt-4 pb-0 w-100 text-center justify-content-center align-items-center d-flex position-relative">
                            <!-- Ikon Quote -->
                            <i class="fa-solid fa-quote-right position-absolute text-light"
                                style="top: -50px; left: -30px; font-size: 80px;"></i>
                            <!-- Kutipan -->
                            <p
                                class="fw-semibold text-light fs-5 mb-4 text-center align-items-center justify-content-center">
                                "Magang bukan hanya kewajiban akademik, tapi langkah nyata membentuk kesiapan kerja. Di
                                sinilah teori diuji dan profesionalisme mulai tumbuh." </p>
                        </blockquote>

                        <!-- Row Profil -->
                        <div class="d-flex align-items-center mt-0 text-start me-auto">
                            <!-- Foto Profil -->
                            <img src="{{ url('/images/kajur_profile.png') }}" alt="Profil" class="rounded-circle me-3"
                                width="40px"px height="40px">

                            <!-- Nama dan Jabatan -->
                            <div class="text-light" style="line-height: 1.3;">
                                <div class="fw-bold mb-0" style="font-size: 1rem;">Rosa Andrie Asmara</div>
                                <div class="small mt-0" style="font-size: 0.85rem;">Ketua Jurusan Teknologi Informasi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Judul + Deskripsi -->
                <div class="col-md-5 text-center text-md-start">
                    <div class="" style="line-height: 60px">
                        <p class="fw-bold text-primary" style="font-size: 50px">Apa <span
                                class="text-secondary">itu</span><br> <span class="text-secondary">Magang.</span>in<span
                                class="text-secondary">?</span>
                        </p>
                    </div>
                    <p class="text-dark fs-5">
                        Magang.in adalah platform terintegrasi yang menghubungkan mahasiswa, dosen, dan
                        mitra industri untuk mempermudah proses magang secara efisien dan sesuai standar.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Panduan Section -->
    <section style="height: 50vh; background: transparent; scroll-margin-top: 30vh;" id="panduan">
        <h1 class="fw-bold text-primary text-center" style="background: transparent; font-size:4rem;">
            <span class="text-secondary" style="background: transparent;">Panduan</span> Penggunaan
        </h1>
        <div class="container h-100 d-flex align-items-center justify-content-center" style="background: transparent;">
            <div class="row w-100 justify-content-center" style="background: transparent;">
                <div class="col-md-3 text-center mb-4" style="background: transparent;">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:80px; height:80px;">
                        <i class="fa-solid fa-right-to-bracket text-white bg-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Login</h5>
                    <p class="text-secondary" style="font-size: 0.95rem; background: transparent;">
                        Masuk ke akun Magang.in Anda untuk mulai menjelajahi fitur dan kesempatan magang.
                    </p>
                </div>
                <div class="col-md-3 text-center mb-4" style="background: transparent;">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:80px; height:80px;">
                        <i class="fa-solid fa-user-pen text-white bg-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Isi Profil</h5>
                    <p class="text-secondary" style="font-size: 0.95rem; background: transparent;">
                        Lengkapi data diri dan profil Anda agar rekomendasi magang lebih sesuai minat dan keahlian.
                    </p>
                </div>
                <div class="col-md-3 text-center mb-4" style="background: transparent;">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:80px; height:80px;">
                        <i class="fa-solid fa-briefcase text-white bg-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Cari Pekerjaan</h5>
                    <p class="text-secondary" style="font-size: 0.95rem; background: transparent;">
                        Temukan dan lamar posisi magang yang sesuai dengan minat dan jurusan Anda.
                    </p>
                </div>
                <div class="col-md-3 text-center mb-4" style="background: transparent;">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:80px; height:80px;">
                        <i class="fa-solid fa-certificate text-white bg-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Terima Sertifikat</h5>
                    <p class="text-secondary" style="font-size: 0.95rem; background: transparent;">
                        Selesaikan magang dan dapatkan sertifikat resmi sebagai bukti pengalaman Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mitra Section -->
    <section
        style="height: 100vh; background: transparent; margin-top:25vh;margin-bottom:20vh; scroll-margin-top: 11vh;"
        id="mitra">
        <div class="container py-5 h-100" style="background: transparent">
            <h1 class="fw-bold text-primary mb-5 text-center" style="background: transparent; font-size: 4rem"><span
                    class="text-secondary" style="background: transparent">Mitra</span> Perusahaan</h1>
            <div class="d-flex align-items-center justify-content-between" class="background: transparent;">
                <!-- Kontainer Perusahaan -->
                <div id="mitra-cards-container" style="background: transparent">
                    @include('component.mitra-cards', ['companies' => $companies])
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-5" style="font-size: 0.98rem;">
        <div class="container bg-dark text-white">
            <div class="row text-start bg-dark">
                <div class="col-md-3 mb-3 bg-dark">
                    <ul class="list-unstyled">
                        <li class="bg-dark">About Us</li>
                        <li class="bg-dark">Feedback</li>
                        <li class="bg-dark">Trust, Safety & Security</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3 bg-dark">
                    <ul class="list-unstyled">
                        <li class="bg-dark">Help & Support</li>
                        <li class="bg-dark">Polinema Foundation</li>
                        <li class="bg-dark">Terms of Service</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3 bg-dark">
                    <ul class="list-unstyled">
                        <li class="bg-dark">Privacy Policy</li>
                        <li class="bg-dark">CA Notice at Collection</li>
                        <li class="bg-dark">Cookie Settings</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3 bg-dark">
                    <ul class="list-unstyled">
                        <li class="bg-dark">Accessibility</li>
                        <li class="bg-dark">Dekstop App</li>
                        <li class="bg-dark">Cookie Policy</li>
                    </ul>
                </div>
            </div>
            <div class="row align-items-center mt-3 bg-dark">
                <div class="col-md-6 text-start bg-dark">
                    <span class="bg-dark">Follow Us: </span>
                </div>
            </div>
            <hr class="border-light my-3">
            <div class="text-center bg-dark" style="font-size: 0.95rem;">
                2015-2025 magang.in® Global Inc.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delegate click event for pagination links
            document.body.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    let url = e.target.closest('a').getAttribute('href');
                    fetchMitraPage(url);
                }
            });

            function fetchMitraPage(url) {
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('mitra-cards-container').innerHTML = data.cards;
                    });
            }
        });
    </script>
</body>

</html>