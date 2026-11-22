<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
} 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SetaraBantu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-mint">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sb mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">SetaraBantu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="user.php">Data User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="kategori.php">Kategori Rentan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="program.php">Program Bantuan</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] == 'warga'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="penerima.php">Profil Warga</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pengajuan.php">Pengajuan</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] == 'petugas' || $_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="verifikasi.php">Verifikasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="penyaluran.php">Penyaluran</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <a href="logout.php" class="btn btn-toska btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">