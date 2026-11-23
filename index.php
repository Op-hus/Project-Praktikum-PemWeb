<?php
include 'koneksi.php';
session_start();
if (isset($_COOKIE['remember_user_id']) && !isset($_SESSION['user_id'])) {
    $q = mysqli_query($conn, "SELECT * FROM users WHERE id=" . (int)$_COOKIE['remember_user_id']);
    if ($d = mysqli_fetch_assoc($q)) {
        $_SESSION['user_id'] = $d['id'];
        $_SESSION['role'] = $d['role'];
        $_SESSION['nama'] = $d['nama'];
        header("Location: dashboard.php");
        exit;
    }
}
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];
    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if ($d = mysqli_fetch_assoc($q)) {
        if (password_verify($pass, $d['password']) || md5($pass) === $d['password']) {
            $_SESSION['user_id'] = $d['id'];
            $_SESSION['role'] = $d['role'];
            $_SESSION['nama'] = $d['nama'];
            if (isset($_POST['remember'])) setcookie('remember_user_id', $d['id'], time() + 86400 * 30, '/');
            header('Location: dashboard.php');
            exit;
        } else $error = 'Password salah!';
    } else $error = 'Email tidak ditemukan!';
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Masuk · SetaraBantu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="login-bg d-flex align-items-center justify-content-center py-4">
    <div class="login-wrap">
        <section class="login-brand d-flex flex-column justify-content-center"><img class="logo mb-4" src="assets/img/logo.svg" alt="SetaraBantu">
            <p class="fs-5 fw-semibold mb-2">Bantuan sosial yang lebih tepat, transparan, dan setara.</p>
            <p class="opacity-75 mb-4">Platform penyaluran bantuan berbasis skor prioritas untuk memastikan dukungan sampai kepada mereka yang paling membutuhkan.</p>
            <div class="d-flex gap-3"><img class="sdg-badge" src="assets/img/sdg1.svg" alt="SDG 1"><img class="sdg-badge" src="assets/img/sdg10.svg" alt="SDG 10"></div>
        </section>
        <section class="login-panel">
            <div class="section-label mb-2">Portal layanan sosial</div>
            <h1 class="judul-sb h3 mb-1">Selamat datang kembali</h1>
            <p class="text-muted mb-4">Masuk untuk melanjutkan ke SetaraBantu.</p><?php if (isset($error)): ?><div class="alert alert-danger border-0"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div><?php endif; ?><form method="POST"><label class="form-label fw-semibold">Email</label>
                <div class="input-group mb-3"><span class="input-group-text"><i class="bi bi-envelope"></i></span><input type="email" name="email" class="form-control" placeholder="nama@email.com" required></div><label class="form-label fw-semibold">Password</label>
                <div class="input-group mb-3"><span class="input-group-text"><i class="bi bi-lock"></i></span><input id="password" type="password" name="password" class="form-control" placeholder="Masukkan password" required><button type="button" class="btn btn-light border" onclick="const x=document.getElementById('password');x.type=x.type==='password'?'text':'password'"><i class="bi bi-eye"></i></button></div>
                <div class="form-check mb-4"><input type="checkbox" class="form-check-input" name="remember" id="rem"><label class="form-check-label text-muted" for="rem">Ingat saya di perangkat ini</label></div><button type="submit" name="login" class="btn btn-ungu w-100 py-3"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Portal</button>
            </form>
        </section>
    </div>
</body>

</html>