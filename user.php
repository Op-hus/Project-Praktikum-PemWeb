<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') 
    exit;

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')");
}

if (isset($_GET['hapus'])) mysqli_query($conn, "DELETE FROM users WHERE id='" . $_GET['hapus'] . "'");
?>
<div class="card p-4">
    <h4 class="mb-3">Modul 2: Data User</h4>
    <form method="POST" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="col-md-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="col-md-2"><select name="role" class="form-select">
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="warga">Warga</option>
            </select></div>
        <div class="col-md-1"><button type="submit" name="tambah" class="btn btn-primary w-100">+</button></div>
    </form>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT * FROM users");
                while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['email'] ?></td>
                    <td><?= $d['role'] ?></td>
                    <td><a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>