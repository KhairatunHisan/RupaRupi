<?php
session_start();

$admin_id = $_SESSION['user_id'];

include "../components/header_admin.php";
include "../config/db.php";

// Hitung statistik admin
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user'"));
$total_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE desainer = '$admin_id'"));
$pending_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE status='pending' AND desainer = '$admin_id'"));
$completed_requests = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE status='selesai' AND desainer = '$admin_id'"));


$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build query berdasarkan status
if ($status_filter == 'pending') {
    $query = "
        SELECT r.*, u.username, u.email
        FROM rekomendasi r 
        JOIN users u ON r.user_id = u.id
        WHERE r.status = 'pending'
        AND r.desainer = $admin_id
        ORDER BY r.id DESC
    ";
    $page_title = "Request Pending";
    $page_subtitle = "Request yang perlu diproses";
} elseif ($status_filter == 'selesai') {
    $query = "
        SELECT r.*, u.username, u.email
        FROM rekomendasi r 
        JOIN users u ON r.user_id = u.id
        WHERE r.status = 'selesai'
        AND r.desainer = $admin_id
        ORDER BY r.id DESC
    ";
    $page_title = "Request Selesai";
    $page_subtitle = "Riwayat request yang telah diselesaikan";
} else {
    $query = "
        SELECT r.*, u.username, u.email
        FROM rekomendasi r 
        JOIN users u ON r.user_id = u.id
        WHERE r.desainer = $admin_id
        ORDER BY r.id DESC
    ";
    $page_title = "Semua Request";
    $page_subtitle = "Semua permintaan rekomendasi";
}

$data = mysqli_query($conn, $query);
?>


<div class="container mt-4 mb-5">

    <!-- Welcome Header -->
    <div class="text-center mb-5">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
             style="width: 100px; height: 100px; background: linear-gradient(135deg, #1e3a5f 0%, #88a8c8 100%);">
            <i class="bi bi-award-fill fs-1 text-white"></i>
        </div>
        <h2 class="fw-bold mb-2" style="color: #1e3a5f;">Dashboard Admin</h2>
        <p class="text-muted mb-0">Kelola permintaan rekomendasi dari user</p>
    </div>

    <!-- Main Actions -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            
            <h5 class="fw-bold mb-4" style="color: #1e3a5f;">
                <i class="bi bi-kanban-fill me-2"></i>Kelola Request
            </h5>

            <div class="row g-3">
                
                <div class="col-md-4">
                    <a href="lihat_request.php" class="text-decoration-none">
                        <div class="card border-2 hover-action h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px; background-color: #d4e3f0;">
                                        <i class="bi bi-inbox-fill fs-4" style="color: #1e3a5f;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Semua Request</h6>
                                        <small class="text-muted">Lihat semua permintaan</small>
                                    </div>
                                </div>
                                <?php if ($total_requests > 0) { ?>
                                    <div class="badge bg-primary rounded-pill px-3 py-2">
                                        <?= $total_requests ?> Request
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="lihat_request.php?status=pending" class="text-decoration-none">
                        <div class="card border-2 hover-action h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px; background-color: #fff3cd;">
                                        <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Request Pending</h6>
                                        <small class="text-muted">Butuh perhatian</small>
                                    </div>
                                </div>
                                <?php if ($pending_requests > 0) { ?>
                                    <div class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                        <?= $pending_requests ?> Pending
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="lihat_request.php?status=selesai" class="text-decoration-none">
                        <div class="card border-2 hover-action h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px; background-color: #d1e7dd;">
                                        <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="color: #1e3a5f;">Request Selesai</h6>
                                        <small class="text-muted">Telah ditangani</small>
                                    </div>
                                </div>
                                <?php if ($completed_requests > 0) { ?>
                                    <div class="badge bg-success rounded-pill px-3 py-2">
                                        <?= $completed_requests ?> Selesai
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4" style="color: #1e3a5f;">
                <i class="bi bi-graph-up-arrow me-2"></i>Statistik
            </h5>

            <div class="row g-4">
                
                <!-- Total Users -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 hover-stat" style="background-color: #e8d5e8;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-people-fill fs-1 mb-3 d-block" style="color: #c084a7;"></i>
                            <h2 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $total_users ?></h2>
                            <p class="text-muted mb-0">Total User</p>
                        </div>
                    </div>
                </div>

                <!-- Total Requests -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 hover-stat" style="background-color: #d4e3f0;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-clipboard-fill fs-1 mb-3 d-block" style="color: #1e3a5f;"></i>
                            <h2 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $total_requests ?></h2>
                            <p class="text-muted mb-0">Total Request</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 hover-stat" style="background-color: #fff3cd;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-clock-fill fs-1 mb-3 d-block text-warning"></i>
                            <h2 class="fw-bold mb-1 text-warning"><?= $pending_requests ?></h2>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>

                <!-- Completed Requests -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 hover-stat" style="background-color: #d1e7dd;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-check-circle-fill fs-1 mb-3 d-block text-success"></i>
                            <h2 class="fw-bold mb-1 text-success"><?= $completed_requests ?></h2>
                            <p class="text-muted mb-0">Selesai</p>
                        </div>
                    </div>
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
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(192, 132, 167, 0.2);
    border-color: #c084a7 !important;
}
</style>

<?php include "../components/footer.php"; ?>