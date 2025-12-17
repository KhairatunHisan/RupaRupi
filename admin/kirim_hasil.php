<?php
session_start();
include "../config/db.php";
include "../components/header_admin.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id']; // id rekomendasi

// Ambil data rekomendasi
$req = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT r.*, u.username 
     FROM rekomendasi r
     JOIN users u ON r.user_id = u.id
     WHERE r.id = $id"
));

if (!$req) die("Data tidak ditemukan.");

$outfit_id = $req['admin_outfit_id'];
$outfit = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM outfit WHERE id=$outfit_id"
));
?>

<h2>Detail Rekomendasi</h2>

User: <?= $req['username'] ?><br>
Status: <?= $req['status'] ?><br><br>

<h3>Outfit yang Dibuat Admin:</h3>
<b><?= $outfit['nama_outfit'] ?></b><br><br>

<?php
$pakaian_ids = explode(",", $outfit['pakaian_ids']);
foreach ($pakaian_ids as $pid) {
    $p = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT foto FROM pakaian WHERE id=$pid"
    ));
    echo "<img src='../uploads/pakaian/$p[foto]' width='120'>";
}
?>

<br><br>
<a href="dashboard.php">Kembali ke Dashboard</a>
