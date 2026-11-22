<?php 
include 'header.php';
include 'koneksi.php';
function e_dash($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function one_count(mysqli $conn, string $sql): int
{
    $r = mysqli_query($conn, $sql);
    $d = mysqli_fetch_assoc($r);
    return (int)$d['total'];
}
$total_program = one_count($conn, "SELECT COUNT(*) total FROM program_bantuan");
$total_pengajuan = one_count($conn, "SELECT COUNT(*) total FROM pengajuan");
$disetujui = one_count($conn, "SELECT COUNT(*) total FROM pengajuan WHERE status='disetujui'");
$tersalurkan = one_count($conn, "SELECT COUNT(*) total FROM penyaluran WHERE status='sudah diterima'");
$menunggu = one_count($conn, "SELECT COUNT(*) total FROM pengajuan WHERE status='diajukan'");
$ditolak = one_count($conn, "SELECT COUNT(*) total FROM pengajuan WHERE status='ditolak'");
$kategori = mysqli_query($conn, "SELECT k.nama_kategori, k.bobot_skor, COUNT(p.id) jumlah FROM kategori_rentan k LEFT JOIN penerima p ON p.kategori_id=k.id GROUP BY k.id,k.nama_kategori,k.bobot_skor ORDER BY jumlah DESC,k.bobot_skor DESC");
$terbaru = mysqli_query($conn, "SELECT pg.tanggal,u.nama,pr.nama_program,pg.status FROM pengajuan pg JOIN penerima p ON pg.penerima_id=p.id JOIN users u ON p.user_id=u.id JOIN program_bantuan pr ON pg.program_id=pr.id ORDER BY pg.id DESC LIMIT 5");
?>
<div class="card-sb p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="judul-sb mb-1">Dashboard & Laporan</h4><small class="text-muted">Ringkasan penyaluran bantuan sosial SetaraBantu.</small>
        </div><span class="badge badge-toska"><?= date('d/m/Y') ?></span>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg-2">
            <div class="stat-ungu"><b><?= $total_program ?></b><span>Program</span></div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="stat-biru"><b><?= $total_pengajuan ?></b><span>Total Pengajuan</span></div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="stat-toska"><b><?= $menunggu ?></b><span>Menunggu</span></div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="stat-ungu"><b><?= $disetujui ?></b><span>Disetujui</span></div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="stat-toska"><b><?= $tersalurkan ?></b><span>Tersalurkan</span></div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="stat-biru"><b><?= $ditolak ?></b><span>Ditolak</span></div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="judul-sb">Distribusi Kelompok Rentan</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sb">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Bobot</th>
                            <th>Jumlah Warga</th>
                        </tr>
                    </thead>
                    <tbody><?php while ($d = mysqli_fetch_assoc($kategori)): ?><tr>
                                <td><?= e_dash($d['nama_kategori']) ?></td>
                                <td><?= (int)$d['bobot_skor'] ?></td>
                                <td><?= (int)$d['jumlah'] ?></td>
                            </tr><?php endwhile; ?></tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <h5 class="judul-sb">Pengajuan Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sb">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Warga</th>
                            <th>Program</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody><?php while ($d = mysqli_fetch_assoc($terbaru)): ?><tr>
                                <td><?= e_dash($d['tanggal']) ?></td>
                                <td><?= e_dash($d['nama']) ?></td>
                                <td><?= e_dash($d['nama_program']) ?></td>
                                <td><?= e_dash(ucfirst($d['status'])) ?></td>
                            </tr><?php endwhile; ?><?php if (mysqli_num_rows($terbaru) === 0): ?><tr>
                                <td colspan="4" class="text-center text-muted">Belum ada pengajuan.</td>
                            </tr><?php endif; ?></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
include 'footer.php'; 
?>