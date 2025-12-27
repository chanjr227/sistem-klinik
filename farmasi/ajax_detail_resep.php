<?php
include '../config/db.php';

$id = intval($_GET['id']);

$q = $koneksi->query("
    SELECT r.resep_obat, p.nama AS nama_pasien
    FROM riwayat_konsultasi r
    JOIN pasien_akun p ON r.id_pasien = p.id_pasien
    WHERE r.id = $id
");

$data = $q->fetch_assoc();
?>

<h5>👤 Pasien: <?= $data['nama_pasien'] ?></h5>

<hr>

<p><strong>Catatan Resep Dokter:</strong></p>
<div class="alert alert-info">
    <?= nl2br($data['resep_obat']) ?>
</div>

<a href="cetak_resep.php?id=<?= $id ?>"
    target="_blank"
    class="btn btn-success">
    🖨️ Cetak Resep
</a>