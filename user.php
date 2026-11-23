<?php 
include 'header.php';
include 'koneksi.php';

if ($role != 'admin') 
    exit;

if (isset($_POST['simpan'])) {
    $id = (int)$_POST['id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    if ($id > 0) {
        $sql_p = !empty($_POST['password']) ? ", password='" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "'" : "";
        mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role' $sql_p WHERE id=$id");
    } else {
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$pass', '$role')");
    }
}
if (isset($_GET['hapus'])) mysqli_query($conn, "DELETE FROM users WHERE id=" . (int)$_GET['hapus']);
$e = isset($_GET['edit']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=" . (int)$_GET['edit'])) : null;
?>
<div class="card-sb p-4">
    <h4 class="judul-sb mb-3">Data User</h4>
    <form method="POST" class="row g-2 mb-3">
        <input type="hidden" name="id" value="<?= $e['id'] ?? 0 ?>">
        <div class="col-md-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?= $e['nama'] ?? '' ?>" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $e['email'] ?? '' ?>" required>
        </div>
        <div class="col-md-2">
            <input type="password" name="password" class="form-control" placeholder="<?= $e ? 'Pass Baru (Opsional)' : 'Password' ?>" <?= $e ? '' : 'required' ?>>
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select">
                <?php foreach (['admin', 'petugas', 'warga'] as $r) echo "<option value='$r' " . (($e['role'] ?? '') == $r ? 'selected' : '') . ">" . ucfirst($r) . "</option>"; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1"><button type="submit" name="simpan" class="btn btn-ungu w-100"><?= $e ? 'Update' : 'Tambah' ?></button><?php if ($e) echo "<a href='user.php' class='btn btn-secondary'>X</a>"; ?></div>
    </form>
    <table class="table table-bordered table-sb">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT * FROM users");
                while ($d = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['email'] ?></td>
                    <td><span class="badge badge-biru"><?= $d['role'] ?></span></td>
                    <td><a href="?edit=<?= $d['id'] ?>" class="btn btn-sm btn-biru">Edit</a> <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
include 'footer.php'; 
?>