<?php include 'header.php';
include 'koneksi.php'; ?>
<div class="card p-4">
    <h4 class="mb-3">Modul 4: Kategori Rentan (Bobot Prioritas)</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kategori</th>
                <th>Bobot Skor (SDG 10)</th>
            </tr>
        </thead>
        <tbody><?php $q = mysqli_query($conn, "SELECT * FROM kategori_rentan ORDER BY bobot_skor DESC");
                while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= $d['nama_kategori'] ?></td>
                    <td><?= $d['bobot_skor'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include 'footer.php'; 
?>