<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Welcome</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('img/iconWeb.png') }}" rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<style>
    @keyframes floating {
        0% {
            transform: scale(1);
            /* Ukuran normal */
        }

        50% {
            transform: scale(1.1);
            /* Ukuran sedikit lebih besar */
        }

        100% {
            transform: scale(1);
            /* Kembali ke ukuran normal */
        }
    }

    .floating-image {
        animation: floating 3s ease-in-out infinite;
        /* Animasi berlangsung 3 detik dan berulang terus-menerus */
    }
</style>

<body>
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-light">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/iconWeb.png') }}" alt="Logo" style="width: 40px; margin-right: 10px;">
            <h3 class="mb-0">SISP3M</h3>
        </div>
        <nav class="d-flex align-items-center">
            <!-- Home Button -->
            <a href="{{ route('welcome') }}" class="btn btn-outline-primary me-3 px-4 py-2 fw-bold">Home</a>

            @guest
                <!-- Login Button -->
                <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2 fw-bold">Login</a>
            @endguest

            @auth
                <!-- Logged-in User Display -->
                <span class="me-3 px-4 py-2 bg-primary text-white rounded-3 fw-bold">
                    {{ Auth::user()->name }}
                </span>
                <!-- Logout Button -->
                <a href="{{ route('logout') }}" class="btn btn-danger px-4 py-2 fw-bold"
                    onclick="event.preventDefault(); document.getElementById('logout-link').click();">
                    Logout
                </a>
            @endauth
        </nav>

        <!-- Link untuk logout (disembunyikan menggunakan CSS atau diletakkan terpisah) -->
        <a href="{{ route('logout') }}" id="logout-link" style="display:none;"></a>

    </header>

    <!-- Hero Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <!-- Text Content -->
                <div class="col-md-6 text-center text-md-start">
                    <h1 class="display-5 fw-bold">Web Surat Pusat Penelitian dan Pengabdian Kepada Masyarakat</h1>
                    <p class="lead text-muted mt-3">
                        Web Pembuatan Surat Untuk Para Dosen Politeknik Negeri Cilacap, dengan Website ini, para dosen
                        diharapkan dipermudah untuk urusan pembuatan surat dan konfirmasi dari kantor P3M PNC.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-4">Mulai Membuat Surat</a>
                </div>
                <!-- Image Content -->
                <div class="col-md-6 text-center">
                    <img src="{{ asset('img/heroWeb.png') }}" class="img-fluid floating-image"
                        style="max-width: 625px;">
                </div>

            </div>
        </div>
    </section>



    <!-- Footer -->
    <footer class="text-center py-3 bg-light">
        <p class="mb-0">&copy; 2024 Web SISP3M. All rights reserved.</p>
    </footer>

    <!-- Vendor JS Files -->
    <script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
