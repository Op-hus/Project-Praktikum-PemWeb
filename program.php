<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') 
    exit;

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $j = $_POST['jenis'];
    $k = $_POST['kuota'];
    mysqli_query($conn, "INSERT INTO program_bantuan (nama_program, jenis, kuota, deskripsi) VALUES ('$nama', '$j', '$k', 'Bantuan')");
}

if (isset($_GET['hapus'])) mysqli_query($conn, "DELETE FROM program_bantuan WHERE id='" . $_GET['hapus'] . "'");
?>
<div class="card p-4">
    <h4 class="mb-3">Modul 5: Program Bantuan</h4>
    <form method="POST" class="row g-2 mb-4">
        <div class="col-md-4"><input type="text" name="nama" class="form-control" placeholder="Nama Program" required></div>
        <div class="col-md-3"><select name="jenis" class="form-select">
                <option value="sembako">Sembako</option>
                <option value="tunai">Tunai</option>
            </select></div>
        <div class="col-md-3">
            <input type="number" name="kuota" class="form-control" placeholder="Kuota" required>
        </div>
        <div class="col-md-2">
            <button type="submit" name="tambah" class="btn btn-primary w-100">+</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Program</th>
                <th>Jenis</th>
                <th>Kuota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT * FROM program_bantuan");
                while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= $d['nama_program'] ?></td>
                    <td><?= $d['jenis'] ?></td>
                    <td><?= $d['kuota'] ?></td>
                    <td><a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>