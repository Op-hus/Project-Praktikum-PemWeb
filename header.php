<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$current = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? 'warga';
$nama = $_SESSION['nama'] ?? 'Pengguna';
$roleLabel = ['admin' => 'Admin', 'petugas' => 'Petugas', 'warga' => 'Warga', 'donatur' => 'Donatur'][$role] ?? ucfirst($role);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($current === 'dashboard.php' ? 'Dashboard' : ucfirst(pathinfo($current, PATHINFO_FILENAME))) ?> · SetaraBantu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-mint">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sb">
        <div class="container"><a class="navbar-brand" href="dashboard.php"><img src="assets/img/logo-white.svg" alt="SetaraBantu"></a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto">
                    <li><a class="nav-link <?= $current === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php"><i class="bi bi-grid-1x2-fill me-1"></i>Dashboard</a></li><?php if ($role === 'admin'): ?><li><a class="nav-link <?= $current === 'user.php' ? 'active' : '' ?>" href="user.php"><i class="bi bi-people me-1"></i>User</a></li>
                        <li><a class="nav-link <?= $current === 'kategori.php' ? 'active' : '' ?>" href="kategori.php"><i class="bi bi-shield-check me-1"></i>Kategori</a></li>
                        <li><a class="nav-link <?= $current === 'program.php' ? 'active' : '' ?>" href="program.php"><i class="bi bi-box-seam me-1"></i>Program</a></li><?php endif; ?><?php if ($role === 'warga'): ?><li><a class="nav-link <?= $current === 'penerima.php' ? 'active' : '' ?>" href="penerima.php"><i class="bi bi-person-vcard me-1"></i>Profil Warga</a></li>
                        <li><a class="nav-link <?= $current === 'pengajuan.php' ? 'active' : '' ?>" href="pengajuan.php"><i class="bi bi-send me-1"></i>Pengajuan</a></li><?php endif; ?><?php if (in_array($role, ['admin', 'petugas'], true)): ?><li><a class="nav-link <?= $current === 'verifikasi.php' ? 'active' : '' ?>" href="verifikasi.php"><i class="bi bi-check2-square me-1"></i>Verifikasi</a></li>
                        <li><a class="nav-link <?= $current === 'penyaluran.php' ? 'active' : '' ?>" href="penyaluran.php"><i class="bi bi-truck me-1"></i>Penyaluran</a></li><?php endif; ?>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <div class="user-pill"><img src="assets/img/avatar-default.svg" alt="">
                        <div><strong><?= htmlspecialchars($nama) ?></strong><small><?= htmlspecialchars($roleLabel) ?></small></div>
                    </div><a href="logout.php" class="btn btn-toska btn-sm"><i class="bi bi-box-arrow-right"></i><span class="d-none d-sm-inline ms-1">Keluar</span></a>
                </div>
            </div>
        </div>
    </nav>
    <main class="container page-shell pt-4">