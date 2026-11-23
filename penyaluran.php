<?php 
include 'header.php';
include 'koneksi.php';

if (!in_array($role, ['admin', 'petugas'], true)) {
    header('Location: dashboard.php');
    exit;
}
function e_sal($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
$pesan = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selesai'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = mysqli_prepare($conn, "UPDATE penyaluran SET status='sudah diterima', tanggal_diterima=CURDATE() WHERE id=? AND status='belum'");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $pesan = mysqli_stmt_affected_rows($stmt) ? 'Status penyaluran berhasil diperbarui.' : 'Data sudah diterima atau tidak ditemukan.';
        mysqli_stmt_close($stmt);
    } else $error = 'ID penyaluran tidak valid.';
}
$q = mysqli_query($conn, "SELECT py.id, py.jadwal, py.status, py.tanggal_diterima, v.skor_prioritas, v.tanggal AS tanggal_verifikasi,
    u.nama, pr.nama_program, k.nama_kategori
    FROM penyaluran py JOIN pengajuan pg ON py.pengajuan_id=pg.id
    JOIN verifikasi v ON pg.id=v.pengajuan_id JOIN penerima p ON pg.penerima_id=p.id
    JOIN users u ON p.user_id=u.id JOIN program_bantuan pr ON pg.program_id=pr.id
    JOIN kategori_rentan k ON p.kategori_id=k.id
    ORDER BY py.status='belum' DESC, v.skor_prioritas DESC, py.jadwal ASC, py.id ASC");
?>
<div class="card-sb p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="judul-sb mb-1">Antrean Penyaluran</h4><small class="text-muted">Urutan otomatis: status belum diterima, skor tertinggi, lalu jadwal terdekat.</small>
        </div><span class="badge badge-toska"><?= mysqli_num_rows($q) ?> data</span>
    </div>
    <?php if ($pesan): ?><div class="alert alert-success"><?= e_sal($pesan) ?></div><?php endif; ?><?php if ($error): ?><div class="alert alert-danger"><?= e_sal($error) ?></div><?php endif; ?>
    <div class="table-responsive">
        <table class="table table-bordered table-sb align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Skor</th>
                    <th>Penerima</th>
                    <th>Kategori</th>
                    <th>Program</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($d = mysqli_fetch_assoc($q)): ?><tr>
                        <td><?= $no++ ?></td>
                        <td><span class="badge badge-toska"><?= (int)$d['skor_prioritas'] ?></span></td>
                        <td><?= e_sal($d['nama']) ?></td>
                        <td><?= e_sal($d['nama_kategori']) ?></td>
                        <td><?= e_sal($d['nama_program']) ?></td>
                        <td><?= e_sal($d['jadwal']) ?></td>
                        <td><?= $d['status'] === 'belum' ? '<span class="badge bg-warning text-dark">Belum diterima</span>' : '<span class="badge bg-success">Sudah diterima</span><br><small>' . e_sal($d['tanggal_diterima']) . '</small>' ?></td>
                        <td><?php if ($d['status'] === 'belum'): ?><form method="post"><input type="hidden" name="id" value="<?= (int)$d['id'] ?>"><button name="selesai" class="btn btn-sm btn-toska" onclick="return confirm('Tandai bantuan sudah diterima?')">Tandai diterima</button></form><?php else: ?>-<?php endif; ?></td>
                    </tr><?php endwhile; ?>
                <?php if ($no === 1): ?><tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data penyaluran.</td>
                    </tr><?php endif; ?></tbody>
        </table>
    </div>
</div>

<?php 
include 'footer.php'; 
?>