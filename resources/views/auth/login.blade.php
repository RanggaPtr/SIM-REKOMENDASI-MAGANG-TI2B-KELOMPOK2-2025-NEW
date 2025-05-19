<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ url('/images/kopermagang.png') }}">
    <title>Login - Magang.in</title>
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdf6e3;
            font-family: 'Poppins', Arial, Helvetica, sans-serif;
            color: #1a237e;
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            pointer-events: none;
            background-image: radial-gradient(rgba(255, 215, 79, 0.15) 1px, transparent 1px);
            background-size: 18px 18px;
        }
        .login-container {
            max-width: 900px;
            margin: 70px auto 0 auto;
            background: transparent;
            box-shadow: none;
            border-radius: 0;
        }
        .login-left {
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
        }
        .login-right {
            background: #fffbe7;
            padding: 50px 40px 40px 40px;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(255, 215, 79, 0.10);
            border: 1.5px solid #ffe082;
            position: relative;
            z-index: 1;
        }
        .login-right h2 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 18px;
            color: #1a237e;
        }
        .login-right h2 strong span {
            font-weight: 700;
        }
        .login-right h2 strong span:first-child {
            color: #1976d2;
        }
        .login-right h2 strong span:last-child {
            color: #ffd54f;
        }
        .login-right label {
            color: #1a237e;
            font-weight: 500;
        }
        .form-control {
            background-color: #fdf6e3;
            border: 1.5px solid #ffe082;
            color: #1a237e;
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #ffd600;
            box-shadow: 0 0 0 0.2rem rgba(255, 214, 79, 0.15);
        }
        .btn-primary {
            background: #ffd600;
            color: #1a237e;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: #ffb300;
            color: #fff;
        }
        a {
            color: #1a237e;
            text-decoration: underline;
            font-weight: 600;
        }
        a:hover {
            color: #ffd600;
            text-decoration: underline;
        }
        .alert {
            border-radius: 7px;
            font-size: 0.98rem;
            font-weight: 500;
        }
        .carousel-caption-bottom h5 {
            color: #1a237e;
            font-weight: 700;
        }
        .carousel-caption-bottom span {
            color: #ffd54f;
            font-weight: 700;
        }
        .carousel-caption-bottom {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            margin-top: 10px;
            color: #1a237e;
            font-weight: 500;
            padding: 15px;
            text-align: center;
        }
        @media (max-width: 991.98px) {
            .login-container {
                max-width: 98vw;
                margin-top: 30px;
            }
            .login-right {
                padding: 30px 10px;
            }
        }
        @media (max-width: 767.98px) {
            .login-container {
                flex-direction: column;
            }
            .login-left, .login-right {
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 20px 5px;
            }
            .login-right {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container login-container row g-0 shadow">
        <!-- Slideshow Kiri -->
        <div class="login-left col-md-6 d-flex flex-column justify-content-center align-items-center">
            <div id="slideshowExample" class="carousel slide w-100" data-bs-ride="carousel" aria-label="Promotional slideshow">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/slide1.png') }}" class="d-block w-100 rounded-3" alt="Slide 1">
                        <div class="carousel-caption-bottom">
                            <h5>
                                <span style="color: #1976d2;">magang.</span>
                                <span style="color: #ffd54f;">in</span> mengutamakan keefisiensian
                            </h5>
                            <p>Temukan magang impian melalui magang.in, efisien dan cocok untuk semua kalangan.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/slide2.png') }}" class="d-block w-100 rounded-3" alt="Slide 2">
                        <div class="carousel-caption-bottom">
                            <h5>Jelajahi Berbagai Kesempatan Magang</h5>
                            <p>Temukan magang yang sesuai dengan minat dan keahlianmu.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/slide3.png') }}" class="d-block w-100 rounded-3" alt="Slide 3">
                        <div class="carousel-caption-bottom">
                            <h5>Dapatkan Pengalaman Berharga</h5>
                            <p>Tingkatkan keterampilanmu dengan pengalaman magang yang bermanfaat.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#slideshowExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#slideshowExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Form Login Kanan -->
        <div class="login-right col-md-6">
            <img src="{{ asset('images/logo.png') }}" alt="Magang.in" class="mb-3" style="width: 150px;">
            <h2>
                Selamat Datang di 
                <strong>
                    <span style="color: #1976d2;">magang.</span>
                    <span style="color: #ffd54f;">in</span>
                </strong>
            </h2>
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('postlogin') }}">
                @csrf
                <div class="mb-3">
                    <label for="username">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
                <p class="mt-3 text-center">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi sederhana (opsional)
        document.querySelector('form').addEventListener('submit', function (e) {
            const username = document.getElementById('username').value;
            if (username.trim() === '') {
                e.preventDefault();
                alert('Username tidak boleh kosong.');
            }
        });
    </script>
</body>
</html>
