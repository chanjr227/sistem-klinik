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
    <link rel="stylesheet" href="../assets/dashboard.css">
    <link rel="stylesheet" href="../assets/dokter.css">
    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Klinik Sehat</span>
            </div>

            <ul class="sidebar-menu">
                <li><a href="../dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="../data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-user-doctor"></i>
                        Data Dokter
                        <i class="fa-solid fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="data_dokter.php">Lihat Data Dokter</a></li>
                        <li><a href="riwayat_konsultasi.php">Riwayat konsultasi</a></li>
                        <li><a href="tambah_dokter.php">Tambah Dokter</a></li>
                    </ul>
                </li>

                <li><a href="../antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="../tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="../laporan/index.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
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
                                        <button class="btn-edit"
                                            onclick="openModal(
                                                '<?= $row['id_dokter'] ?>',
                                                '<?= htmlspecialchars($row['nama']) ?>',
                                                '<?= htmlspecialchars($row['spesialisasi']) ?>',
                                                '<?= htmlspecialchars($row['jadwal_praktik']) ?>'
                                            )">Edit</button>
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

    <!-- Modal Edit -->
    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Edit Dokter</h2>
            <form action="update_dokter.php" method="POST">
                <input type="hidden" id="editId" name="id_dokter">

                <label>Nama Dokter</label>
                <input type="text" id="editNama" name="nama" required>

                <label>Spesialisasi</label>
                <input type="text" id="editSpesialisasi" name="spesialisasi">

                <label>Jadwal Praktik</label>
                <input type="text" id="editJadwal" name="jadwal_praktik">

                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, nama, spesialisasi, jadwal) {
            document.getElementById("editModal").style.display = "flex";
            document.getElementById("editId").value = id;
            document.getElementById("editNama").value = nama;
            document.getElementById("editSpesialisasi").value = spesialisasi;
            document.getElementById("editJadwal").value = jadwal;
        }

        function closeModal() {
            document.getElementById("editModal").style.display = "none";
        }

        window.onclick = function(event) {
            let modal = document.getElementById("editModal");
            if (event.target == modal) {
                closeModal();
            }
        }

        // === SUBMENU TOGGLE ===
        document.querySelectorAll(".submenu-toggle").forEach(menu => {
            menu.addEventListener("click", function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle("open");

                const arrow = this.querySelector(".arrow");
                if (parent.classList.contains("open")) {
                    arrow.style.transform = "rotate(90deg)";
                } else {
                    arrow.style.transform = "rotate(0deg)";
                }
            });
        });
    </script>


</body>

</html>