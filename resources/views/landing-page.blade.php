<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Magang.in</title>
    <style>
        body {
            background-image: url('/images/bg.png');
            /* Ganti dengan path gambar kamu */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
        }

        header {
            position: sticky;
            top: 0;
            width: 100%;
            background-color: #FFF6DF; /* Transparan sedikit */
            padding: 10px 20px;
            z-index: 10; /* Agar header tetap di atas konten */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header img {
            height: 50px;
        }

        /* Konten utama untuk memeriksa efek scroll */
        .content {
            padding: 20px;
            height: 2000px; /* Cukup panjang agar bisa scroll */
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="d-flex justify-between align-items-center">
        <img src="{{url('/images/logo.png')}}" alt="Logo">
        <p>p</p>
    </header>

    <div class="content">
        <!-- Konten lainnya -->
        <h1>Selamat datang di Magang.in</h1>
        <p>Isi konten di sini...</p>
        <button class="btn btn-primary"></button>
    </div>
</body>

</html>
