<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') 
    exit;

if (isset($_POST['simpan'])) {
    $id = (int)$_POST['id'];
    $nama = $_POST['nama'];
    $j = $_POST['jenis'];
    $k = (int)$_POST['kuota'];
    $desk = $_POST['deskripsi'];
    if ($id > 0) mysqli_query($conn, "UPDATE program_bantuan SET nama_program='$nama', jenis='$j', kuota=$k, deskripsi='$desk' WHERE id=$id");
    else mysqli_query($conn, "INSERT INTO program_bantuan (nama_program, jenis, kuota, deskripsi) VALUES ('$nama', '$j', $k, '$desk')");
}

if (isset($_GET['hapus'])) mysqli_query($conn, "DELETE FROM program_bantuan WHERE id=" . (int)$_GET['hapus']);
$e = isset($_GET['edit']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM program_bantuan WHERE id=" . (int)$_GET['edit'])) : null;
?>
<div class="card-sb p-4">
    <h4 class="judul-sb mb-3">Program Bantuan</h4>
    <form method="POST" class="row g-2 mb-3">
        <input type="hidden" name="id" value="<?= $e['id'] ?? 0 ?>">
        <div class="col-md-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Program" value="<?= $e['nama_program'] ?? '' ?>" required>
        </div>
        <div class="col-md-2">
            <select name="jenis" class="form-select">
                <?php foreach (['sembako', 'tunai', 'alat bantu', 'beasiswa'] as $jn) echo "<option value='$jn' " . (($e['jenis'] ?? '') == $jn ? 'selected' : '') . ">" . ucfirst($jn) . "</option>"; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi" value="<?= $e['deskripsi'] ?? '' ?>" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="kuota" class="form-control" placeholder="Kuota" value="<?= $e['kuota'] ?? '' ?>" required>
        </div>
        <div class="col-md-2 d-flex gap-1"><button type="submit" name="simpan" class="btn btn-ungu w-100"><?= $e ? 'Update' : 'Tambah' ?></button><?php if ($e) echo "<a href='program.php' class='btn btn-secondary'>X</a>"; ?></div>
    </form>
    <table class="table table-bordered table-sb">
        <thead>
            <tr>
                <th>Program</th>
                <th>Jenis</th>
                <th>Deskripsi</th>
                <th>Kuota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $q = mysqli_query($conn, "SELECT * FROM program_bantuan");
                while ($d = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $d['nama_program'] ?></td>
                    <td><span class="badge badge-biru"><?= $d['jenis'] ?></span></td>
                    <td><?= $d['deskripsi'] ?></td>
                    <td><?= $d['kuota'] ?></td>
                    <td><a href="?edit=<?= $d['id'] ?>" class="btn btn-sm btn-biru">Edit</a> <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>