<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';
$result = $koneksi->query("SELECT p.id_pasien, p.nama, p.tanggal_lahir, p.jenis_kelamin, p.no_hp, d.nama AS nama_dokter 
                           FROM pasien p
                           LEFT JOIN dokter d ON p.id_dokter = d.id_dokter
                           ORDER BY p.id_pasien DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Pasien - Klinik Sehat</title>
    <link rel="stylesheet" href="../admin/assets/data_pasien.css">
    <link rel="stylesheet" href="../admin/assets/dashboard.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Klinik Sehat</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="list.php" class="active">Data Pasien</a></li>
                <li><a href="dokter/data_dokter.php">Data Dokter</a></li>
                <li><a href="../antrian/list.php">Antrian</a></li>
                <li><a href="tambah_admin.php">Tambah admin</a></li>
                <li><a href="../laporan/index.php">Laporan</a></li>
                <li><a href="../auth/logout.php" class="logout">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <header class="content-header">
                <h1>Data Pasien</h1>
                <p>Kelola data pasien yang sudah mendaftar di sistem.</p>
            </header>

            <section class="table-section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>No HP</th>
                            <th>Dokter</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id_pasien'] ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= $row['tanggal_lahir'] ?></td>
                                    <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                    <td><?= $row['no_hp'] ?></td>
                                    <td><?= $row['nama_dokter'] ?? '-' ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= $row['id_pasien'] ?>" class="btn-edit">✏️</a>
                                        <a href="hapus.php?id=<?= $row['id_pasien'] ?>" class="btn-delete"
                                            onclick="return confirm('Yakin ingin menghapus pasien ini?')">🗑️</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">Belum ada data pasien</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>