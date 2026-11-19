<<<<<<< HEAD
<?php
include 'header.php';
include 'koneksi.php';

if (!in_array($_SESSION['role'], ['admin', 'petugas'], true)) {
    header('Location: dashboard.php');
    exit;
}

function e_verif($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$pesan = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verif'])) {
    $pengajuan_id = filter_input(INPUT_POST, 'pengajuan_id', FILTER_VALIDATE_INT);
    $status = $_POST['status'] ?? '';
    $catatan = trim($_POST['catatan'] ?? '');
    $petugas_id = (int)$_SESSION['user_id'];

    if (!$pengajuan_id || !in_array($status, ['disetujui', 'ditolak'], true)) {
        $error = 'Data verifikasi tidak valid.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT pg.id, pg.status, p.penghasilan, p.jumlah_tanggungan, k.bobot_skor
            FROM pengajuan pg
            JOIN penerima p ON pg.penerima_id = p.id
            JOIN kategori_rentan k ON p.kategori_id = k.id
            WHERE pg.id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $pengajuan_id);
        mysqli_stmt_execute($stmt);
        $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$data) {
            $error = 'Pengajuan tidak ditemukan.';
        } elseif ($data['status'] !== 'diajukan') {
            $error = 'Pengajuan ini sudah diproses sebelumnya.';
        } else {
            // Skor SDG 10: bobot kategori + tanggungan (maks. 30) + kondisi penghasilan (maks. 20).
            $skor_kategori = (int)$data['bobot_skor'];
            $skor_tanggungan = min((int)$data['jumlah_tanggungan'] * 5, 30);
            $skor_penghasilan = (int)max(0, min(20, 20 - floor((float)$data['penghasilan'] / 500000)));
            $skor = $skor_kategori + $skor_tanggungan + $skor_penghasilan;

            mysqli_begin_transaction($conn);
            try {
                $stmt = mysqli_prepare($conn, 'UPDATE pengajuan SET status = ? WHERE id = ?');
                mysqli_stmt_bind_param($stmt, 'si', $status, $pengajuan_id);
                if (!mysqli_stmt_execute($stmt)) throw new Exception('Gagal memperbarui status pengajuan.');
                mysqli_stmt_close($stmt);

                if ($status === 'disetujui') {
                    $stmt = mysqli_prepare($conn, 'INSERT INTO verifikasi (pengajuan_id, petugas_id, skor_prioritas, catatan, tanggal) VALUES (?, ?, ?, ?, CURDATE())');
                    mysqli_stmt_bind_param($stmt, 'iiis', $pengajuan_id, $petugas_id, $skor, $catatan);
                    if (!mysqli_stmt_execute($stmt)) throw new Exception('Gagal menyimpan hasil verifikasi.');
                    mysqli_stmt_close($stmt);

                    $stmt = mysqli_prepare($conn, "INSERT INTO penyaluran (pengajuan_id, jadwal, status) VALUES (?, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'belum')");
                    mysqli_stmt_bind_param($stmt, 'i', $pengajuan_id);
                    if (!mysqli_stmt_execute($stmt)) throw new Exception('Gagal memasukkan antrean penyaluran.');
                    mysqli_stmt_close($stmt);
                    $pesan = "Pengajuan disetujui. Skor prioritas: $skor.";
                } else {
                    $pesan = 'Pengajuan ditolak dan tidak dimasukkan ke antrean penyaluran.';
                }
                mysqli_commit($conn);
            } catch (Throwable $exception) {
                mysqli_rollback($conn);
                error_log($exception->getMessage());
                $error = 'Proses verifikasi gagal. Silakan coba lagi.';
            }
        }
    }
}

$pengajuan = mysqli_query($conn, "SELECT pg.id, pg.tanggal, u.nama, k.nama_kategori, k.bobot_skor,
    p.penghasilan, p.jumlah_tanggungan, pr.nama_program
    FROM pengajuan pg
    JOIN penerima p ON pg.penerima_id = p.id
    JOIN users u ON p.user_id = u.id
    JOIN kategori_rentan k ON p.kategori_id = k.id
    JOIN program_bantuan pr ON pg.program_id = pr.id
    WHERE pg.status = 'diajukan' ORDER BY pg.tanggal ASC, pg.id ASC");
?>
<div class="card-sb p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h4 class="judul-sb mb-1">Verifikasi & Prioritas</h4><small class="text-muted">Setujui pengajuan untuk menghitung skor dan membuat antrean penyaluran.</small></div>
        <span class="badge badge-toska"><?= mysqli_num_rows($pengajuan) ?> menunggu</span>
    </div>
    <?php if ($pesan): ?><div class="alert alert-success"><?= e_verif($pesan) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= e_verif($error) ?></div><?php endif; ?>
    <div class="table-responsive"><table class="table table-bordered table-sb align-middle">
        <thead><tr><th>Warga</th><th>Program</th><th>Kategori</th><th>Penghasilan</th><th>Tanggungan</th><th>Proses</th></tr></thead>
        <tbody>
        <?php if (mysqli_num_rows($pengajuan) === 0): ?><tr><td colspan="6" class="text-center text-muted">Tidak ada pengajuan yang menunggu verifikasi.</td></tr><?php endif; ?>
        <?php while ($d = mysqli_fetch_assoc($pengajuan)): ?>
        <tr>
            <td><?= e_verif($d['nama']) ?><br><small class="text-muted">Diajukan <?= e_verif($d['tanggal']) ?></small></td>
            <td><?= e_verif($d['nama_program']) ?></td>
            <td><?= e_verif($d['nama_kategori']) ?></td>
            <td>Rp <?= number_format((float)$d['penghasilan'], 0, ',', '.') ?></td>
            <td><?= (int)$d['jumlah_tanggungan'] ?> orang</td>
            <td><form method="post" class="d-flex flex-column gap-1">
                <input type="hidden" name="pengajuan_id" value="<?= (int)$d['id'] ?>">
                <select name="status" class="form-select form-select-sm" required><option value="disetujui">Setujui</option><option value="ditolak">Tolak</option></select>
                <input type="text" name="catatan" class="form-control form-control-sm" maxlength="255" placeholder="Catatan (opsional)">
                <button type="submit" name="verif" class="btn btn-sm btn-ungu">Proses</button>
            </form></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table></div>
    <small class="text-muted">Rumus skor: bobot kategori + (tanggungan × 5, maksimal 30) + skor penghasilan (maksimal 20).</small>
</div>
<?php include 'footer.php'; ?>
=======
<?php 
include 'header.php';
include 'koneksi.php';

if ($_SESSION['role'] == 'warga') 
    exit;

if (isset($_POST['verif'])) {
    $peng_id = (int)$_POST['pengajuan_id'];
    $stat = $_POST['status'];
    $petugas = $_SESSION['user_id'];
    mysqli_query($conn, "UPDATE pengajuan SET status='$stat' WHERE id=$peng_id");
    if ($stat == 'disetujui') {
        $d_pen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.penghasilan, p.jumlah_tanggungan, k.bobot_skor FROM pengajuan pg JOIN penerima p ON pg.penerima_id=p.id JOIN kategori_rentan k ON p.kategori_id=k.id WHERE pg.id=$peng_id"));
        $skor = $d_pen['bobot_skor'] + ($d_pen['jumlah_tanggungan'] * 5) + max(0, 10 - floor($d_pen['penghasilan'] / 500000));
        mysqli_query($conn, "INSERT INTO verifikasi (pengajuan_id, petugas_id, skor_prioritas, tanggal) VALUES ($peng_id, $petugas, $skor, CURDATE())");
        mysqli_query($conn, "INSERT INTO penyaluran (pengajuan_id, jadwal, status) VALUES ($peng_id, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'belum')");
    }
}
?>
<div class="card-sb p-4">
    <h4 class="judul-sb mb-3">Verifikasi & Hitung Skor</h4>
    <table class="table table-bordered table-sb">
        <thead>
            <tr>
                <th>Warga</th>
                <th>Program</th>
                <th>Kerentanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT pg.id, u.nama, k.nama_kategori, pr.nama_program FROM pengajuan pg JOIN penerima p ON pg.penerima_id=p.id JOIN users u ON p.user_id=u.id JOIN kategori_rentan k ON p.kategori_id=k.id JOIN program_bantuan pr ON pg.program_id=pr.id WHERE pg.status='diajukan'");
                while ($d = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $d['nama'] ?></td>
                    <td><?= $d['nama_program'] ?></td>
                    <td><?= $d['nama_kategori'] ?></td>
                    <td>
                        <form method="POST" class="d-flex gap-1">
                            <input type="hidden" name="pengajuan_id" value="<?= $d['id'] ?>">
                            <select name="status" class="form-select form-select-sm">
                                <option value="disetujui">Setujui</option>
                                <option value="ditolak">Tolak</option>
                            </select>
                            <button type="submit" name="verif" class="btn btn-sm btn-ungu">Proses</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>
>>>>>>> 6fdaa30ab8cb39ee22bb8a97db164372eb7ef925
