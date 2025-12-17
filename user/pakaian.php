<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua kategori milik user
$kategori_query = mysqli_query($conn,
    "SELECT DISTINCT kategori FROM pakaian WHERE user_id='$user_id' ORDER BY kategori"
);

// Jika kategori dipilih
$kategori_dipilih = isset($_GET['kategori']) ? $_GET['kategori'] : null;

if ($kategori_dipilih) {
    $pakaian = mysqli_query($conn,
        "SELECT * FROM pakaian WHERE user_id='$user_id' AND kategori='$kategori_dipilih'"
    );
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pakaian — RupaRupi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="padding-top: 70px;">

<?php include "../components/header.php"; ?>

<div class="container mt-4 mb-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <?php if (!$kategori_dipilih) { ?>
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>Kategori Pakaian
                <?php } else { ?>
                    <i class="bi bi-handbag-fill me-2"></i>Pakaian: <span style="color: #c084a7;"><?= ucfirst($kategori_dipilih) ?></span>
                <?php } ?>
            </h3>
            <p class="text-muted mb-0">
                <?php if (!$kategori_dipilih) { ?>
                    Pilih kategori untuk melihat koleksi pakaian Anda
                <?php } else { ?>
                    Kelola pakaian dalam kategori ini
                <?php } ?>
            </p>
        </div>

        <a href="upload_pakaian.php" class="btn btn-lg text-white shadow-sm" style="background-color: #1e3a5f;">
            <i class="bi bi-plus-circle me-2"></i>Upload Pakaian
        </a>
    </div>

    <!-- ======================= TAMPILKAN KATEGORI ======================= -->
    <?php if (!$kategori_dipilih) { ?>
    
        <?php if (mysqli_num_rows($kategori_query) == 0) { ?>
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background-color: #e8d5e8;">
                        <i class="bi bi-inbox fs-1" style="color: #c084a7;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Belum Ada Pakaian</h5>
                    <p class="text-muted mb-3">Mulai upload pakaian pertama Anda untuk membuat kategori</p>
                    <a href="upload_pakaian.php" class="btn btn-lg text-white" style="background-color: #c084a7;">
                        <i class="bi bi-plus-circle me-2"></i>Upload Sekarang
                    </a>
                </div>
            </div>
        <?php } else { ?>
            <div class="row g-4">
                <?php while ($row = mysqli_fetch_assoc($kategori_query)) { ?>
                    <div class="col-md-4 col-lg-3">
                        <a href="?kategori=<?= $row['kategori'] ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100 hover-kategori">
                                <div class="card-body text-center p-4">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 70px; height: 70px; background-color: #e8d5e8;">
                                        <i class="bi bi-box-seam-fill fs-2" style="color: #c084a7;"></i>
                                    </div>
                                    <h5 class="fw-bold text-capitalize mb-1" style="color: #1e3a5f;">
                                        <?= $row['kategori'] ?>
                                    </h5>
                                    <small class="text-muted">Lihat koleksi</small>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

    <?php } ?>


    <!-- ======================= TAMPILKAN PAKAIAN SESUAI KATEGORI ======================= -->
    <?php if ($kategori_dipilih) { ?>

        <a href="pakaian.php" class="btn btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Kategori
        </a>

        <?php if (mysqli_num_rows($pakaian) == 0) { ?>
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; background-color: #d4e3f0;">
                        <i class="bi bi-inbox fs-1" style="color: #1e3a5f;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Tidak Ada Pakaian</h5>
                    <p class="text-muted">Belum ada pakaian dalam kategori ini</p>
                </div>
            </div>
        <?php } else { ?>
            <div class="row g-4">
                <?php while ($row = mysqli_fetch_assoc($pakaian)) { ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 hover-pakaian">
                            <img src="../uploads/pakaian/<?= $row['gambar'] ?>" 
                                 class="card-img-top" 
                                 style="height: 230px; object-fit: contain;">

                            <div class="card-body">
                                <h5 class="fw-bold mb-1" style="color: #1e3a5f;"><?= $row['nama'] ?></h5>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-tag-fill me-1"></i><?= ucfirst($row['kategori']) ?>
                                </p>

                                <div class="d-flex gap-2">
                                    <a href="edit_pakaian.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                    </a>
                                    <a href="delete_pakaian.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger flex-fill"
                                       onclick="return confirm('Hapus pakaian ini?');">
                                       <i class="bi bi-trash-fill me-1"></i>Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

    <?php } ?>

</div>

<style>
.hover-kategori {
    transition: all 0.3s ease;
}
.hover-kategori:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(192, 132, 167, 0.2) !important;
}
.hover-pakaian {
    transition: all 0.3s ease;
}
.hover-pakaian:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(30, 58, 95, 0.15) !important;
}
</style>

<?php include "../components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>