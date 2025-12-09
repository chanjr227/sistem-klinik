<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    echo "ID konsultasi tidak ditemukan.";
    exit;
}

$id_konsultasi = $_GET['id'];

// Ambil info konsultasi
$sql_konsultasi = "
    SELECT r.tanggal, r.diagnosa, p.nama AS nama_pasien, d.nama AS nama_dokter, d.spesialisasi
    FROM riwayat_konsultasi r
    LEFT JOIN pasien p ON r.id_pasien = p.id_pasien
    LEFT JOIN dokter d ON r.id_dokter = d.id_dokter
    WHERE r.id = $id_konsultasi
";
$konsultasi = mysqli_fetch_assoc(mysqli_query($koneksi, $sql_konsultasi));

// Ambil detail resep
$sql_detail = "
    SELECT dr.jumlah, o.nama_obat
    FROM detail_resep dr
    LEFT JOIN obat o ON dr.id_obat = o.id_obat
    WHERE dr.id_rekam = $id_konsultasi
";
$result_detail = mysqli_query($koneksi, $sql_detail);
?>

<p><strong>Pasien:</strong> <?= $konsultasi['nama_pasien'] ?></p>
<p><strong>Dokter:</strong> <?= $konsultasi['nama_dokter'] ?> (<?= $konsultasi['spesialisasi'] ?>)</p>
<p><strong>Tanggal:</strong> <?= $konsultasi['tanggal'] ?></p>
<p><strong>Diagnosa:</strong> <?= $konsultasi['diagnosa'] ?></p>

<h5>Daftar Obat:</h5>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nama Obat</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        if ($result_detail && mysqli_num_rows($result_detail) > 0):
            while ($row = mysqli_fetch_assoc($result_detail)):
        ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama_obat'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                </tr>
            <?php
            endwhile;
        else:
            ?>
            <tr>
                <td colspan="3" class="text-center">Tidak ada obat untuk resep ini.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>