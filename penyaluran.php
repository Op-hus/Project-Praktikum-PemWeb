<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] == 'warga') 
    exit;

if (isset($_POST['selesai'])) {
    mysqli_query($conn, "UPDATE penyaluran SET status='sudah diterima', tanggal_diterima=CURDATE() WHERE id='" . $_POST['id'] . "'");
}
?>
<div class="card p-4">
    <h4 class="mb-3">Modul 8: Antrean Penyaluran (Diurutkan Skor)</h4>
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>Skor</th>
                <th>Penerima</th>
                <th>Program</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT py.id, py.status, v.skor_prioritas, u.nama, pr.nama_program FROM penyaluran py JOIN pengajuan pg ON py.pengajuan_id=pg.id JOIN verifikasi v ON pg.id=v.pengajuan_id JOIN penerima p ON pg.penerima_id=p.id JOIN users u ON p.user_id=u.id JOIN program_bantuan pr ON pg.program_id=pr.id ORDER BY v.skor_prioritas DESC, py.jadwal ASC");
                while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><span class="badge bg-danger"><?= $d['skor_prioritas'] ?></span></td>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['nama_program'] ?></td>
                    <td><?= $d['status'] ?></td>
                    <td><?php if ($d['status'] == 'belum') { ?><form method="POST"><input type="hidden" name="id" value="<?= $d['id'] ?>"><button type="submit" name="selesai" class="btn btn-sm btn-success">Selesai</button></form><?php } ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>