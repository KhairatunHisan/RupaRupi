<?php
session_start();
$title = "Edit Profil";
include "../components/header_admin.php";
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<div class="container mt-4 mb-5">

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="profile_admin.php" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-1" style="color: #1e3a5f;">
                <i class="bi bi-pencil-square me-2"></i>Edit Profil
            </h3>
            <p class="text-muted mb-0">Perbarui informasi akun Anda</p>
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
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #c084a7 0%, #88a8c8 100%);">
                            <i class="bi bi-person-fill fs-1 text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: #1e3a5f;">Informasi Profil</h5>
                        <small class="text-muted">Edit data pribadi Anda di bawah</small>
                    </div>

                    <form action="../admin/admin_profile_edit_proses.php" method="POST">

                        <!-- Username -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-person-fill me-1"></i>Username
                            </label>
                            <input type="text" name="username" class="form-control form-control-lg border-2" 
                                   value="<?= $user['username']; ?>" 
                                   placeholder="Masukkan username" required>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Username akan ditampilkan di profil Anda
                            </small>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-envelope-fill me-1"></i>Email
                            </label>
                            <input type="email" name="email" class="form-control form-control-lg border-2" 
                                   value="<?= $user['email']; ?>" 
                                   placeholder="nama@email.com" required>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Email digunakan untuk login dan notifikasi
                            </small>
                        </div>

                        <!-- Current Role Display (Read Only) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #1e3a5f;">
                                <i class="bi bi-shield-fill-check me-1"></i>Role Akun
                            </label>
                            <div class="d-flex align-items-center p-3 bg-light rounded border">
                                <span class="badge rounded-pill px-3 py-2 text-white" 
                                      style="background-color: <?= $user['role'] == 'admin' ? '#88a8c8' : '#c084a7' ?>;">
                                    <?= ucfirst($user['role']); ?>
                                </span>
                                <small class="text-muted ms-3">Role tidak dapat diubah</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-lg flex-fill text-white shadow-sm" 
                                    style="background-color: #1e3a5f;">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                            <a href="profile_admin.php" class="btn btn-lg btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>

                    </form>

                    <!-- Info Alert -->
                    <div class="alert alert-light border mt-4 mb-0">
                        <small class="text-muted">
                            <i class="bi bi-lightbulb-fill me-1" style="color: #c084a7;"></i>
                            <strong>Tips:</strong> Pastikan email Anda valid dan masih aktif
                        </small>
                    </div>

                </div>
            </div>


        </div>
    </div>

</div>

<?php include "../components/footer.php"; ?>