<?php
session_start();
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
$user_id = $_SESSION['user_id'];

// 1. Kosongkan admin_outfit_id pada tabel rekomendasi yang memakai outfit ini
mysqli_query($conn, "
    UPDATE rekomendasi 
    SET admin_outfit_id = NULL 
    WHERE admin_outfit_id = '$outfit_id'
");

// 2. Baru hapus outfit milik user
$query = "DELETE FROM outfit 
          WHERE id = '$outfit_id' AND user_id = '$user_id'";
mysqli_query($conn, $query);

header("Location: outfit.php");
exit;
?>
