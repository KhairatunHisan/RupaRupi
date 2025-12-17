<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data dari form
$id            = $_POST['id'];
$nama_outfit   = $_POST['nama_outfit'];
$pakaian_ids   = isset($_POST['pakaian_ids']) ? $_POST['pakaian_ids'] : [];

// Convert array pakaian menjadi string "1,3,5"
$pakaian_ids_str = implode(",", $pakaian_ids);

// Update outfit
$query = "
    UPDATE outfit SET
        nama_outfit  = '$nama_outfit',
        pakaian_ids  = '$pakaian_ids_str'
    WHERE id = '$id' AND user_id = '$user_id'
";

mysqli_query($conn, $query);

// Kembali ke halaman outfit
header("Location: outfit.php");
exit;
?>
