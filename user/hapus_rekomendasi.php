<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: rekomendasi.php");
    exit;
}

$rek_id = $_GET['id'];

// Pastikan rekomendasi ini milik user
$cek = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM rekomendasi 
    WHERE id = $rek_id AND user_id = $user_id
"));

if (!$cek) {
    header("Location: rekomendasi.php");
    exit;
}

// Hapus rekomendasi
mysqli_query($conn, "DELETE FROM rekomendasi WHERE id = $rek_id");

header("Location: rekomendasi.php");
exit;
?>
