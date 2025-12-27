<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    die("ID konsultasi tidak ditemukan");
}

$id_konsultasi = (int) $_GET['id'];

/* =========================
   AMBIL DATA KONSULTASI
========================= */
$sql_konsultasi = "
    SELECT 
        r.id_pendaftaran,
        r.tanggal,
        r.diagnosa,
        r.resep_obat,
        p.nama AS nama_pasien,
        d.nama AS nama_dokter,
        d.spesialisasi
    FROM riwayat_konsultasi r
    JOIN pasien_akun p ON r.id_pasien = p.id_pasien
    JOIN dokter d ON r.id_dokter = d.id_dokter
    WHERE r.id = $id_konsultasi
";

$konsultasi = mysqli_fetch_assoc(mysqli_query($koneksi, $sql_konsultasi));

if (!$konsultasi) {
    die("Data konsultasi tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Resep</title>
    <link rel="stylesheet" href="assets/detail_resep.css">
</head>

<body>

    <div class="container">

        <h2>KLINIK SEHAT</h2>
        <h4>DETAIL RESEP DOKTER</h4>
        <hr>

        <p><strong>Pasien :</strong> <?= $konsultasi['nama_pasien'] ?></p>
        <p><strong>Dokter :</strong> <?= $konsultasi['nama_dokter'] ?> (<?= $konsultasi['spesialisasi'] ?>)</p>
        <p><strong>Tanggal :</strong> <?= $konsultasi['tanggal'] ?></p>
        <p><strong>Diagnosa :</strong> <?= $konsultasi['diagnosa'] ?></p>

        <h5>Resep Obat</h5>
        <div class="resep">
            <?= nl2br($konsultasi['resep_obat']) ?>
        </div>

        <div class="ttd">
            <p>Dokter</p>
            <br><br>
            <p><strong><?= $konsultasi['nama_dokter'] ?></strong></p>
        </div>

        <!-- TOMBOL -->
        <div class="btn-area">
            <button onclick="window.print()">🖨️ Cetak Resep</button>
            <a href="resep.php" class="btn-back">⬅ Kembali</a>
        </div>

    </div>

</body>

</html>