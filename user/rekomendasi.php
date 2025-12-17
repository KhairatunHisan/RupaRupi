<?php
session_start();
include "../config/db.php";
include "../components/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil username user
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE id=$user_id"));
$username = $user['username'];

// Ambil semua admin/desainer
$admins = mysqli_query($conn, "SELECT id, username FROM users WHERE role='admin'");

$success = "";

// Jika kirim rekomendasi
if (isset($_POST['kirim'])) {

    $kategori = $_POST['kategori'];
    $desainer = $_POST['desainer'];

    $query = "INSERT INTO rekomendasi (user_id, kategori, desainer, status)
              VALUES ($user_id, '$kategori', '$desainer', 'pending')";
    mysqli_query($conn, $query);

    $success = "Permintaan rekomendasi berhasil dikirim!";
}

// Ambil status rekomendasi user
$req = mysqli_query($conn, 
    "SELECT r.*, u.username AS admin_name 
     FROM rekomendasi r 
     LEFT JOIN users u ON r.desainer = u.id
     WHERE r.user_id = $user_id 
     ORDER BY r.id DESC"
);
?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="text-center mb-4">
        <h3 class="fw-bold" style="color: #1e3a5f;">
            <i class="bi bi-stars me-2"></i>Rekomendasi Outfit
        </h3>
        <p class="text-muted">Dapatkan saran outfit terbaik dari admin profesional</p>
    </div>

    <!-- Success Alert -->
    <?php if ($success) { ?>
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div><?= $success ?></div>
        </div>
    <?php } ?>

    <div class="row g-4">

        <!-- FORM AJUKAN REKOMENDASI -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    
                    <!-- Icon Header -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 70px; height: 70px; background-color: #d0dce8;">
                            <i class="bi bi-send-fill fs-2" style="color: #88a8c8;"></i>
                        </div>
                        <h5 class="fw-bold" style="color: #1e3a5f;">Ajukan Rekomendasi</h5>
                        <small class="text-muted">Isi form di bawah untuk meminta saran outfit</small>
                    </div>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-person-fill me-1"></i>Username
                            </label>
                            <input type="text" class="form-control form-control-lg border-2 bg-light" 
                                   value="<?= $username ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i>Kategori Outfit
                            </label>
                            <select name="kategori" class="form-select form-select-lg border-2" required>
                                <option value="">Pilih kategori...</option>
                                <option value="Formal">👔 Formal</option>
                                <option value="Casual">👕 Casual</option>
                                <option value="Streetwear">🎨 Streetwear</option>
                                <option value="Korean Style">🇰🇷 Korean Style</option>
                                <option value="Vintage">🕰️ Vintage</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-award-fill me-1"></i>Pilih Desainer
                            </label>
                            <select name="desainer" class="form-select form-select-lg border-2" required>
                                <option value="">Pilih desainer...</option>
                                <?php 
                                mysqli_data_seek($admins, 0); // Reset pointer
                                while ($a = mysqli_fetch_assoc($admins)) { 
                                ?>
                                    <option value="<?= $a['id'] ?>">
                                        <i class="bi bi-person-badge"></i> <?= $a['username'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit" name="kirim" class="btn btn-lg w-100 text-white shadow-sm" 
                                style="background-color: #88a8c8;">
                            <i class="bi bi-send-fill me-2"></i>Kirim Permintaan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- STATUS REKOMENDASI -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-3" 
                             style="width: 45px; height: 45px; background-color: #e8d5e8;">
                            <i class="bi bi-clock-history fs-5" style="color: #c084a7;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0" style="color: #1e3a5f;">Status Permintaan</h5>
                            <small class="text-muted">Pantau progress rekomendasi Anda</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="border-bottom">
                                <tr>
                                    <th class="fw-semibold" style="color: #1e3a5f;">ID</th>
                                    <th class="fw-semibold" style="color: #1e3a5f;">Kategori</th>
                                    <th class="fw-semibold" style="color: #1e3a5f;">Desainer</th>
                                    <th class="fw-semibold" style="color: #1e3a5f;">Status</th>
                                    <th class="fw-semibold" style="color: #1e3a5f;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php 
                            $has_data = false;
                            while ($r = mysqli_fetch_assoc($req)) { 
                                $has_data = true;
                            ?>
                                <tr>
                                    <td class="fw-bold">#<?= $r['id'] ?></td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2" 
                                              style="background-color: #e8d5e8; color: #c084a7;">
                                            <?= $r['kategori'] ?>
                                        </span>
                                    </td>
                                    <td><?= $r['admin_name'] ?: '-' ?></td>

                                    <td>
                                        <?php if ($r['status'] == 'pending') { ?>
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bi bi-clock me-1"></i>Pending
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Selesai
                                            </span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-1">
                                            <?php if ($r['status'] == 'selesai' && $r['admin_outfit_id']) { ?>
                                                <a href="lihat_outfit.php?id=<?= $r['admin_outfit_id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Lihat Outfit">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            <?php } ?>
                                            <a href="hapus_rekomendasi.php?id=<?= $r['id'] ?>"
                                               class="btn btn-sm btn-outline-danger" title="Hapus"
                                               onclick="return confirm('Yakin ingin menghapus rekomendasi ini?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if (!$has_data) { ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <p class="mb-0">Belum ada permintaan rekomendasi</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>

                        </table>
                    </div>

                    <!-- Info Box -->
                    <div class="alert alert-light border mt-3 mb-0">
                        <small class="text-muted">
                            <i class="bi bi-info-circle-fill me-1" style="color: #88a8c8;"></i>
                            <strong>Info:</strong> Admin akan memproses permintaan Anda dalam 1-3 hari kerja
                        </small>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

<?php include "../components/footer.php"; ?>