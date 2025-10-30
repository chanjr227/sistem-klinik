<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../../config/db.php';

$user_id = $_SESSION['user_id'];
$query = $koneksi->query("SELECT * FROM admin WHERE id_admin = '$user_id'");
$user = $query->fetch_assoc();

// Cek hak akses
if (!$user || $user['role'] !== 'superadmin') {
    // Jika bukan superadmin, tampilkan halaman pemberitahuan
    echo '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Akses Ditolak</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: "Inter", sans-serif;
                background-color: #f8fafc;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .notif-box {
                background: white;
                padding: 40px;
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }
            .notif-box h1 {
                font-size: 24px;
                color: #dc2626;
                margin-bottom: 10px;
            }
            .notif-box p {
                color: #475569;
                margin-bottom: 20px;
            }
            .notif-box i {
                font-size: 50px;
                color: #ef4444;
                margin-bottom: 15px;
            }
            .notif-box a {
                background-color: #2563eb;
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                transition: 0.3s;
            }
            .notif-box a:hover {
                background-color: #1d4ed8;
            }
        </style>
    </head>
    <body>
        <div class="notif-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <h1>Akses Ditolak</h1>
            <p>Maaf, halaman ini hanya bisa diakses oleh <b>Superadmin</b>.</p>
            <a href="../dashboard.php">Kembali ke Dashboard</a>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
    </body>
    </html>
    ';
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Dokter - Klinik Sehat</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
    <link rel="stylesheet" href="../assets/dokter.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .submenu {
            display: none;
            list-style: none;
            margin: 0;
            padding: 0;
            background: #2d3748;
        }

        .submenu li a {
            display: block;
            padding: 10px 40px;
            font-size: 14px;
            color: #cbd5e1;
            text-decoration: none;
        }

        .submenu li a:hover {
            background: #4a5568;
            color: #fff;
        }

        .has-submenu.open .submenu {
            display: block;
        }

        .has-submenu>a {
            cursor: pointer;
        }

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
                <li><a href="../dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="../data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

                <li class="has-submenu open">
                    <a href="#" class="submenu-toggle active">
                        <i class="fa-solid fa-user-doctor"></i>
                        Data Dokter
                        <i class="fa-solid fa-angle-right arrow" style="transform: rotate(90deg);"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="data_dokter.php" class="active">Lihat Data Dokter</a></li>
                        <li><a href="riwayat_konsultasi.php">Riwayat Konsultasi</a></li>
                        <li><a href="tambah_dokter.php">Tambah Dokter</a></li>
                    </ul>
                </li>

                <li><a href="../antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="../tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="../laporan.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>

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