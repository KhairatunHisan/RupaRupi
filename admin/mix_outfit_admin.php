<?php
include "../config/db.php";
include "../components/header_admin.php";

$user_id = $_GET['user'];
$req_id = $_GET['id'];

// Ambil info user dan request
$user_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE id=$user_id"));
$request_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT kategori FROM rekomendasi WHERE id=$req_id"));

$success_message = "";

if ($_POST) {
    $nama = $_POST['nama_outfit'];
    $pakaian_ids = isset($_POST['pakaian']) && is_array($_POST['pakaian'])
    ? implode(",", $_POST['pakaian'])
    : "";

    if ($pakaian_ids == "") {
        $error = "Pilih minimal 1 pakaian!";
    } else {
        mysqli_query($conn,
            "INSERT INTO outfit (user_id,nama_outfit,pakaian_ids,dibuat_oleh)
             VALUES ($user_id,'$nama','$pakaian_ids','admin')");

        $outfit_id = mysqli_insert_id($conn);

        mysqli_query($conn,
            "UPDATE rekomendasi SET status='selesai', admin_outfit_id=$outfit_id WHERE id=$req_id");

        $success_message = "Outfit berhasil dikirim ke user!";
    }
}

// Ambil pakaian user dan kelompokkan per kategori
$q = mysqli_query($conn, "SELECT * FROM pakaian WHERE user_id=$user_id ORDER BY kategori ASC, nama ASC");

$kategori_group = [];
while ($p = mysqli_fetch_assoc($q)) {
    $kategori_group[$p['kategori']][] = $p;
}
?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="lihat_request.php" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <i class="bi bi-palette-fill me-2"></i>Buat Outfit Rekomendasi
            </h3>
            <p class="text-muted mb-0">Pilih pakaian untuk user: <strong><?= $user_info['username'] ?></strong></p>
        </div>
    </div>

    <!-- Success Alert -->
    <?php if ($success_message) { ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                <?= $success_message ?>
                <a href="lihat_request.php" class="alert-link ms-2">Kembali ke Request</a>
            </div>
        </div>
    <?php } ?>

    <!-- Error Alert -->
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div><?= $error; ?></div>
        </div>
    <?php } ?>

    <form method="POST">

        <!-- Request Info Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">User</label>
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-person-circle me-2" style="color: #c084a7;"></i>
                            <strong style="color: #1e3a5f;"><?= $user_info['username'] ?></strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Kategori Diminta</label>
                        <div class="p-3 bg-light rounded">
                            <span class="badge rounded-pill px-3 py-2" 
                                  style="background-color: #e8d5e8; color: #c084a7;">
                                <?= $request_info['kategori'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Request ID</label>
                        <div class="p-3 bg-light rounded">
                            <strong style="color: #1e3a5f;">#<?= $req_id ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nama Outfit Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <label class="form-label fw-semibold" style="color: #1e3a5f;">
                    <i class="bi bi-tag-fill me-1"></i>Nama Outfit
                </label>
                <input type="text" name="nama_outfit" class="form-control form-control-lg border-2" 
                       placeholder="Contoh: Casual Weekend" required>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>Beri nama yang sesuai dengan kategori yang diminta
                </small>
            </div>
        </div>

        <!-- Pilih Pakaian Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1e3a5f;">
                    <i class="bi bi-box-seam me-2"></i>Pilih Pakaian User
                </h5>
                <p class="text-muted mb-4">Klik pada pakaian untuk memilih</p>

                <?php if (count($kategori_group) == 0) { ?>
                    <div class="text-center py-5">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px; background-color: #e8d5e8;">
                            <i class="bi bi-inbox fs-2" style="color: #c084a7;"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #1e3a5f;">User Belum Punya Pakaian</h5>
                        <p class="text-muted">User ini belum mengupload pakaian apapun</p>
                    </div>
                <?php } else { ?>

                    <?php
                    $kategori_count = 0;
                    foreach ($kategori_group as $kategori => $items) {
                        $kategori_count++;
                    ?>

                    <!-- Kategori Header -->
                    <div class="d-flex align-items-center mb-3 <?= $kategori_count > 1 ? 'mt-4' : '' ?>">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-2" 
                             style="width: 35px; height: 35px; background-color: #d4e3f0;">
                            <i class="bi bi-grid-3x3-gap-fill" style="color: #1e3a5f;"></i>
                        </div>
                        <h6 class="fw-bold mb-0" style="color: #1e3a5f;"><?= ucfirst($kategori) ?></h6>
                    </div>

                    <div class="row g-3 mb-3">
                        <?php foreach ($items as $p) { ?>
                            <div class="col-md-4 col-lg-3">
                                <label class="w-100 h-100" style="cursor: pointer;">
                                    <input type="checkbox" name="pakaian[]" value="<?= $p['id']; ?>" 
                                           class="outfit-checkbox d-none">
                                    
                                    <div class="card border-2 h-100 outfit-card">
                                        <div class="card-body p-2">
                                            <div class="position-relative">
                                                <img src="../uploads/pakaian/<?= $p['gambar']; ?>" 
                                                     class="w-100 rounded shadow-sm" 
                                                     style="height: 180px; object-fit: contain;">
                                                <div class="position-absolute top-0 end-0 m-2 outfit-check d-none">
                                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 30px; height: 30px; background-color: #88a8c8;">
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

                    <?php } ?>

                <?php } ?>

            </div>
        </div>

        <!-- Action Buttons -->
        <?php if (count($kategori_group) > 0) { ?>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-lg flex-fill text-white shadow-sm" 
                        style="background-color: #88a8c8;">
                    <i class="bi bi-send-fill me-2"></i>Kirim Outfit ke User
                </button>
                <a href="lihat_request.php" class="btn btn-lg btn-outline-secondary">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>
        <?php } else { ?>
            <a href="lihat_request.php" class="btn btn-lg btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Request
            </a>
        <?php } ?>

    </form>

</div>

<style>
.outfit-card {
    transition: all 0.3s ease;
}
.outfit-checkbox:checked ~ .outfit-card {
    border-color: #88a8c8 !important;
    background-color: #f0f6fa;
}
.outfit-checkbox:checked ~ .outfit-card .outfit-check {
    display: block !important;
}
.outfit-card:hover {
    border-color: #d4e3f0;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(136, 168, 200, 0.2);
}
</style>

<?php include "../components/footer.php"; ?>