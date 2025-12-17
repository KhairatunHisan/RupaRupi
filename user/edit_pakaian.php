<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) header("Location: ../auth/login.php");

$id = $_GET['id'];
$user = $_SESSION['user_id'];

// Ambil data pakaian
$data = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM pakaian WHERE id=$id AND user_id=$user"
));

if (!$data) die("Tidak diizinkan!");

if ($_POST) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];

    // Jika upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        unlink("../uploads/pakaian/".$data['gambar']);

        $file = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/pakaian/" . $file);

        mysqli_query($conn,
            "UPDATE pakaian SET nama='$nama', kategori='$kategori', gambar='$file'
             WHERE id=$id"
        );
    } else {
        mysqli_query($conn,
            "UPDATE pakaian SET nama='$nama', kategori='$kategori'
             WHERE id=$id"
        );
    }

    header("Location: pakaian.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pakaian — RupaRupi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="padding-top: 70px;">

<?php include "../components/header.php"; ?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="pakaian.php?kategori=<?= $data['kategori'] ?>" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">Edit Pakaian</h3>
            <p class="text-muted mb-0">Perbarui informasi pakaian Anda</p>
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
                             style="width: 70px; height: 70px; background-color: #d4e3f0;">
                            <i class="bi bi-pencil-square fs-2" style="color: #1e3a5f;"></i>
                        </div>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        
                        <!-- Nama Pakaian -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-tag-fill me-1"></i>Nama Pakaian
                            </label>
                            <input name="nama" value="<?= $data['nama'] ?>" 
                                   class="form-control form-control-lg border-2" required>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i>Kategori
                            </label>
                            <select class="form-select form-select-lg border-2" name="kategori" required>
                                <option value="">Pilih kategori...</option>
                                <option value="hijab" <?= $data['kategori']=='hijab' ? 'selected' : '' ?>>🧕 Hijab</option>
                                <option value="atasan" <?= $data['kategori']=='atasan' ? 'selected' : '' ?>>👕 Atasan</option>
                                <option value="bawahan" <?= $data['kategori']=='bawahan' ? 'selected' : '' ?>>👖 Bawahan</option>
                                <option value="dress" <?= $data['kategori']=='dress' ? 'selected' : '' ?>>👗 Dress</option>
                                <option value="sepatu" <?= $data['kategori']=='sepatu' ? 'selected' : '' ?>>👟 Sepatu</option>
                                <option value="aksesoris" <?= $data['kategori']=='aksesoris' ? 'selected' : '' ?>>💍 Aksesoris</option>
                            </select>
                        </div>

                        <!-- Foto Lama -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-image-fill me-1"></i>Foto Saat Ini
                            </label>
                            <div class="text-center p-3 bg-light rounded border">
                                <img src="../uploads/pakaian/<?= $data['gambar'] ?>" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Foto Baru -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-cloud-upload-fill me-1"></i>Ganti Foto (Opsional)
                            </label>
                            <input type="file" name="gambar" class="form-control form-control-lg border-2" accept="image/*">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Kosongkan jika tidak ingin mengganti foto
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-lg flex-fill text-white shadow-sm" 
                                    style="background-color: #1e3a5f;">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                            <a href="pakaian.php?kategori=<?= $data['kategori'] ?>" 
                               class="btn btn-lg btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>

                    </form>

                    <!-- Info Alert -->
                    <div class="alert alert-light border mt-4 mb-0">
                        <small class="text-muted">
                            <i class="bi bi-lightbulb-fill me-1" style="color: #c084a7;"></i>
                            <strong>Tips:</strong> Gunakan foto dengan pencahayaan baik untuk hasil terbaik
                        </small>
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