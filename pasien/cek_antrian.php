<?php
require '../config/db.php'; // pastikan koneksi database

// Ambil semua pasien dengan join dokter
$query = "
    SELECT p.id_pasien, p.nama, p.tanggal_lahir, p.jenis_kelamin, p.no_hp, 
           d.nama AS nama_dokter, d.spesialisasi, p.created_at
    FROM pasien p
    JOIN dokter d ON p.id_dokter = d.id_dokter
    ORDER BY p.created_at ASC
";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cek Antrian Pasien</title>
    <link rel="stylesheet" href="../assets/index.css">
    <style>
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table th,
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #007bff;
            color: white;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Antrian Pasien</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Dokter</th>
                        <th>Spesialisasi</th>
                        <th>No HP</th>
                        <th>Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama']); ?></td>
                            <td><?= htmlspecialchars($row['nama_dokter']); ?></td>
                            <td><?= htmlspecialchars($row['spesialisasi']); ?></td>
                            <td><?= htmlspecialchars($row['no_hp']); ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Belum ada pasien yang mendaftar.</p>
        <?php endif; ?>
    </div>
</body>

</html>