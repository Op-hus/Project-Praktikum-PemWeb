<?php
include 'koneksi.php'; session_start();
if(isset($_COOKIE['remember_user_id']) && !isset($_SESSION['user_id'])) {
    $c_id = $_COOKIE['remember_user_id'];
    $q = mysqli_query($conn, "SELECT * FROM users WHERE id='$c_id'");
    if(mysqli_num_rows($q) > 0){
        $d = mysqli_fetch_assoc($q);
        $_SESSION['user_id'] = $d['id']; $_SESSION['role'] = $d['role']; $_SESSION['nama'] = $d['nama'];
        header("Location: dashboard.php"); exit;
    }
}
if(isset($_SESSION['user_id'])){ header("Location: dashboard.php"); exit; }

if (isset($_POST['login'])) {
    $email = $_POST['email']; $password = $_POST['password']; 
    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($q) > 0) {
        $d = mysqli_fetch_assoc($q);
        if(password_verify($password, $d['password']) || md5($password) === $d['password']) {
            $_SESSION['user_id'] = $d['id']; $_SESSION['role'] = $d['role']; $_SESSION['nama'] = $d['nama'];
            if(isset($_POST['remember'])) setcookie('remember_user_id', $d['id'], time() + (86400 * 30), "/");
            header("Location: dashboard.php"); exit;
        } else { $error = "Password salah!"; }
    } else { $error = "Email tidak ditemukan!"; }
}
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <title>Login - SetaraBantu</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
<body class="bg-primary d-flex align-items-center justify-content-center vh-100">
<div class="card p-4 shadow" style="width: 100%; max-width: 400px; border-radius:15px;">
    <h3 class="text-center mb-3 text-primary">SetaraBantu</h3>
    <?php 
    if(isset($error)) 
    {
        echo "<div class='alert alert-danger'>$error</div>";
    }
    ?>
    <form method="POST">
        <input type="email" name="email" class="form-control mb-3" required placeholder="Email (cth: admin@bantu.com)">
        <input type="password" name="password" class="form-control mb-3" required placeholder="Password (cth: admin123)">
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label" for="remember">Ingat Saya</label>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
    </form>
</div>
</body>
</html>
