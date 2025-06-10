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
            background-color: #f9f6eb;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            max-width: 900px;
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .login-left {
            background-color: #EEB521;
            padding: 40px;
            color: #333;
            height: 100%;
        }

        .login-right {
            padding: 40px;
        }

        .login-right h2 {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #EEB521;
            border: none;
            color: #333;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #EEB521;
        }

        .form-control {
            background-color: #f9f6eb;
        }

        .carousel-caption-bottom {
            position: static;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            margin-top: 10px;
            text-align: center;
        }
        
        .carousel-item img {
            max-height: 400px;
            object-fit: contain;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-left, .login-right {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="login-container row g-0 shadow">
                <!-- Slideshow Kiri -->
                <div class="login-left col-md-6 d-flex flex-column justify-content-center align-items-center">
                    <div id="slideshowExample" class="carousel slide w-100" data-bs-ride="carousel" aria-label="Promotional slideshow">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/slide1.png') }}" class="d-block w-100 rounded-3" alt="Slide 1">
                                <div class="carousel-caption-bottom">
                                    <h5>
                                        <span style="color: #092C6B;">magang.</span>
                                        <span style="color: #EEB521;">in</span> mengutamakan keefisiensian
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
                            <span style="color: #092C6B;">magang.</span>
                            <span style="color: #EEB521;">in</span>
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
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan NIM ATAU NIK" required>
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