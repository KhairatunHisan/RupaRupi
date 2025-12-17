<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RupaRupi - Kelola Pakaian & Outfit Anda</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #0a1929;">
            <div class="container">
                <a class="navbar-brand fw-bold fs-4" href="#">
                RupaRupi
                </a>
                <div class="ms-auto">
                    <a href="auth/login.php" class="btn btn-outline-light me-2">Login</a>
                    <a href="auth/register.php" class="btn btn-light">Register</a>
                </div>
            </div>
        </nav>

        <div class="container my-5 py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4" style="color: #1e3a5f;">
                        Selamat datang di <span style="color: #c084a7;">RupaRupi</span>
                    </h1>
                    <p class="lead mb-4 text-muted">
                        Kelola koleksi pakaian Anda dengan mudah, buat kombinasi outfit yang sempurna, dan dapatkan rekomendasi personal dari admin kami.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="auth/login.php" class="btn btn-lg px-4 py-3 text-white shadow" style="background-color: #1e3a5f;">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login Sekarang
                        </a>
                        <a href="auth/register.php" class="btn btn-lg px-4 py-3 text-white shadow" style="background-color: #c084a7;">
                            <i class="bi bi-person-plus me-2"></i>Daftar Gratis
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 position-relative">
                    <div class="rounded-4 shadow-lg overflow-hidden position-relative">
                        <img src="assets/oufit.png"
                            class="img-fluid w-100"
                            style="height:420px; object-fit:contain;">
                        <div class="position-absolute top-0 start-0 w-100 h-100"
                            style="background: linear-gradient(
                                rgba(30,58,95,0.25),
                                rgba(192,132,167,0.25)
                            );">
                        </div>
                    </div>
                </div>

            </div>
        </div>

 
        <div class="py-5" style="background-color: #f8f9fa;">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold" style="color: #1e3a5f;">Fitur Unggulan</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px; background-color: #e8d5e8;">
                                    <i class="bi bi-handbag fs-1" style="color: #c084a7;"></i>
                                </div>
                                <h5 class="card-title fw-bold mb-3">Kelola Pakaian</h5>
                                <p class="card-text text-muted">Simpan dan atur semua koleksi pakaian Anda dalam satu tempat yang terorganisir.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px; background-color: #d4e3f0;">
                                    <i class="bi bi-shuffle fs-1" style="color: #1e3a5f;"></i>
                                </div>
                                <h5 class="card-title fw-bold mb-3">Buat Outfit</h5>
                                <p class="card-text text-muted">Mix and match pakaian Anda untuk menciptakan kombinasi outfit yang stylish.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px; background-color: #d0dce8;">
                                    <i class="bi bi-stars fs-1" style="color: #88a8c8;"></i>
                                </div>
                                <h5 class="card-title fw-bold mb-3">Rekomendasi Admin</h5>
                                <p class="card-text text-muted">Dapatkan saran dan rekomendasi outfit dari admin berpengalaman kami.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

 
        <div class="py-5 my-5">
            <div class="container">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5 text-center" style="background: linear-gradient(135deg, #1e3a5f 0%, #c084a7 100%);">
                        <h2 class="text-white fw-bold mb-3">Siap Memulai?</h2>
                        <p class="text-white-50 mb-4 fs-5">Bergabunglah dengan RupaRupi dan kelola gaya fashion Anda hari ini!</p>
                        <a href="auth/register.php" class="btn btn-light btn-lg px-5 py-3 shadow">
                            <i class="bi bi-rocket-takeoff me-2"></i>Mulai Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <footer class="py-4 mt-5" style="background-color: #0a1929;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="text-white-50 mb-0">© 2024 RupaRupi. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <a href="#" class="text-white-50 text-decoration-none me-3">Privacy</a>
                        <a href="#" class="text-white-50 text-decoration-none me-3">Terms</a>
                        <a href="#" class="text-white-50 text-decoration-none">Contact</a>
                    </div>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}

// Jika sudah login -> redirect sesuai role (TANPA mengirim HTML lebih dulu)
if ($_SESSION['role'] === "admin") {
    header("Location: admin/dashboard.php");
    exit();
}

if ($_SESSION['role'] === "user") {
    header("Location: user/dashboard.php");
    exit();
}

// Jika role tidak dikenali → fallback
header("Location: auth/login.php");
exit();
?>