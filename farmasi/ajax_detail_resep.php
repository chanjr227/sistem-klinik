<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    echo "ID konsultasi tidak ditemukan";
    exit;
}

$id_konsultasi = (int) $_GET['id'];

/* =========================
   QUERY KONSULTASI + RESEP
   ========================= */
$sql = "
    SELECT 
        r.tanggal,
        r.diagnosa,
        r.resep_obat,
        p.nama AS nama_pasien,
        d.nama AS nama_dokter,
        d.spesialisasi
    FROM riwayat_konsultasi r
    LEFT JOIN pasien_akun p ON r.id_pasien = p.id_pasien
    LEFT JOIN dokter d ON r.id_dokter = d.id_dokter
    WHERE r.id = $id_konsultasi
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    echo "Query error: " . mysqli_error($koneksi);
    exit;
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan";
    exit;
}
?>

<h5>Detail Resep</h5>

<p><strong>Pasien:</strong> <?= $data['nama_pasien'] ?></p>
<p><strong>Dokter:</strong> <?= $data['nama_dokter'] ?> (<?= $data['spesialisasi'] ?>)</p>
<p><strong>Tanggal:</strong> <?= $data['tanggal'] ?></p>
<p><strong>Diagnosa:</strong> <?= $data['diagnosa'] ?></p>

<hr>

<h6>Resep Obat</h6>
<div class="border p-2">
    <?= nl2br(htmlspecialchars($data['resep_obat'])) ?>
</div>