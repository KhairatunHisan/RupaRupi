<?php 
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit; }

include "../config/db.php";
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RupaRupi — Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="padding-top: 70px;">

<?php include "../components/header.php"; ?>

<div class="container mt-4 mb-5">
    
    <!-- Header Welcome -->
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2" style="color: #1e3a5f;">Selamat Datang di RupaRupi</h2>
        <p class="text-muted fs-5">Kelola pakaian, buat outfit favorit, dan minta rekomendasi terbaik!</p>
    </div>

    <!-- Menu Cards -->
    <div class="row g-4 mb-5">

        <!-- Daftar Pakaian -->
        <div class="col-md-4">
            <a href="pakaian.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body text-center p-5">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 100px; height: 100px; background-color: #e8d5e8;">
                            <i class="bi bi-handbag-fill" style="color: #c084a7; font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1e3a5f;">Daftar Pakaian</h4>
                        <p class="text-muted mb-0 fs-6">Kelola koleksi pakaian Anda</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mix & Match Outfit -->
        <div class="col-md-4">
            <a href="outfit.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body text-center p-5">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 100px; height: 100px; background-color: #d4e3f0;">
                            <i class="bi bi-palette-fill" style="color: #1e3a5f; font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1e3a5f;">Mix & Match Outfit</h4>
                        <p class="text-muted mb-0 fs-6">Buat kombinasi outfit keren</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Rekomendasi -->
        <div class="col-md-4">
            <a href="rekomendasi.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body text-center p-5">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 100px; height: 100px; background-color: #d0dce8;">
                            <i class="bi bi-stars" style="color: #88a8c8; font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1e3a5f;">Rekomendasi</h4>
                        <p class="text-muted mb-0 fs-6">Minta saran dari admin</p>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <!-- ==================== OUTFIT BUATAN ADMIN ==================== -->
    <?php
    $admin_outfits = mysqli_query($conn,
        "SELECT r.admin_outfit_id, o.nama_outfit, o.pakaian_ids
         FROM rekomendasi r
         JOIN outfit o ON r.admin_outfit_id = o.id
         WHERE r.user_id=$user_id AND r.status='selesai'
         ORDER BY r.id DESC"
    );

    if (mysqli_num_rows($admin_outfits) > 0) {
    ?>
        <div class="mt-5">
            <!-- Section Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background-color: #e8d5e8;">
                        <i class="bi bi-award-fill fs-4" style="color: #c084a7;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0" style="color: #1e3a5f;">Rekomendasi dari Admin</h4>
                        <small class="text-muted">Outfit yang dibuat khusus untuk Anda</small>
                    </div>
                </div>
                <span class="badge rounded-pill px-3 py-2" style="background-color: #e8d5e8; color: #c084a7;">
                    <?= mysqli_num_rows($admin_outfits) ?> Outfit
                </span>
            </div>

            <!-- Outfit Cards -->
            <div class="row g-4">
                <?php while ($row = mysqli_fetch_assoc($admin_outfits)) { 
                    $ids = explode(",", $row["pakaian_ids"]);
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body p-4">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="fw-bold mb-0" style="color: #1e3a5f;">
                                        <?= $row['nama_outfit'] ?>
                                    </h5>
                                    <span class="badge rounded-pill text-white" style="background-color: #88a8c8;">
                                        <i class="bi bi-award-fill me-1"></i>Admin
                                    </span>
                                </div>

                                <!-- Pakaian Images -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <?php foreach ($ids as $id) { 
                                        $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pakaian WHERE id=$id AND user_id=$user_id"));
                                        if ($p) {
                                    ?>
                                        <img src="../uploads/pakaian/<?= $p['gambar'] ?>" 
                                             class="rounded shadow-sm" 
                                             style="width: 90px; height: 90px; object-fit: cover;"
                                             title="<?= $p['nama'] ?>">
                                    <?php 
                                        }
                                    } 
                                    ?>
                                </div>

                                <!-- Action Button -->
                                <div class="d-grid">
                                    <a href="lihat_outfit.php?id=<?= $row['admin_outfit_id'] ?>" 
                                       class="btn btn-outline-primary">
                                        <i class="bi bi-eye-fill me-2"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

</div>

<?php include "../components/footer.php"; ?>

<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>