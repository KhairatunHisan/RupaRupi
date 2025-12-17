<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id  = $_SESSION['user_id'];
$username = $_POST['username'];
$email    = $_POST['email'];

$query = "
    UPDATE users SET
        username = '$username',
        email    = '$email'
    WHERE id = '$user_id'
";

mysqli_query($conn, $query);

// Redirect kembali ke halaman profil
header("Location: profile_admin.php");
exit;
?>
