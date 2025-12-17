<?php
session_start();
include "../config/db.php";

$title = "Detail Outfit";
include "../components/header.php";

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Pastikan ID outfit ada
if (!isset($_GET['id'])) {
    header("Location: outfit.php");
    exit;
}

$outfit_id = $_GET['id'];
$user_id   = $_SESSION['user_id'];

// Ambil data outfit
$query = "
    SELECT *
    FROM outfit
    WHERE id = '$outfit_id' AND user_id = '$user_id'
";

$res = mysqli_query($conn, $query);
$outfit = mysqli_fetch_assoc($res);

// Jika tidak ditemukan
if (!$outfit) {
    echo "<div class='container mt-4'>";
    echo "<div class='alert alert-danger border-0 shadow-sm'><i class='bi bi-exclamation-triangle-fill me-2'></i>Outfit tidak ditemukan!</div>";
    echo "</div>";
    include "../components/footer.php";
    exit;
}

// Ambil pakaian berdasarkan pakaian_ids
$pakaian_ids = $outfit['pakaian_ids'];

$pakaian = [];
if (!empty($pakaian_ids)) {
    $sql_pakaian = "
        SELECT *
        FROM pakaian
        WHERE id IN ($pakaian_ids)
    ";
    $result_pakaian = mysqli_query($conn, $sql_pakaian);

    while ($row = mysqli_fetch_assoc($result_pakaian)) {
        $pakaian[] = $row;
    }
}

?>

<div class="container mt-4 mb-5">
    
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="outfit.php" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <i class="bi bi-eye-fill me-2"></i><?= $outfit['nama_outfit']; ?>
            </h3>
            <p class="text-muted mb-0">Detail kombinasi outfit Anda</p>
        </div>
    </div>

    <!-- Outfit Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            
            <!-- Badge Status -->
            <div class="text-center mb-4">
                <?php if ($outfit['dibuat_oleh'] == 'admin') { ?>
                    <span class="badge rounded-pill px-4 py-2 fs-6 text-white" style="background-color: #88a8c8;">
                        <i class="bi bi-award-fill me-2"></i>Outfit Rekomendasi dari Admin
                    </span>
                <?php } else { ?>
                    <span class="badge rounded-pill px-4 py-2 fs-6 text-white" style="background-color: #c084a7;">
                        <i class="bi bi-person-fill me-2"></i>Outfit Buatan Anda
                    </span>
                <?php } ?>
            </div>

            <!-- Empty State -->
            <?php if (count($pakaian) === 0) { ?>
                <div class="text-center py-5">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 70px; height: 70px; background-color: #e8d5e8;">
                        <i class="bi bi-inbox fs-2" style="color: #c084a7;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Tidak Ada Pakaian</h5>
                    <p class="text-muted">Tidak ada pakaian untuk outfit ini</p>
                </div>
            <?php } else { ?>
                
                <!-- Pakaian Grid -->
                <div class="row g-4">
                    <?php foreach ($pakaian as $p) { ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-2 h-100 hover-item">
                                <div class="card-body p-0">
                                    <img src="../uploads/pakaian/<?= $p['gambar']; ?>" 
                                         class="card-img-top" 
                                         style="height: 280px; object-fit: contain;">
                                    
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="fw-bold mb-0" style="color: #1e3a5f;">
                                                <?= $p['nama']; ?>
                                            </h5>
                                            <span class="badge rounded-pill" style="background-color: #e8d5e8; color: #c084a7;">
                                                <?= ucfirst($p['kategori']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            <?php } ?>

        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="edit_outfit.php?id=<?= $outfit_id; ?>" 
                       class="btn btn-lg btn-outline-warning w-100">
                        <i class="bi bi-pencil-fill me-2"></i>Edit Outfit
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="hapus_outfit.php?id=<?= $outfit_id; ?>"
                       class="btn btn-lg btn-outline-danger w-100"
                       onclick="return confirm('Hapus outfit ini?')">
                        <i class="bi bi-trash-fill me-2"></i>Hapus Outfit
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="outfit.php" class="btn btn-lg btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.hover-item {
    transition: all 0.3s ease;
}
.hover-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(192, 132, 167, 0.2);
}
</style>

<?php include "../components/footer.php"; ?>