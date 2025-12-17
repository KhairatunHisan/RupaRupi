<?php
include "../config/db.php";

if ($_POST) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn,
        "INSERT INTO users (email, username, password, role)
         VALUES ('$email','$username','$password','user')");

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RupaRupi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #0a1929;">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="../index.php">
                RupaRupi
            </a>
            <div class="ms-auto">
                <a href="login.php" class="btn btn-outline-light">Login</a>
            </div>
        </div>
    </nav>

    <!-- Register Section -->
    <div class="container my-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background-color: #e8d5e8;">
                        <i class="bi bi-person-plus-fill fs-1" style="color: #c084a7;"></i>
                    </div>
                    <h2 class="fw-bold" style="color: #1e3a5f;">Bergabung dengan RupaRupi</h2>
                    <p class="text-muted">Buat akun baru dan mulai kelola gaya fashion Anda</p>
                </div>

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                    <i class="bi bi-envelope-fill me-1"></i>Email
                                </label>
                                <input name="email" type="email" class="form-control form-control-lg border-2" 
                                       placeholder="nama@email.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                    <i class="bi bi-person-fill me-1"></i>Username
                                </label>
                                <input name="username" type="text" class="form-control form-control-lg border-2" 
                                       placeholder="Input username" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                    <i class="bi bi-lock-fill me-1"></i>Password
                                </label>
                                <input name="password" type="password" class="form-control form-control-lg border-2" 
                                       placeholder="Password" required>
                            </div>

                            <button type="submit" class="btn btn-lg w-100 text-white shadow-sm mb-3" 
                                    style="background-color: #c084a7;">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </button>
                        </form>

                        <div class="text-center pt-3 border-top">
                            <p class="text-muted mb-0">
                                Sudah punya akun? 
                                <a href="login.php" class="text-decoration-none fw-semibold" style="color: #1e3a5f;">
                                    Login di sini
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="../index.php" class="text-muted text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 mt-auto" style="background-color: #0a1929;">
        <div class="container">
            <div class="text-center">
                <p class="text-white-50 mb-0">© 2024 RupaRupi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>