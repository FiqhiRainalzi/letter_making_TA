<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login</title>
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

<body>
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-light">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/iconWeb.png') }}" alt="Logo" style="width: 40px; margin-right: 10px;">
            <h3 class="mb-0">SISP3M</h3>
        </div>
        <nav>
            <a href="{{ route('welcome') }}" class="btn btn-outline-primary me-3 px-4 py-2 fw-bold">Home</a>

        </nav>
    </header>
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="/welcome" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('img/iconWeb.png') }}" alt="">
                            <span class="d-none d-lg-block">SISP3M</span>
                        </a>
                    </div><!-- End Logo -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="pt-1 pb-2">
                                <h5 class="card-title text-center pb-0 fs-5">Login untuk masuk ke akunmu</h5>
                                <p class="text-center small">Masukan email dan password untuk login</p>
                                <!-- Display Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">@</span>
                                        <input value="{{ old('email') }}" type="text" name="email"
                                            class="form-control" required>
                                        <div class="invalid-feedback">Please enter your email.</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                    <div class="invalid-feedback">Please enter your password!</div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Login</button>
                                </div>
                                <div class="col-12">
                                    <p class="small mb-0">Belum mempunyai akun? <a
                                            href="{{ route('regisPage') }}">Register</a>
                                    </p>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>



    <!-- Footer -->
    <footer class="text-center py-3 bg-light">
        <p class="mb-0">&copy; 2024 Web SISP3M. All rights reserved.</p>
    </footer>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    position: 'top-end', // Mengatur posisi di pojok atas kanan
                    showConfirmButton: false, // Tidak menampilkan tombol OK
                    timer: 3000, // Notifikasi akan hilang otomatis dalam 3 detik
                    timerProgressBar: true, // Menampilkan progress bar saat hitung mundur
                    toast: true // Menyesuaikan tampilan menjadi kecil seperti toast
                });
            });
        </script>
    @endif

    <!-- Vendor JS Files -->
    <script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
