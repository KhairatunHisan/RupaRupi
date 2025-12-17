<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];
$user = $_SESSION['user_id'];

// Pastikan pakaian milik user sendiri
$cek = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT foto FROM pakaian WHERE id=$id AND user_id=$user"
));

if (!$cek) {
    die("Tidak diizinkan!");
}

// Hapus file foto
unlink("../uploads/pakaian/".$cek['foto']);

// Hapus data
mysqli_query($conn, "DELETE FROM pakaian WHERE id=$id");

header("Location: pakaian.php");
exit();
