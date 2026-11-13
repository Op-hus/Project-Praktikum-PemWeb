<?php 
include 'header.php';
include 'koneksi.php';
if ($_SESSION['role'] != 'warga') 
    exit;
$uid = $_SESSION['user_id'];
if (isset($_POST['simpan'])) {
    $alamat = $_POST['alamat'];
    $gaji = $_POST['penghasilan'];
    $tang = $_POST['jumlah_tanggungan'];
    $kat = $_POST['kategori_id'];
    $cek = mysqli_query($conn, "SELECT id FROM penerima WHERE user_id='$uid'");
    if (mysqli_num_rows($cek) > 0) mysqli_query($conn, "UPDATE penerima SET alamat='$alamat', penghasilan='$gaji', jumlah_tanggungan='$tang', kategori_id='$kat' WHERE user_id='$uid'");
    else mysqli_query($conn, "INSERT INTO penerima (user_id, alamat, penghasilan, jumlah_tanggungan, kategori_id) VALUES ('$uid', '$alamat', '$gaji', '$tang', '$kat')");
    echo "<div class='alert alert-success'>Profil disimpan!</div>";
}
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penerima WHERE user_id='$uid'"));
?>
<div class="card p-4">
    <h4 class="mb-3">Modul 3: Lengkapi Profil Warga</h4>
    <form method="POST">
        <div class="mb-2">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required><?= $d['alamat'] ?? '' ?></textarea>
        </div>
        <div class="mb-2">
            <label>Penghasilan (Rp)</label>
            <input type="number" name="penghasilan" class="form-control" value="<?= $d['penghasilan'] ?? '' ?>" required>
        </div>
        <div class="mb-2">
            <label>Jml Tanggungan (Anak/Istri)</label>
            <input type="number" name="jumlah_tanggungan" class="form-control" value="<?= $d['jumlah_tanggungan'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Kategori Kerentanan</label>
            <select name="kategori_id" class="form-select">
                <?php $qk = mysqli_query($conn, "SELECT * FROM kategori_rentan");
                while ($k = mysqli_fetch_assoc($qk)) {
                    $sel = (isset($d['kategori_id']) && $d['kategori_id'] == $k['id']) ? 'selected' : '';
                    echo "<option value='{$k['id']}' $sel>{$k['nama_kategori']}</option>";
                } 
                ?>
            </select>
        </div>
        <button type="submit" name="simpan" class="btn btn-success">Simpan Profil</button>
    </form>
</div>
<?php 
include 'footer.php'; 
?>