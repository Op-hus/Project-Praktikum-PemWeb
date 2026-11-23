<?php 
include 'header.php';
include 'koneksi.php';
function e_dash($v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function one_count(mysqli $c, string $s): int
{
    $r = mysqli_query($c, $s);
    $d = mysqli_fetch_assoc($r);
    return (int)$d['total'];
}
$total_program = one_count($conn, "SELECT COUNT(*) total FROM program_bantuan");
$total_pengajuan = one_count($conn, "SELECT COUNT(*) total FROM pengajuan");
$disetujui = one_count($conn, "SELECT COUNT(*) total FROM pengajuan WHERE status='disetujui'");
$tersalurkan = one_count($conn, "SELECT COUNT(*) total FROM penyaluran WHERE status='sudah diterima'");
$menunggu = one_count($conn, "SELECT COUNT(*) total FROM pengajuan WHERE status='diajukan'");
$ditolak = one_count($conn, "SELECT COUNT(*) total FROM pengajuan WHERE status='ditolak'");
$kategori = mysqli_query($conn, "SELECT k.nama_kategori,k.bobot_skor,COUNT(p.id) jumlah FROM kategori_rentan k LEFT JOIN penerima p ON p.kategori_id=k.id GROUP BY k.id,k.nama_kategori,k.bobot_skor ORDER BY jumlah DESC,k.bobot_skor DESC");
$terbaru = mysqli_query($conn, "SELECT pg.tanggal,u.nama,pr.nama_program,pg.status FROM pengajuan pg JOIN penerima p ON pg.penerima_id=p.id JOIN users u ON p.user_id=u.id JOIN program_bantuan pr ON pg.program_id=pr.id ORDER BY pg.id DESC LIMIT 5");
$labels = [];
$values = [];
while ($k = mysqli_fetch_assoc($kategori)) {
    $labels[] = $k['nama_kategori'];
    $values[] = (int)$k['jumlah'];
}
?><div class="hero-sb p-4 p-md-5 mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8 position-relative" style="z-index:1">
            <div class="section-label text-info mb-2">Dashboard SetaraBantu</div>
            <h1 class="h3 fw-bold mb-2">Selamat datang kembali, <?= e_dash($nama) ?>! 👋</h1>
            <p class="mb-0 opacity-75">Pantau ekosistem bantuan sosial yang lebih adil dan berbasis prioritas.</p>
        </div>
        <div class="col-lg-4 d-flex justify-content-lg-end gap-2 mt-4 mt-lg-0 position-relative" style="z-index:1"><img class="sdg-mini" src="assets/img/sdg1.svg" alt="SDG 1"><img class="sdg-mini" src="assets/img/sdg10.svg" alt="SDG 10"></div>
    </div>
</div>
<div class="d-flex justify-content-between align-items-end mb-3">
    <div>
        <div class="section-label">Ringkasan operasional</div>
        <h2 class="judul-sb h4 mb-0">Data hari ini</h2>
    </div><span class="badge badge-toska"><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y') ?></span>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-ungu"><i class="bi bi-box-seam fs-4"></i><b><?= $total_program ?></b><span>Program bantuan</span></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-biru"><i class="bi bi-file-earmark-text fs-4"></i><b><?= $total_pengajuan ?></b><span>Total pengajuan</span></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-toska"><i class="bi bi-hourglass-split fs-4"></i><b><?= $menunggu ?></b><span>Menunggu verifikasi</span></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-ungu"><i class="bi bi-check2-circle fs-4"></i><b><?= $tersalurkan ?></b><span>Sudah tersalurkan</span></div>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card-sb p-4 h-100">
            <h3 class="judul-sb h5">Kelompok rentan</h3>
            <p class="text-muted small">Distribusi penerima berdasarkan kategori.</p>
            <div style="height:240px"><canvas id="kategoriChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card-sb p-4 h-100">
            <h3 class="judul-sb h5">Pengajuan terbaru</h3>
            <p class="text-muted small">Aktivitas pengajuan bantuan terkini.</p>
            <div class="table-responsive">
                <table class="table table-sb">
                    <thead>
                        <tr>
                            <th>Warga</th>
                            <th>Program</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody><?php while ($d = mysqli_fetch_assoc($terbaru)): ?><tr>
                                <td><?= e_dash($d['nama']) ?><br><small class="text-muted"><?= e_dash($d['tanggal']) ?></small></td>
                                <td><?= e_dash($d['nama_program']) ?></td>
                                <td><span class="badge badge-<?= $d['status'] === 'disetujui' ? 'toska' : ($d['status'] === 'ditolak' ? 'danger' : 'biru') ?>"><?= e_dash(ucfirst($d['status'])) ?></span></td>
                            </tr><?php endwhile;
                                if (mysqli_num_rows($terbaru) === 0): ?><tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada pengajuan.</td>
                            </tr><?php endif; ?></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    new Chart(document.getElementById('kategoriChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data: <?= json_encode($values) ?>,
                backgroundColor: ['#533E85', '#488FB1', '#4FD3C4', '#F59E0B', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 16
                    }
                }
            }
        }
    });
</script>

<?php 
include 'footer.php'; 
?>