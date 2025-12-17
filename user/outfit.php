<?php
session_start();
include "../config/db.php";

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$outfit = mysqli_query($conn, "SELECT * FROM outfit WHERE user_id='$user_id'");

// Ambil data outfit + pakaian yang terkait
$query = "
    SELECT o.*, 
       GROUP_CONCAT(p.nama ORDER BY p.id SEPARATOR '||') AS nama_pakaian,
       GROUP_CONCAT(p.gambar ORDER BY p.id SEPARATOR '||') AS gambar_pakaian
    FROM outfit o
    LEFT JOIN pakaian p ON FIND_IN_SET(p.id, o.pakaian_ids)
    WHERE o.user_id = '$user_id'
    GROUP BY o.id
    ORDER BY o.id DESC

";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outfit Saya — RupaRupi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="padding-top: 70px;">

<?php include "../components/header.php"; ?>

<div class="container mt-4 mb-5">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <i class="bi bi-palette-fill me-2"></i>Outfit Saya
            </h3>
            <p class="text-muted mb-0">Koleksi mix & match pakaian Anda</p>
        </div>

        <a href="tambah_outfit.php" class="btn btn-lg text-white shadow-sm" style="background-color: #c084a7;">
            <i class="bi bi-plus-circle me-2"></i>Tambah Outfit
        </a>
    </div>

    <!-- Empty State -->
    <?php if (mysqli_num_rows($outfit) === 0) { ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px; background-color: #e8d5e8;">
                    <i class="bi bi-palette fs-1" style="color: #c084a7;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Belum Ada Outfit</h5>
                <p class="text-muted mb-3">Mulai buat kombinasi outfit pertama Anda</p>
                <a href="tambah_outfit.php" class="btn btn-lg text-white" style="background-color: #c084a7;">
                    <i class="bi bi-plus-circle me-2"></i>Buat Outfit Sekarang
                </a>
            </div>
        </div>
    <?php } ?>

    <!-- Outfit Grid -->
    <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 hover-outfit">
                    <div class="card-body p-4">

                        <!-- Header dengan Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="fw-bold mb-0" style="color: #1e3a5f;">
                                <?= $row['nama_outfit']; ?>
                            </h5>
                            <?php if ($row['dibuat_oleh'] == 'admin') { ?>
                                <span class="badge rounded-pill text-white" style="background-color: #88a8c8;">
                                    <i class="bi bi-award-fill me-1"></i>Admin
                                </span>
                            <?php } else { ?>
                                <span class="badge rounded-pill text-white" style="background-color: #c084a7;">
                                    <i class="bi bi-person-fill me-1"></i>Saya
                                </span>
                            <?php } ?>
                        </div>

                        <!-- Grid Gambar Pakaian -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php 
                            $gambarList = explode("||", $row['gambar_pakaian']);
                            $namaList   = explode("||", $row['nama_pakaian']);


                            for ($i = 0; $i < count($gambarList); $i++) {
                                if (!empty($gambarList[$i])) {

                                    $nama = $namaList[$i] ?? 'Pakaian';
                            ?>
                                <div class="text-center">
                                    <img src="../uploads/pakaian/<?= htmlspecialchars($gambarList[$i]); ?>" 
                                        class="rounded shadow-sm" 
                                        style="width: 90px; height: 90px; object-fit: contain;"
                                        alt="<?= htmlspecialchars($nama); ?>">

                                    <small class="d-block text-muted mt-1"
                                        style="font-size: 0.7rem; max-width: 90px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= htmlspecialchars($nama); ?>
                                    </small>
                                </div>
                            <?php
                                }
                            }
                            ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <a href="lihat_outfit.php?id=<?= $row['id']; ?>"
                               class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="bi bi-eye-fill me-1"></i>Lihat
                            </a>

                            <a href="edit_outfit.php?id=<?= $row['id']; ?>"
                               class="btn btn-sm btn-outline-warning flex-fill">
                                <i class="bi bi-pencil-fill me-1"></i>Edit
                            </a>

                            <a href="hapus_outfit.php?id=<?= $row['id']; ?>"
                               class="btn btn-sm btn-outline-danger flex-fill"
                               onclick="return confirm('Yakin ingin menghapus outfit ini?')">
                                <i class="bi bi-trash-fill me-1"></i>Hapus
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<style>
.hover-outfit {
    transition: all 0.3s ease;
}
.hover-outfit:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(30, 58, 95, 0.15) !important;
}
</style>

<?php include "../components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>