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

    <style>
        /* Tambahkan style submenu */
        .submenu {
            display: none;
            list-style: none;
            margin: 0;
            padding: 0;
            background: #2d3748;
            /* lebih gelap dari sidebar */
        }

        .submenu li a {
            display: block;
            padding: 10px 40px;
            /* indent supaya terlihat child menu */
            font-size: 14px;
            color: #cbd5e1;
            text-decoration: none;
        }

        .submenu li a:hover {
            background: #4a5568;
            color: #fff;
        }

        /* kalau parent open, tampilkan submenu */
        .has-submenu.open .submenu {
            display: block;
        }

        /* kasih pointer cursor */
        .has-submenu>a {
            cursor: pointer;
        }

        /* warna logout */
        .logout {
            color: #ef4444 !important;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Klinik Sehat</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"> Dashboard</a></li>
                <li><a href="data_pasien.php"> Data Pasien</a></li>

                <!-- Menu Dokter dengan Submenu -->
                <li class="has-submenu">
                    <a href="#"> Data Dokter ▸</a>
                    <ul class="submenu">
                        <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                    </ul>
                </li>

                <li><a href="../antrian/list.php"> Antrian</a></li>
                <li><a href="tambah_admin.php"> Tambah Akun</a></li>
                <li><a href="../laporan/index.php"> Laporan</a></li>
                <li><a href="logout.php" class="logout"> Logout</a></li>
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

    <script>
        // Toggle submenu
        document.querySelectorAll(".has-submenu > a").forEach(menu => {
            menu.addEventListener("click", function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle("open");

                // Ubah panah
                if (parent.classList.contains("open")) {
                    this.innerHTML = "Data Dokter ▾";
                } else {
                    this.innerHTML = "Data Dokter ▸";
                }
            });
        });
    </script>
</body>

</html>