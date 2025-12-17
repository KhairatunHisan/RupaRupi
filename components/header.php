<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : "RupaRupi"; ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Icons Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="padding-top: 70px;">

<!-- NAVBAR -->
<nav class="navbar navbar-dark fixed-top shadow-sm px-3 py-3" style="background: linear-gradient(135deg, #1e3a5f 0%, #0a1929 100%);">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- HOME ICON -->
        <a href="../user/dashboard.php" class="text-white text-decoration-none fs-4" title="Beranda">
            <i class="bi bi-house-door-fill"></i>
        </a>

        <!-- LOGO & BRAND -->
        <div class="d-flex align-items-center">
            <span class="navbar-brand mb-0 fw-bold text-white fs-5">
                RupaRupi
            </span>
        </div>

        <!-- PROFIL ICON -->
        <a href="../user/profile.php" class="text-white text-decoration-none fs-4" title="Profil">
            <i class="bi bi-person-circle"></i>
        </a>
    </div>
</nav>

<div class="container mt-4 mb-5 pb-3">