<?php
require '../config/db.php';
$result = $koneksi->query("SELECT nama, spesialisasi, jadwal_praktik FROM dokter");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Jadwal Dokter</title>
    <link rel="stylesheet" href="../assets/jadwal.css">
</head>

<body>
    <div class="container">
        <h2>Jadwal Dokter</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Jadwal Praktik</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['spesialisasi'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['jadwal_praktik'] ?? '-') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Tidak ada jadwal dokter</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>