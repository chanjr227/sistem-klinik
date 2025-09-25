<?php
require '../config/db.php';

$query = "
    SELECT p.id_pasien, p.nama, p.tanggal_lahir, p.jenis_kelamin, p.no_hp, 
           d.nama AS nama_dokter, d.spesialisasi, p.created_at
    FROM pasien p
    JOIN dokter d ON p.id_dokter = d.id_dokter
    ORDER BY p.created_at ASC
";
$result = $koneksi->query($query);

if ($result->num_rows > 0): ?>
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