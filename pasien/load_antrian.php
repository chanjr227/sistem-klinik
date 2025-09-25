<?php
require '../config/db.php';

$sql = "SELECT p.nama, d.nama AS dokter, p.status, p.created_at 
        FROM pasien p
        JOIN dokter d ON p.id_dokter = d.id_dokter
        ORDER BY p.created_at ASC";

$result = $koneksi->query($sql);

if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Status</th>
                <th>Waktu Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['dokter']) ?></td>
                    <td>
                        <?php if ($row['status'] == 'Menunggu'): ?>
                            ⏳ Menunggu
                        <?php elseif ($row['status'] == 'Dipanggil'): ?>
                            ✅ Dipanggil
                        <?php else: ?>
                            ✔️ Selesai
                        <?php endif; ?>
                    </td>
                    <td><?= date('H:i', strtotime($row['created_at'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="no-data">Belum ada pasien dalam antrian.</p>
<?php endif; ?>