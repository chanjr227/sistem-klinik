<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../../config/db.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Dokter - Klinik Sehat</title>
    <link rel="stylesheet" href="../assets/dokter.css">
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Klinik Sehat</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../data_pasien.php">Data Pasien</a></li>
                <li><a href="data_dokter.php" class="active">Data Dokter</a></li>
                <li><a href="../antrian/list.php">📋 Antrian</a></li>
                <li><a href="../laporan/index.php">📊 Laporan</a></li>
                <li><a href="../auth/logout.php" class="logout">🚪 Logout</a></li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="content">
            <header class="content-header">
                <h1>Data Dokter</h1>
                <a href="tambah_dokter.php" class="btn-tambah">Tambah Dokter</a>
            </header>

            <section class="table-section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Spesialisasi</th>
                            <th>Jadwal Praktik</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $koneksi->query("SELECT * FROM dokter");
                        if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id_dokter']) ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['spesialisasi'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['jadwal_praktik'] ?? '-') ?></td>
                                    <td>
                                        <a href="edit_dokter.php?id=<?= $row['id_dokter'] ?>" class="btn-edit">Edit</a>
                                        <a href="hapus_dokter.php?id=<?= $row['id_dokter'] ?>" class="btn-delete" onclick="return confirm('Yakin hapus dokter ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="5">Belum ada data dokter.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

        </main>
    </div>
</body>

</html>