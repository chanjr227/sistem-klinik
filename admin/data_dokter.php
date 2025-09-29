<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data dokter dari database
$sql = "SELECT * FROM dokter ORDER BY nama ASC";
$result = $koneksi->query($sql);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Dokter - Klinik Sehat</title>
    <link rel="stylesheet" href="../admin/assets/dokter.css">
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
                <li><a href="../dashboard.php">🏠 Dashboard</a></li>
                <li><a href="../pasien/list.php">👥 Data Pasien</a></li>
                <li><a href="list.php" class="active">🩺 Data Dokter</a></li>
                <li><a href="../antrian/list.php">📋 Antrian</a></li>
                <li><a href="../laporan/index.php">📊 Laporan</a></li>
                <li><a href="../auth/logout.php" class="logout">🚪 Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <header class="content-header">
                <h1>Data Dokter</h1>
                <a href="tambah.php" class="btn-tambah">+ Tambah Dokter</a>
            </header>

            <section class="table-section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Dokter</th>
                            <th>Spesialis</th>
                            <th>Jadwal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . $no++ . "</td>
                                    <td>" . htmlspecialchars($row['nama']) . "</td>
                                    <td>" . htmlspecialchars($row['spesialisasi']) . "</td>
                                    <td>" . htmlspecialchars($row['jadwal_praktik']) . "</td>
                                    <td>
                                        <a href='edit.php?id=" . $row['id_dokter'] . "' class='btn-edit'>✏ Edit</a>
                                        <a href='hapus.php?id=" . $row['id_dokter'] . "' class='btn-delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>🗑 Hapus</a>
                                    </td>
                                  </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center;color:#888;'>Belum ada data dokter.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>