<?php
session_start();
require_once '../config/db.php';

// Pastikan pasien sudah login
if (!isset($_SESSION['pasien_id'])) {
    header("Location: ../pasien/auth/login.php?redirect=../pasien/riwayat.php");
    exit;
}

$id_pasien = $_SESSION['pasien_id'];

// Ambil data riwayat kunjungan dari database
$query = $koneksi->prepare("
    SELECT r.tanggal, d.nama AS nama_dokter, d.spesialisasi, r.diagnosa, r.resep_obat
    FROM riwayat_konsultasi r
    JOIN dokter d ON r.id_dokter = d.id_dokter
    WHERE r.id_pasien = ?
    ORDER BY r.tanggal DESC
");
$query->bind_param("i", $id_pasien);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Kunjungan</title>
    <link rel="stylesheet" href="../assets/index.css">
    <link rel="stylesheet" href="../assets/riwayat.css">
</head>

<body>
    <div class="container">
        <h2>Riwayat Kunjungan</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Tanggal</th>
                    <th>Dokter</th>
                    <th>Diagnosa</th>
                    <th>Resep Obat</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars(date("d-m-Y", strtotime($row['tanggal']))) ?></td>
                        <td><?= htmlspecialchars($row['nama_dokter']) ?> (<?= htmlspecialchars($row['spesialisasi']) ?>)</td>
                        <td><?= nl2br(htmlspecialchars($row['diagnosa'] ?? "-")) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['resep_obat'] ?? "-")) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-data">Belum ada riwayat kunjungan.</p>
        <?php endif; ?>
    </div>
</body>

</html>