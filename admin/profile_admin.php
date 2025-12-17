<?php
session_start();
$title = "Profil Saya";
include "../config/db.php";
include "../components/header_admin.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Hitung statistik admin
$total_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE desainer = '$user_id'"));
$pending_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE status='pending' AND desainer = '$user_id'"));
$completed_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE status='selesai' AND desainer = '$user_id'"));
?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <i class="bi bi-person-badge-fill me-2"></i>Profil Admin
            </h3>
            <p class="text-muted mb-0">Informasi akun dan statistik Anda</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Dashboard
        </a>
    </div>

    <div class="row g-4">

        <!-- Profile Info Card -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    
                    <!-- Avatar -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 100px; height: 100px; background: linear-gradient(135deg, #1e3a5f 0%, #88a8c8 100%);">
                            <i class="bi bi-person-fill fs-1 text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $user['username']; ?></h5>
                        <p class="text-muted mb-0">
                            <i class="bi bi-envelope-fill me-1"></i><?= $user['email']; ?>
                        </p>
                    </div>

                    <!-- Info Fields -->
                    <h6 class="fw-bold mb-3" style="color: #1e3a5f;">
                        <i class="bi bi-info-circle-fill me-2"></i>Informasi Akun
                    </h6>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">Username</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <i class="bi bi-person-fill me-3 fs-5" style="color: #1e3a5f;"></i>
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
                            <i class="bi bi-shield-fill-check me-3 fs-5" style="color: #c084a7;"></i>
                            <span class="badge rounded-pill px-3 py-2 text-white" style="background-color: #88a8c8;">
                                <i class="bi bi-award-fill me-1"></i><?= ucfirst($user['role']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="../admin/edit_profile_admin.php" class="btn btn-lg text-white" style="background-color: #1e3a5f;">
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

        <!-- Statistics & Quick Actions -->
        <div class="col-lg-7">
            
            <!-- Statistics -->
            <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
                <i class="bi bi-graph-up-arrow me-2"></i>Statistik Saya
            </h5>

            <div class="row g-3 mb-4">
                
                <!-- Total Requests -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm hover-stat" style="background-color: #d4e3f0;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-clipboard-fill fs-2 mb-2 d-block" style="color: #1e3a5f;"></i>
                            <h3 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $total_requests ?></h3>
                            <small class="text-muted">Total Request</small>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm hover-stat" style="background-color: #fff3cd;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-clock-fill fs-2 mb-2 d-block text-warning"></i>
                            <h3 class="fw-bold mb-1 text-warning"><?= $pending_requests ?></h3>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>

                <!-- Completed Requests -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm hover-stat" style="background-color: #d1e7dd;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-check-circle-fill fs-2 mb-2 d-block text-success"></i>
                            <h3 class="fw-bold mb-1 text-success"><?= $completed_requests ?></h3>
                            <small class="text-muted">Selesai</small>
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
                    <a href="lihat_request.php" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-action">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; background-color: #d4e3f0;">
                                    <i class="bi bi-inbox-fill fs-5" style="color: #1e3a5f;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Lihat Request</h6>
                                    <small class="text-muted">Kelola permintaan</small>
                                </div>
                                <i class="bi bi-chevron-right ms-auto fs-5 text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="lihat_request.php?status=pending" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-action">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; background-color: #fff3cd;">
                                    <i class="bi bi-hourglass-split fs-5 text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Request Pending</h6>
                                    <small class="text-muted">Butuh perhatian</small>
                                </div>
                                <i class="bi bi-chevron-right ms-auto fs-5 text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="lihat_request.php?status=selesai" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-action">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px; background-color: #d1e7dd;">
                                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Request Selesai</h6>
                                    <small class="text-muted">Telah ditangani</small>
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