<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] != 'warga') 
    exit;

$uid = $_SESSION['user_id'];
$cek_p = mysqli_query($conn, "SELECT id FROM penerima WHERE user_id='$uid'");

if (mysqli_num_rows($cek_p) == 0) {
    echo "<div class='alert alert-warning'>Isi menu Profil Warga dulu!</div>";
    include 'footer.php';
    exit;
}
$pid = mysqli_fetch_assoc($cek_p)['id'];

if (isset($_POST['ajukan'])) {
    $prg = $_POST['program_id'];
    $als = $_POST['alasan'];
    $t = date('Y-m-d');
    mysqli_query($conn, "INSERT INTO pengajuan (penerima_id, program_id, alasan, status, tanggal) VALUES ('$pid', '$prg', '$als', 'diajukan', '$t')");
}
?>
<div class="card p-4">
    <h4 class="mb-3">Modul 6: Pengajuan Bantuan</h4>
    <form method="POST" class="mb-4">
        <div class="mb-2">
            <label>Pilih Program</label>
            <select name="program_id" class="form-select">
                <?php 
                $q = mysqli_query($conn, "SELECT * FROM program_bantuan WHERE status='aktif'");
                while ($d = mysqli_fetch_assoc($q)) {
                    echo "<option value='{$d['id']}'>{$d['nama_program']}</option>";
                } ?>
            </select>
        </div>
        <div class="mb-2">
            <label>Alasan</label>
            <textarea name="alasan" class="form-control" required></textarea>
        </div>
        <button type="submit" name="ajukan" class="btn btn-primary">Ajukan Bantuan</button>
    </form>
    <h5>Riwayat Pengajuan</h5>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Tanggal</th>
                <th>Program</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT p.tanggal, p.status, b.nama_program FROM pengajuan p JOIN program_bantuan b ON p.program_id=b.id WHERE p.penerima_id='$pid'");
                while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= $d['tanggal'] ?></td>
                    <td><?= $d['nama_program'] ?></td>
                    <td><?= $d['status'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>