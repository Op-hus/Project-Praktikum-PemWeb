<?php
include 'header.php';
include 'koneksi.php';

$uid  = (int)$_SESSION['user_id'];
$role = $role ?? 'warga';

if ($role == 'warga' && isset($_POST['simpan_profil'])) {
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $gaji   = (float)$_POST['penghasilan'];
    $tang   = (int)$_POST['jumlah_tanggungan'];
    $kat    = (int)$_POST['kategori_id'];

    $cek = mysqli_query($conn, "SELECT id FROM penerima WHERE user_id=$uid");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE penerima SET alamat='$alamat', penghasilan=$gaji, jumlah_tanggungan=$tang, kategori_id=$kat WHERE user_id=$uid");
    } else {
        mysqli_query($conn, "INSERT INTO penerima (user_id, alamat, penghasilan, jumlah_tanggungan, kategori_id) VALUES ($uid, '$alamat', $gaji, $tang, $kat)");
    }
    echo "<div class='alert alert-sb-info'>Data keluarga penerima berhasil disimpan!</div>";
}

if (($role == 'admin' || $role == 'petugas') && isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM penerima WHERE id=$id_hapus");
    echo "<div class='alert alert-sb-info'>Data penerima berhasil dihapus!</div>";
}
?>

<?php if ($role == 'warga'):
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penerima WHERE user_id=$uid"));
?>
<div class="sb-card">
    <h4 class="sb-section-title"><i class="fas fa-id-card me-2"></i>Profil Keluarga Penerima</h4>
    <p class="text-muted small">Lengkapi data sosial-ekonomi keluarga Anda untuk perhitungan skor prioritas bantuan.</p>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($d['alamat'] ?? '') ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Penghasilan per Bulan (Rp)</label>
                <input type="number" name="penghasilan" class="form-control" value="<?= $d['penghasilan'] ?? '' ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Jumlah Tanggungan</label>
                <input type="number" name="jumlah_tanggungan" class="form-control" value="<?= $d['jumlah_tanggungan'] ?? '' ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Kategori Kelompok Rentan</label>
                <select name="kategori_id" class="form-select" required>
                    <?php
                    $qk = mysqli_query($conn, "SELECT * FROM kategori_rentan ORDER BY bobot_skor DESC");
                    while ($k = mysqli_fetch_assoc($qk)) {
                        $sel = (isset($d['kategori_id']) && $d['kategori_id'] == $k['id']) ? 'selected' : '';
                        echo "<option value='{$k['id']}' $sel>{$k['nama_kategori']} (Bobot: {$k['bobot_skor']})</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <button type="submit" name="simpan_profil" class="btn btn-sb-primary px-4">
                <i class="fas fa-save me-1"></i> Simpan Data Warga
            </button>
            <button type="reset" class="btn btn-outline-secondary">
                <i class="fas fa-undo me-1"></i> Reset Formulir
            </button>
        </div>
    </form>
</div>

<?php else: ?>
<div class="sb-card">
    <h4 class="sb-section-title"><i class="fas fa-address-book me-2"></i>Data Keluarga Penerima Terdaftar</h4>
    <div class="table-responsive">
        <table class="table table-bordered sb-table align-middle">
            <thead>
                <tr>
                    <th>Nama Warga</th>
                    <th>Alamat</th>
                    <th>Penghasilan</th>
                    <th>Tanggungan</th>
                    <th>Kategori Rentan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $qp = mysqli_query($conn, "SELECT p.*, u.nama, k.nama_kategori FROM penerima p JOIN users u ON p.user_id=u.id JOIN kategori_rentan k ON p.kategori_id=k.id ORDER BY p.id DESC");
                if (mysqli_num_rows($qp) == 0) echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada warga yang melengkapi profil penerima.</td></tr>";
                while ($row = mysqli_fetch_assoc($qp)):
                ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td>Rp <?= number_format($row['penghasilan'], 0, ',', '.') ?></td>
                    <td><?= $row['jumlah_tanggungan'] ?> orang</td>
                    <td><span class="badge badge-sb-mint"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                    <td>
                        <a href="penerima.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data penerima ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?php
include 'footer.php'; 
?>