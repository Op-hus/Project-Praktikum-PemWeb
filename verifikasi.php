<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] == 'warga') 
    exit;

if (isset($_POST['verif'])) {
    $peng_id = $_POST['pengajuan_id'];
    $stat = $_POST['status'];
    $petugas = $_SESSION['user_id'];
    $t = date('Y-m-d');
    mysqli_query($conn, "UPDATE pengajuan SET status='$stat' WHERE id='$peng_id'");

    if ($stat == 'disetujui') {
        $d_pen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.penghasilan, p.jumlah_tanggungan, k.bobot_skor FROM pengajuan pg JOIN penerima p ON pg.penerima_id = p.id JOIN kategori_rentan k ON p.kategori_id = k.id WHERE pg.id = '$peng_id'"));
        $skor = $d_pen['bobot_skor'] + ($d_pen['jumlah_tanggungan'] * 5) + max(0, 10 - floor($d_pen['penghasilan'] / 500000));
        mysqli_query($conn, "INSERT INTO verifikasi (pengajuan_id, petugas_id, skor_prioritas, tanggal) VALUES ('$peng_id', '$petugas', '$skor', '$t')");
        mysqli_query($conn, "INSERT INTO penyaluran (pengajuan_id, jadwal, status) VALUES ('$peng_id', DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'belum')");
    }
}
?>
<div class="card p-4">
    <h4 class="mb-3">Modul 7: Verifikasi & Hitung Skor SDG 10</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Warga</th>
                <th>Program</th>
                <th>Kerentanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $q = mysqli_query($conn, "SELECT pg.id, u.nama, k.nama_kategori, pr.nama_program FROM pengajuan pg JOIN penerima p ON pg.penerima_id=p.id JOIN users u ON p.user_id=u.id JOIN kategori_rentan k ON p.kategori_id=k.id JOIN program_bantuan pr ON pg.program_id=pr.id WHERE pg.status='diajukan'");
            while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['nama_program'] ?></td>
                    <td><?= $d['nama_kategori'] ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="pengajuan_id" value="<?= $d['id'] ?>">
                            <select name="status" class="form-select form-select-sm me-2">
                                <option value="disetujui">Setujui</option>
                                <option value="ditolak">Tolak</option>
                            </select>
                            <button type="submit" name="verif" class="btn btn-sm btn-primary">Proses</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>