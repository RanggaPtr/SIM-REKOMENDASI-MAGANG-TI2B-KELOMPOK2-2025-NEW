<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Magang.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f6eb;
        }

        .login-container {
            max-width: 900px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .login-left {
            background-color: #ffd54f;
            padding: 40px;
            color: #333;
        }

        .login-right {
            padding: 40px;
        }

        .login-right h2 {
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
    </style>
</head>

<body>
    <div class="login-container row">
        <div class="login-left col-md-6 d-flex flex-column justify-content-center align-items-center">
            <div id="slideshowExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="http://sim-rekomendasi-magang-ti2b-kelompok2-2025.test/images/slide1.png" class="d-block w-100" alt="Slide 1">
                        <div class="carousel-caption-bottom">
                            <h5><span style="color: #1976d2;">magang.</span>
                                <span style="color: #ffd54f;">in</span> mengutamakan keefisiensian</h5>
                            <p>Temukan magang impian melalui magang.in, efisien dan cocok untuk semua kalangan.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="http://sim-rekomendasi-magang-ti2b-kelompok2-2025.test/images/slide2.png" class="d-block w-100" alt="Slide 2">
                        <div class="carousel-caption-bottom">
                            <h5>Jelajahi Berbagai Kesempatan Magang</h5>
                            <p>Temukan magang yang sesuai dengan minat dan keahlianmu.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="http://sim-rekomendasi-magang-ti2b-kelompok2-2025.test/images/slide3.png" class="d-block w-100" alt="Slide 3">
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

        <div class="login-right col-md-6">
            <img src="http://sim-rekomendasi-magang-ti2b-kelompok2-2025.test/images/logo.png"alt="Magang.in" class="mb-3" style="width: 150px;">
            <h2>Selamat Datang di <strong>
                    <span style="color: #1976d2;">magang.</span>
                    <span style="color: #ffd54f;">in</span>
                </strong>
            </h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
                <p class="mt-3 text-center">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
