<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') 
    exit;

if (isset($_POST['tambah'])) mysqli_query($conn, "INSERT INTO kategori_rentan (nama_kategori, bobot_skor) VALUES ('{$_POST['nama']}', '{$_POST['bobot']}')");
if (isset($_GET['hapus'])) mysqli_query($conn, "DELETE FROM kategori_rentan WHERE id=" . (int)$_GET['hapus']);
?>
<div class="card-sb p-4">
    <h4 class="judul-sb mb-3">Kategori Rentan</h4>
    <form method="POST" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="nama" class="form-control" placeholder="Nama Kategori" required>
        </div>
        <div class="col-md-4">
            <input type="number" name="bobot" class="form-control" placeholder="Bobot Skor" required>
        </div>
        <div class="col-md-2">
            <button type="submit" name="tambah" class="btn btn-ungu w-100">Tambah</button>
        </div>
    </form>
    <table class="table table-bordered table-sb">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Bobot Skor</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT * FROM kategori_rentan ORDER BY bobot_skor DESC");
                while ($d = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $d['nama_kategori'] ?></td>
                    <td><span class="badge badge-toska"><?= $d['bobot_skor'] ?></span></td>
                    <td><a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>