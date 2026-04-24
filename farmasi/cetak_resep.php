<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    die("ID konsultasi tidak ditemukan");
}

$id = (int) $_GET['id'];

$sql = "
    SELECT 
        r.tanggal,
        r.diagnosa,
        r.resep_obat,
        p.nama AS nama_pasien,
        d.nama AS nama_dokter,
        d.spesialisasi
    FROM riwayat_konsultasi r
    LEFT JOIN pasien p ON r.id_pasien = p.id_pasien
    LEFT JOIN dokter d ON r.id_dokter = d.id_dokter
    WHERE r.id = $id
";

$data = mysqli_fetch_assoc(mysqli_query($koneksi, $sql));

if (!$data) {
    die("Data tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Resep</title>
    <link rel="stylesheet" href="../admin/assets/cetak_resep.css">
</head>

<body>

    <div class="resep-container">

        <div class="header">
            <h2>KLINIK SEHAT</h2>
            <p>Jl. Contoh No. 123 | Telp. 0812xxxxxxx</p>
            <hr>
        </div>

        <table class="info">
            <tr>
                <td>Nama Pasien</td>
                <td>: <?= $data['nama_pasien'] ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?= $data['tanggal'] ?></td>
            </tr>
            <tr>
                <td>Dokter</td>
                <td>: <?= $data['nama_dokter'] ?> (<?= $data['spesialisasi'] ?>)</td>
            </tr>
        </table>

        <h4 class="judul">📝 Resep Obat</h4>

        <div class="resep-box">
            <?php
            $lines = explode("\n", trim($data['resep_obat']));
            echo "<ol>";
            foreach ($lines as $line) {
                if (trim($line)) {
                    echo "<li>" . htmlspecialchars($line) . "</li>";
                }
            }
            echo "</ol>";
            ?>
        </div>

        <div class="ttd">
            <p>Dokter Pemeriksa</p>
            <br><br>
            <strong><?= $data['nama_dokter'] ?></strong>
        </div>

        <div class="aksi no-print">
            <button onclick="window.print()">🖨️ Cetak</button>
            <button onclick="window.close()">❌ Tutup</button>
        </div>

    </div>

</body>

</html>