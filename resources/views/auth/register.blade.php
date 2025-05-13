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
            background-color: #727272;
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
            background-color: #87857f;
            border: none;
            color: #333;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #020201;
        }

        .form-select {
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
    <div class="register-container row">
        <div class="register-left col-md-6 d-flex flex-column justify-content-center align-items-center">
            <div id="slideshowExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../public/images/slide1.jpg" class="d-block w-100" alt="Slide 1">
                        <div class="carousel-caption-bottom">
                            <h5><strong>magang.in</strong> mengutamakan keefisiensian</h5>
                            <p>Temukan magang impian melalui magang.in, efisien dan cocok untuk semua kalangan.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../public/images/slide2.jpg" class="d-block w-100" alt="Slide 2">
                        <div class="carousel-caption-bottom">
                            <h5>Jelajahi Berbagai Kesempatan Magang</h5>
                            <p>Temukan magang yang sesuai dengan minat dan keahlianmu.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="../public/images/slide3.png" class="d-block w-100" alt="Slide 3">
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
            <img src="../public/images/logo.png" alt="Magang.in" class="mb-3" style="width: 150px;">
            <h2>Selamat Datang di <strong>
                    <span style="color: #1976d2;">magang.</span>
                    <span style="color: #ffd54f;">in</span>
                </strong></h2>
            <form method="POST" action="{{ route('postregister') }}">
                @csrf
                <div class="mb-3">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="role">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                <p class="mt-3 text-center">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
