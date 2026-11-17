<?php 
include 'header.php';
include 'koneksi.php';

$q1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM program_bantuan"));
$q2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pengajuan"));
$q3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pengajuan WHERE status='disetujui'"));
?>
<div class="card-sb p-4">
    <h4 class="judul-sb mb-3">Dashboard & Laporan</h4>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-ungu"><b><?= $q1['t'] ?></b> Program Bantuan</div>
        </div>
        <div class="col-md-4">
            <div class="stat-biru"><b><?= $q2['t'] ?></b> Total Pengajuan</div>
        </div>
        <div class="col-md-4">
            <div class="stat-toska"><b><?= $q3['t'] ?></b> Pengajuan Disetujui</div>
        </div>
    </div>
    <h5 class="judul-sb">Statistik Penerima per Kelompok Rentan</h5>
    <table class="table table-bordered table-sb">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Warga</th>
            </tr>
        </thead>
        <tbody>
            <?php $q = mysqli_query($conn, "SELECT k.nama_kategori, COUNT(p.id) as jml FROM penerima p JOIN kategori_rentan k ON p.kategori_id=k.id GROUP BY k.nama_kategori");
                while ($d = mysqli_fetch_assoc($q)) 
                    echo "<tr><td>{$d['nama_kategori']}</td><td>{$d['jml']}</td></tr>"; 
            ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>