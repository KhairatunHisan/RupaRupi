<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_POST) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $user_id = $_SESSION['user_id'];

    // Upload gambar
    $file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $newName = time() . "_" . $file;
    move_uploaded_file($tmp, "../uploads/pakaian/" . $newName);

    mysqli_query($conn, 
        "INSERT INTO pakaian (user_id, nama, kategori, gambar) 
         VALUES ('$user_id','$nama','$kategori','$newName')");

    header("Location: pakaian.php?kategori=" . urlencode($kategori));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Pakaian — RupaRupi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="padding-top: 70px;">

<?php include "../components/header.php"; ?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="pakaian.php" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">Upload Pakaian Baru</h3>
            <p class="text-muted mb-0">Tambahkan pakaian ke koleksi Anda</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    
                    <!-- Icon Header -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px; background-color: #e8d5e8;">
                            <i class="bi bi-cloud-upload-fill fs-2" style="color: #c084a7;"></i>
                        </div>
                    </div>

                    <form method="POST" enctype="multipart/form-data">

                        <!-- Nama Pakaian -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-tag-fill me-1"></i>Nama Pakaian
                            </label>
                            <input type="text" class="form-control form-control-lg border-2" 
                                   name="nama" placeholder="Contoh: Kemeja Putih" required>
                        </div>

                        <!-- Dropdown Kategori -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i>Kategori
                            </label>
                            <select class="form-select form-select-lg border-2" name="kategori" required>
                                <option value="">Pilih kategori...</option>
                                <option value="hijab">🧕 Hijab</option>
                                <option value="atasan">👕 Atasan</option>
                                <option value="bawahan">👖 Bawahan</option>
                                <option value="dress">👗 Dress</option>
                                <option value="sepatu">👟 Sepatu</option>
                                <option value="aksesoris">💍 Aksesoris</option>
                            </select>
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-image-fill me-1"></i>Gambar Pakaian
                            </label>
                            <input type="file" class="form-control form-control-lg border-2" 
                                   name="gambar" accept="image/*" required>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Format: JPG, PNG, atau JPEG. Maksimal 5MB
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-lg flex-fill text-white shadow-sm" 
                                    style="background-color: #c084a7;">
                                <i class="bi bi-cloud-upload me-2"></i>Upload Pakaian
                            </button>
                            <a href="pakaian.php" class="btn btn-lg btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>

                    </form>

                    <!-- Info Tips -->
                    <div class="alert alert-light border mt-4 mb-0">
                        <h6 class="fw-bold mb-2" style="color: #1e3a5f;">
                            <i class="bi bi-lightbulb-fill me-1" style="color: #c084a7;"></i>Tips Upload:
                        </h6>
                        <ul class="mb-0 small text-muted">
                            <li>Gunakan foto dengan pencahayaan yang baik</li>
                            <li>Pastikan pakaian terlihat jelas</li>
                            <li>Background polos lebih baik</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<?php include "../components/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>