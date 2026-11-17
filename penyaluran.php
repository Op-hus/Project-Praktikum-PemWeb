<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] == 'warga') exit;
if (isset($_POST['selesai'])) mysqli_query($conn, "UPDATE penyaluran SET status='sudah diterima', tanggal_diterima=CURDATE() WHERE id=" . (int)$_POST['id']);
?>
<div class="card-sb p-4">
    <h4 class="judul-sb mb-3">Antrean Penyaluran</h4>
    <table class="table table-bordered table-sb">
        <thead>
            <tr>
                <th>Skor</th>
                <th>Penerima</th>
                <th>Program</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT py.id, py.jadwal, py.status, v.skor_prioritas, u.nama, pr.nama_program FROM penyaluran py JOIN pengajuan pg ON py.pengajuan_id=pg.id JOIN verifikasi v ON pg.id=v.pengajuan_id JOIN penerima p ON pg.penerima_id=p.id JOIN users u ON p.user_id=u.id JOIN program_bantuan pr ON pg.program_id=pr.id ORDER BY v.skor_prioritas DESC, py.jadwal ASC");
                while ($d = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><span class="badge badge-toska"><?= $d['skor_prioritas'] ?></span></td>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['nama_program'] ?></td>
                    <td><?= $d['jadwal'] ?></td>
                    <td><?= $d['status'] ?></td>
                    <td><?php if ($d['status'] == 'belum'): ?><form method="POST"><input type="hidden" name="id" value="<?= $d['id'] ?>"><button type="submit" name="selesai" class="btn btn-sm btn-toska">Selesai</button></form><?php endif; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>