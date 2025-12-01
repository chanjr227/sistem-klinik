<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data obat
$query = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY id_obat DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Obat - Klinik Sehat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS Dashboard -->
    <link rel="stylesheet" href="../admin/assets/dashboard.css">

    <!-- Bootstrap untuk tabel -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="wrapper">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Klinik Sehat</span>
            </div>

            <ul class="sidebar-menu">
                <li><a href="../admin/dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="../admin/data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-user-doctor"></i>
                        Data Dokter
                        <i class="fa-solid fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                        <li><a href="../admin/dokter/riwayat_konsultasi.php">Riwayat konsultasi</a></li>
                        <li><a href="../admin/dokter/tambah_dokter.php">Tambah Dokter</a></li>
                    </ul>
                </li>

                <li><a href="../farmasi/obat.php" class="active"><i class="fa-solid fa-capsules"></i> Menu Obat</a></li>

                <li><a href="../admin/antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="../admin/tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="../admin/laporan.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="../admin/logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <header class="content-header">
                <h1>Data Obat</h1>
                <p>Manajemen stok & harga obat.</p>
            </header>

            <div class="card p-3">

                <a href="tambah_obat.php" class="btn btn-primary mb-3">+ Tambah Obat</a>

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Obat</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?= $row['id_obat'] ?></td>
                                <td><?= $row['nama_obat'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td>Rp <?= number_format($row['harga'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="edit_obat.php?id=<?= $row['id_obat'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus_obat.php?id=<?= $row['id_obat'] ?>"
                                        onclick="return confirm('Hapus obat ini?')"
                                        class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </main>

    </div>

    <!-- Script untuk submenu sidebar -->
    <script>
        document.querySelectorAll(".submenu-toggle").forEach(menu => {
            menu.addEventListener("click", function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle("open");

                const arrow = this.querySelector(".arrow");
                arrow.style.transform = parent.classList.contains("open") ? "rotate(90deg)" : "rotate(0deg)";
            });
        });
    </script>

</body>

</html>