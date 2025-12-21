<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Rekam Medis - Klinik Sehat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/dashboard.css">


</head>

<body>
    <div class="d-flex">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Klinik Sehat</span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-user-doctor"></i> Data Dokter
                        <i class="fa-solid fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                        <li><a href="../admin/dokter/riwayat_konsultasi.php">Riwayat konsultasi</a></li>
                        <li><a href="../admin/dokter/tambah_dokter.php">Tambah Dokter</a></li>
                    </ul>
                </li>

                <li class="has-submenu open">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-prescription-bottle"></i> Data Obat
                        <i class="fa-solid fa-angle-right arrow" style="transform: rotate(90deg);"></i>
                    </a>
                    <ul class="submenu" style="display:block;">
                        <li><a href="../farmasi/resep.php"><i class="fa-solid fa-file-medical"></i> Resep Masuk</a></li>
                        <li><a href="../farmasi/obat.php"><i class="fa-solid fa-capsules"></i> Manajemen Obat</a></li>
                    </ul>
                </li>

                <li><a href="antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="laporan.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content flex-fill">
            <h2 class="mb-4">Data Rekam Medis Pasien</h2>

            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Pasien</th>
                                <th>Tanggal Konsultasi</th>
                                <th>Diagnosa</th>
                                <th>Tindakan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "
                            SELECT rm.*, r.tanggal, p.nama AS nama_pasien 
                            FROM rekam_medis rm
                            LEFT JOIN riwayat_konsultasi r ON rm.id_pendaftaran = r.id
                            LEFT JOIN pasien p ON r.id_pasien = p.id_pasien
                            ORDER BY r.tanggal DESC
                        ";
                            $result = $koneksi->query($sql);
                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "
                                <tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama_pasien']}</td>
                                    <td>{$row['tanggal']}</td>
                                    <td>{$row['diagnosa']}</td>
                                    <td>{$row['tindakan']}</td>
                                    <td>{$row['catatan']}</td>
                                </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada data rekam medis.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // SUBMENU TOGGLE
        document.querySelectorAll('.submenu-toggle').forEach(toggle => {
            toggle.addEventListener('click', e => {
                e.preventDefault();
                const parent = toggle.parentElement;
                const arrow = toggle.querySelector('.arrow');
                const submenu = parent.querySelector('.submenu');
                document.querySelectorAll('.has-submenu.open').forEach(openItem => {
                    if (openItem !== parent) {
                        openItem.classList.remove('open');
                        const openArrow = openItem.querySelector('.arrow');
                        const openSubmenu = openItem.querySelector('.submenu');
                        if (openArrow) openArrow.style.transform = 'rotate(0deg)';
                        if (openSubmenu) openSubmenu.style.maxHeight = null;
                    }
                });
                parent.classList.toggle('open');
                if (parent.classList.contains('open')) {
                    arrow.style.transform = 'rotate(90deg)';
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                } else {
                    arrow.style.transform = 'rotate(0deg)';
                    submenu.style.maxHeight = null;
                }
            });
        });
    </script>
</body>

</html>