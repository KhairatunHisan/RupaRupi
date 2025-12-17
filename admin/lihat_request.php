<?php
session_start();
include "../config/db.php";
include "../components/header_admin.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];

// Cek apakah ada filter status dari URL
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

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <i class="bi bi-inbox-fill me-2"></i><?= $page_title ?>
            </h3>
            <p class="text-muted mb-0"><?= $page_subtitle ?></p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Dashboard
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="d-flex gap-2 flex-wrap">
                <a href="lihat_request.php" 
                   class="btn <?= $status_filter == 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">
                    <i class="bi bi-inbox-fill me-2"></i>Semua
                </a>
                <a href="lihat_request.php?status=pending" 
                   class="btn <?= $status_filter == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' ?>">
                    <i class="bi bi-clock-fill me-2"></i>Pending
                </a>
                <a href="lihat_request.php?status=selesai" 
                   class="btn <?= $status_filter == 'selesai' ? 'btn-success' : 'btn-outline-success' ?>">
                    <i class="bi bi-check-circle-fill me-2"></i>Selesai
                </a>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <?php if (mysqli_num_rows($data) == 0) { ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <?php if ($status_filter == 'pending') { ?>
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background-color: #d1e7dd;">
                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Semua Request Pending Selesai!</h5>
                    <p class="text-muted mb-3">Tidak ada request pending yang perlu diproses</p>
                <?php } elseif ($status_filter == 'selesai') { ?>
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background-color: #e8d5e8;">
                        <i class="bi bi-inbox fs-1" style="color: #c084a7;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Belum Ada Request Selesai</h5>
                    <p class="text-muted mb-3">Belum ada request yang diselesaikan</p>
                <?php } else { ?>
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background-color: #e8d5e8;">
                        <i class="bi bi-inbox fs-1" style="color: #c084a7;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Tidak Ada Request</h5>
                    <p class="text-muted mb-3">Belum ada request untuk Anda</p>
                <?php } ?>
                <a href="dashboard.php" class="btn text-white" style="background-color: #1e3a5f;">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    <?php } else { ?>

        <!-- Request Cards -->
        <div class="row g-4">

            <?php while ($r = mysqli_fetch_assoc($data)) { 
                // Get admin/desainer info
                $admin = mysqli_fetch_assoc(
                    mysqli_query($conn, "SELECT username FROM users WHERE id=".$r['desainer'])
                );
            ?>
                
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm hover-request h-100">
                        <div class="card-body p-4">
                            
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #1e3a5f;">
                                        <i class="bi bi-person-circle me-2"></i><?= $r['username'] ?>
                                    </h5>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope me-1"></i><?= $r['email'] ?>
                                    </small>
                                </div>
                                <?php if ($r['status'] == 'pending') { ?>
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>Pending
                                    </span>
                                <?php } else { ?>
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                <?php } ?>
                            </div>

                            <!-- Request Details -->
                            <div class="mb-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded">
                                            <small class="text-muted d-block mb-1">Kategori</small>
                                            <span class="badge rounded-pill px-3 py-2" 
                                                  style="background-color: #e8d5e8; color: #c084a7;">
                                                <?= $r['kategori'] ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded">
                                            <small class="text-muted d-block mb-1">Request ID</small>
                                            <strong style="color: #1e3a5f;">#<?= $r['id'] ?></strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-award me-1"></i>Desainer yang Dipilih
                                    </small>
                                    <strong style="color: #1e3a5f;"><?= $admin['username'] ?></strong>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="d-grid">
                                <?php if ($r['status'] == 'pending') { ?>
                                    <a href="mix_outfit_admin.php?id=<?= $r['id'] ?>&user=<?= $r['user_id'] ?>" 
                                    class="btn btn-lg text-white shadow-sm" style="background-color: #1e3a5f;">
                                        <i class="bi bi-palette-fill me-2"></i>Buat Outfit Sekarang
                                    </a>
                                <?php } ?>
                            </div>


                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>

        <!-- Summary Card -->
        <div class="card border-0 shadow-sm mt-4" style="background-color: #f8f9fa;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="fw-bold mb-2" style="color: #1e3a5f;">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <?php if ($status_filter == 'pending') { ?>
                                Total Request Pending
                            <?php } elseif ($status_filter == 'selesai') { ?>
                                Total Request Selesai
                            <?php } else { ?>
                                Total Semua Request
                            <?php } ?>
                        </h6>
                        <p class="text-muted mb-0 small">
                            Menampilkan <?= mysqli_num_rows($data) ?> request
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <?php if ($status_filter == 'pending') { ?>
                            <div class="badge bg-warning text-dark px-4 py-3 fs-5">
                                <?= mysqli_num_rows($data) ?> Pending
                            </div>
                        <?php } elseif ($status_filter == 'selesai') { ?>
                            <div class="badge bg-success px-4 py-3 fs-5">
                                <?= mysqli_num_rows($data) ?> Selesai
                            </div>
                        <?php } else { ?>
                            <div class="badge bg-primary px-4 py-3 fs-5">
                                <?= mysqli_num_rows($data) ?> Request
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

</div>

<style>
.hover-request {
    transition: all 0.3s ease;
}
.hover-request:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(30, 58, 95, 0.15) !important;
}
</style>

<?php include "../components/footer.php"; ?>    