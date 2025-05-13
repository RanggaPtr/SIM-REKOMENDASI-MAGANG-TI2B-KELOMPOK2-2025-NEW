<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Magang.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f6eb;
        }
        .register-container {
            max-width: 900px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .register-left {
            background-color: #ffd54f;
            padding: 40px;
            color: #333;
        }
        .register-right {
            padding: 40px;
        }
        .register-right h2 {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #ffd54f;
            border: none;
            color: #333;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #ffcc00;
        }
        .form-control, .form-select {
            background-color: #f9f6eb;
        }
        .carousel-caption-bottom {
            position: static;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            margin-top: 10px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .register-left, .register-right {
                padding: 20px;
            }
            .register-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container row">
        <div class="register-left col-md-6 d-flex flex-column justify-content-center align-items-center">
            <div id="slideshowExample" class="carousel slide" data-bs-ride="carousel" aria-label="Promotional slideshow">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/slide1.png') }}" class="d-block w-100" alt="Slide 1">
                        <div class="carousel-caption-bottom">
                            <h5><span style="color: #1976d2;">magang.</span><span style="color: #ffd54f;">in</span> mengutamakan keefisiensian</h5>
                            <p>Temukan magang impian melalui magang.in, efisien dan cocok untuk semua kalangan.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/slide2.png') }}" class="d-block w-100" alt="Slide 2">
                        <div class="carousel-caption-bottom">
                            <h5>Jelajahi Berbagai Kesempatan Magang</h5>
                            <p>Temukan magang yang sesuai dengan minat dan keahlianmu.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/slide3.png') }}" class="d-block w-100" alt="Slide 3">
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
        <div class="register-right col-md-6">
            <img src="{{ asset('images/logo.png') }}" alt="Magang.in" class="mb-3" style="width: 150px;">
            <h2>Selamat Datang di <strong><span style="color: #1976d2;">magang.</span><span style="color: #ffd54f;">in</span></strong></h2>
            <form method="POST" action="{{ route('postregister') }}">
                @csrf
                <div class="mb-3">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <input type="hidden" name="role" value="User">
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                <p class="mt-3 text-center">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (password.length < 8) {
                e.preventDefault();
                alert('Password harus minimal 8 karakter.');
            } else if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Masukkan email yang valid.');
            }
        });
    </script>
</body>
</html>