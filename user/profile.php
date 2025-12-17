<?php
session_start();
$title = "Profil Saya";
include "../components/header.php";
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Hitung statistik user
$total_pakaian = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pakaian WHERE user_id='$user_id'"));
$total_outfit = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM outfit WHERE user_id='$user_id'"));
$total_rekomendasi = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE user_id='$user_id'"));
?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="text-center mb-4">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
             style="width: 100px; height: 100px; background: linear-gradient(135deg, #c084a7 0%, #88a8c8 100%);">
            <i class="bi bi-person-fill fs-1 text-white"></i>
        </div>
        <h3 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $user['username']; ?></h3>
        <p class="text-muted mb-0">
            <i class="bi bi-envelope-fill me-1"></i><?= $user['email']; ?>
        </p>
    </div>

    <div class="row g-4">

        <!-- Profile Info Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    
                    <h5 class="fw-bold mb-4" style="color: #1e3a5f;">
                        <i class="bi bi-person-badge-fill me-2"></i>Informasi Profil
                    </h5>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">Username</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <i class="bi bi-person-fill me-3 fs-5" style="color: #c084a7;"></i>
                            <span class="fw-semibold"><?= $user['username']; ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">Email</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <i class="bi bi-envelope-fill me-3 fs-5" style="color: #88a8c8;"></i>
                            <span class="fw-semibold"><?= $user['email']; ?></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small mb-1">Role</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <i class="bi bi-shield-fill-check me-3 fs-5" style="color: #1e3a5f;"></i>
                            <span class="badge rounded-pill px-3 py-2 text-white" 
                                  style="background-color: <?= $user['role'] == 'admin' ? '#88a8c8' : '#c084a7' ?>;">
                                <?= ucfirst($user['role']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="profile_edit.php" class="btn btn-lg text-white" style="background-color: #1e3a5f;">
                            <i class="bi bi-pencil-fill me-2"></i>Edit Profil
                        </a>
                        <a href="../auth/logout.php" class="btn btn-lg btn-outline-danger"
                           onclick="return confirm('Yakin ingin logout?');">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-lg-8">
            
            <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
                <i class="bi bi-graph-up-arrow me-2"></i>Statistik Saya
            </h5>

            <div class="row g-3 mb-4">
                
                <!-- Total Pakaian -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm hover-stat">
                        <div class="card-body p-4 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px; background-color: #e8d5e8;">
                                <i class="bi bi-handbag-fill fs-3" style="color: #c084a7;"></i>
                            </div>
                            <h2 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $total_pakaian ?></h2>
                            <p class="text-muted mb-0">Total Pakaian</p>
                        </div>
                    </div>
                </div>

                <!-- Total Outfit -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm hover-stat">
                        <div class="card-body p-4 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px; background-color: #d4e3f0;">
                                <i class="bi bi-palette-fill fs-3" style="color: #1e3a5f;"></i>
                            </div>
                            <h2 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $total_outfit ?></h2>
                            <p class="text-muted mb-0">Total Outfit</p>
                        </div>
                    </div>
                </div>

                <!-- Total Rekomendasi -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm hover-stat">
                        <div class="card-body p-4 text-center">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px; background-color: #d0dce8;">
                                <i class="bi bi-stars fs-3" style="color: #88a8c8;"></i>
                            </div>
                            <h2 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $total_rekomendasi ?></h2>
                            <p class="text-muted mb-0">Rekomendasi</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Actions -->
            <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
                <i class="bi bi-lightning-fill me-2"></i>Aksi Cepat
            </h5>

            <div class="row g-3">
                
                <div class="col-md-6">
                    <a href="pakaian.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-action">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; background-color: #e8d5e8;">
                                    <i class="bi bi-handbag-fill fs-5" style="color: #c084a7;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Kelola Pakaian</h6>
                                    <small class="text-muted">Upload & atur pakaian</small>
                                </div>
                                <i class="bi bi-chevron-right ms-auto fs-5 text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="outfit.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-action">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; background-color: #d4e3f0;">
                                    <i class="bi bi-palette-fill fs-5" style="color: #1e3a5f;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Buat Outfit</h6>
                                    <small class="text-muted">Mix & match style</small>
                                </div>
                                <i class="bi bi-chevron-right ms-auto fs-5 text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="rekomendasi.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-action">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; background-color: #d0dce8;">
                                    <i class="bi bi-stars fs-5" style="color: #88a8c8;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Rekomendasi</h6>
                                    <small class="text-muted">Minta saran admin</small>
                                </div>
                                <i class="bi bi-chevron-right ms-auto fs-5 text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="dashboard.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-action">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; background-color: #e8d5e8;">
                                    <i class="bi bi-house-door-fill fs-5" style="color: #c084a7;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Dashboard</h6>
                                    <small class="text-muted">Kembali ke beranda</small>
                                </div>
                                <i class="bi bi-chevron-right ms-auto fs-5 text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>

<style>
.hover-stat {
    transition: all 0.3s ease;
}
.hover-stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(30, 58, 95, 0.15) !important;
}
.hover-action {
    transition: all 0.3s ease;
}
.hover-action:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(192, 132, 167, 0.2) !important;
}
</style>

<?php include "../components/footer.php"; ?>