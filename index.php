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
            if (isset($_POST['remember'])) setcookie('remember_user_id', $d['id'], time() + (86400 * 30), "/");
            header("Location: dashboard.php");
            exit;
        } else $error = "Password salah!";
    } else $error = "Email tidak ditemukan!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SetaraBantu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-bg d-flex align-items-center justify-content-center vh-100">
    <div class="card-sb p-4" style="width:100%; max-width:380px;">
        <h3 class="text-center judul-sb mb-3">SetaraBantu</h3>
        <?php if (isset($error)) echo "<div class='alert alert-danger py-2'>$error</div>"; ?>
        <form method="POST">
            <input type="email" name="email" class="form-control mb-2" placeholder="Email (xxxx@bantu.com)" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
            <div class="form-check mb-3"><input type="checkbox" class="form-check-input" name="remember" id="rem"><label class="form-check-label" for="rem">Ingat Saya (Cookie)</label></div>
            <button type="submit" name="login" class="btn btn-ungu w-100">Masuk</button>
        </form>
    </div>
</body>
</html>