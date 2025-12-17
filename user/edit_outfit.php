<?php
session_start();
$title = "Edit Outfit";
include "../components/header.php";
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: outfit.php");
    exit;
}

$outfit_id = $_GET['id'];
$user_id   = $_SESSION['user_id'];

// Ambil data outfit
$query = "SELECT * FROM outfit WHERE id = '$outfit_id' AND user_id = '$user_id'";
$res   = mysqli_query($conn, $query);
$outfit = mysqli_fetch_assoc($res);

if (!$outfit) {
    echo "<div class='alert alert-danger border-0 shadow-sm'><i class='bi bi-exclamation-triangle-fill me-2'></i>Outfit tidak ditemukan!</div>";
    include "../components/footer.php";
    exit;
}

// Pecah pakaian_ids menjadi array
$selected_ids = explode(",", $outfit['pakaian_ids']);

// Ambil semua pakaian milik user lalu kelompokkan per kategori
$q = mysqli_query($conn, "SELECT * FROM pakaian WHERE user_id='$user_id' ORDER BY kategori ASC, nama ASC");

$kategori_group = [];
while ($p = mysqli_fetch_assoc($q)) {
    $kategori_group[$p['kategori']][] = $p;
}
?>

<div class="container mt-4 mb-5">
    
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="outfit.php" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">Edit Outfit</h3>
            <p class="text-muted mb-0">Ubah kombinasi pakaian untuk outfit Anda</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="proses_edit_outfit.php" method="POST">
                <input type="hidden" name="id" value="<?= $outfit_id; ?>">

                <!-- Nama Outfit -->
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="color: #1e3a5f;">
                        <i class="bi bi-tag-fill me-1"></i>Nama Outfit
                    </label>
                    <input type="text" name="nama_outfit" class="form-control form-control-lg border-2" 
                           value="<?= $outfit['nama_outfit']; ?>" placeholder="Contoh: Casual Weekend" required>
                </div>

                <!-- Pilih Pakaian per kategori -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #1e3a5f;">
                        <i class="bi bi-box-seam me-1"></i>Pilih Pakaian
                    </label>
                </div>

                <?php foreach ($kategori_group as $kategori => $items) { ?>
                    
                    <!-- Kategori Header -->
                    <div class="d-flex align-items-center mb-3 mt-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-2" 
                             style="width: 35px; height: 35px; background-color: #e8d5e8;">
                            <i class="bi bi-grid-3x3-gap-fill" style="color: #c084a7;"></i>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: #1e3a5f;"><?= ucfirst($kategori); ?></h5>
                    </div>

                    <div class="row g-3 mb-4">
                        <?php foreach ($items as $p) { ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="card border-2 h-100 hover-card">
                                    <div class="card-body p-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="pakaian_ids[]" 
                                                   value="<?= $p['id']; ?>"
                                                   id="pakaian_<?= $p['id']; ?>"
                                                   <?= in_array($p['id'], $selected_ids) ? "checked" : ""; ?>>
                                            <label class="form-check-label fw-semibold" for="pakaian_<?= $p['id']; ?>" style="color: #1e3a5f;">
                                                <?= $p['nama']; ?>
                                            </label>
                                        </div>
                                        <img src="../uploads/pakaian/<?= $p['gambar']; ?>" 
                                             class="img-fluid rounded shadow-sm w-100"
                                             style="max-height: 200px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                <?php } ?>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <button class="btn btn-lg px-4 text-white" type="submit" style="background-color: #1e3a5f;">
                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                    </button>
                    <a href="outfit.php" class="btn btn-lg btn-outline-secondary px-4">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    border-color: #c084a7 !important;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(192, 132, 167, 0.2);
}
.form-check-input:checked {
    background-color: #c084a7;
    border-color: #c084a7;
}
</style>

<?php include "../components/footer.php"; ?>