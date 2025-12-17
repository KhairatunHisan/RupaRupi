<?php
session_start();
include "../config/db.php";

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil kategori unik terlebih dahulu
$kategori_query = mysqli_query($conn,
    "SELECT DISTINCT kategori FROM pakaian WHERE user_id='$user_id' ORDER BY kategori"
);

// Proses simpan outfit
if (isset($_POST['submit'])) {
    $nama_outfit = $_POST['nama_outfit'];
    $pakaian_ids = implode(",", $_POST['pakaian'] ?? []);

    if ($pakaian_ids == "") {
        $error = "Pilih minimal 1 pakaian!";
    } else {
        $insert = mysqli_query($conn, "
            INSERT INTO outfit (user_id, nama_outfit, pakaian_ids, dibuat_oleh)
            VALUES ('$user_id', '$nama_outfit', '$pakaian_ids', 'user')
        ");

        if ($insert) {
            header("Location: outfit.php");
            exit;
        } else {
            $error = "Gagal menyimpan outfit!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mix & Match — RupaRupi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="padding-top: 70px;">

<?php include "../components/header.php"; ?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="outfit.php" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <i class="bi bi-palette-fill me-2"></i>Buat Outfit Baru
            </h3>
            <p class="text-muted mb-0">Pilih pakaian untuk membuat kombinasi outfit</p>
        </div>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div><?= $error; ?></div>
        </div>
    <?php } ?>

    <form action="" method="POST">

        <!-- Nama Outfit Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <label class="form-label fw-semibold" style="color: #1e3a5f;">
                    <i class="bi bi-tag-fill me-1"></i>Nama Outfit
                </label>
                <input type="text" class="form-control form-control-lg border-2" 
                       name="nama_outfit" placeholder="Contoh: Casual Weekend" required>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>Beri nama yang mudah diingat untuk outfit Anda
                </small>
            </div>
        </div>

        <!-- Pilih Pakaian Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
                    <i class="bi bi-box-seam me-2"></i>Pilih Pakaian
                </h5>
                <p class="text-muted mb-4">Klik pada pakaian untuk memilih</p>

                <?php
                $kategori_count = 0;
                while ($k = mysqli_fetch_assoc($kategori_query)) {
                    $kategori = $k['kategori'];
                    $kategori_count++;

                    // Ambil pakaian dalam kategori ini
                    $pakaian = mysqli_query($conn,
                        "SELECT * FROM pakaian WHERE user_id='$user_id' AND kategori='$kategori'"
                    );
                ?>

                <!-- Kategori Header -->
                <div class="d-flex align-items-center mb-3 <?= $kategori_count > 1 ? 'mt-4' : '' ?>">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-2" 
                         style="width: 35px; height: 35px; background-color: #e8d5e8;">
                        <i class="bi bi-grid-3x3-gap-fill" style="color: #c084a7;"></i>
                    </div>
                    <h6 class="fw-bold mb-0" style="color: #1e3a5f;"><?= ucfirst($kategori) ?></h6>
                </div>

                <div class="row g-3 mb-3">
                    <?php while ($p = mysqli_fetch_assoc($pakaian)) { ?>
                        <div class="col-md-4 col-lg-3">
                            <label class="w-100 h-100" style="cursor: pointer;">
                                <input type="checkbox" name="pakaian[]" value="<?= $p['id']; ?>" class="outfit-checkbox d-none">
                                
                                <div class="card border-2 h-100 outfit-card">
                                    <div class="card-body p-2">
                                        <div class="position-relative">
                                            <img src="../uploads/pakaian/<?= $p['gambar']; ?>" 
                                                 class="w-100 rounded shadow-sm" 
                                                 style="max-height: 200px; object-fit: contain;">
                                            <div class="position-absolute top-0 end-0 m-2 outfit-check d-none">
                                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px; background-color: #c084a7;">
                                                    <i class="bi bi-check-lg fw-bold"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <strong class="d-block" style="color: #1e3a5f;"><?= $p['nama']; ?></strong>
                                            <small class="text-muted"><?= ucfirst($p['kategori']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php } ?>
                </div>

                <?php } ?> <!-- END LOOP KATEGORI -->

                <?php if ($kategori_count == 0) { ?>
                    <div class="text-center py-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px; background-color: #e8d5e8;">
                            <i class="bi bi-inbox fs-2" style="color: #c084a7;"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #1e3a5f;">Belum Ada Pakaian</h5>
                        <p class="text-muted mb-3">Upload pakaian terlebih dahulu untuk membuat outfit</p>
                        <a href="upload_pakaian.php" class="btn text-white" style="background-color: #c084a7;">
                            <i class="bi bi-plus-circle me-2"></i>Upload Pakaian
                        </a>
                    </div>
                <?php } ?>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="outfit.php" class="btn btn-lg btn-outline-secondary px-4">
                <i class="bi bi-x-circle me-2"></i>Batal
            </a>
            <button type="submit" name="submit" class="btn btn-lg flex-fill text-white shadow-sm" 
                    style="background-color: #c084a7;">
                <i class="bi bi-check-circle me-2"></i>Simpan Outfit
            </button>
        </div>

    </form>

</div>

<style>
.outfit-card {
    transition: all 0.3s ease;
}
.outfit-checkbox:checked ~ .outfit-card {
    border-color: #c084a7 !important;
    background-color: #faf7f9;
}
.outfit-checkbox:checked ~ .outfit-card .outfit-check {
    display: block !important;
}
.outfit-card:hover {
    border-color: #e8d5e8;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(192, 132, 167, 0.2);
}
</style>

<?php include "../components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>