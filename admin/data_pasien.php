<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../config/db.php';

// Proses tambah data rekam medis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pendaftaran = $_POST['id_pendaftaran'];
    $diagnosa = $_POST['diagnosa'];
    $tindakan = $_POST['tindakan'];
    $catatan = $_POST['catatan'];

    $stmt = $koneksi->prepare("INSERT INTO rekam_medis (id_pendaftaran, diagnosa, tindakan, catatan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id_pendaftaran, $diagnosa, $tindakan, $catatan);
    $stmt->execute();
    $success = true;
}
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

    <style>
        /* submenu animation */
        .submenu {
            max-height: 0;
            overflow: hidden;
            background: #2d3748;
            transition: max-height 0.3s ease;
            list-style: none;
            margin: 0;
            padding: 0;
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
            overflow: visible;
            max-height: 500px;
        }

        .arrow {
            transition: transform 0.3s ease;
            float: right;
            margin-top: 4px;
        }

        .logout {
            color: #ef4444 !important;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-hospital-user"></i>
            <span>Klinik Sehat</span>
        </div>

        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fa-solid fa-user-doctor"></i> Data Dokter
                    <i class="fa-solid fa-angle-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                    <li><a href="../admin/dokter/riwayat_konsultasi.php">Riwayat konsultasi</a></li>
                    <li><a href="../admin/dokter/tambah_dokter.php">Tambah Dokter</a></li>
                </ul>
            </li>

            <li><a href="antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
            <li><a href="tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
            <li><a href="../laporan/index.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
            <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Rekam Medis Pasien</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                + Tambah Rekam Medis
            </button>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">✅ Data rekam medis berhasil disimpan!</div>
        <?php endif; ?>

        <div class="card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>ID Pendaftaran</th>
                            <th>Diagnosa</th>
                            <th>Tindakan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM rekam_medis ORDER BY id_rekam DESC";
                        $result = $koneksi->query($sql);
                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <tr>
                                    <td>{$no}</td>
                                    <td>{$row['id_pendaftaran']}</td>
                                    <td>{$row['diagnosa']}</td>
                                    <td>{$row['tindakan']}</td>
                                    <td>{$row['catatan']}</td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted'>Belum ada data rekam medis.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL TAMBAH DATA -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Rekam Medis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID Pendaftaran</label>
                            <input type="number" name="id_pendaftaran" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Diagnosa</label>
                            <textarea name="diagnosa" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tindakan</label>
                            <textarea name="tindakan" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // === SUBMENU TOGGLE ===
        document.querySelectorAll('.submenu-toggle').forEach(toggle => {
            toggle.addEventListener('click', e => {
                e.preventDefault();

                const parent = toggle.parentElement;
                const arrow = toggle.querySelector('.arrow');
                const submenu = parent.querySelector('.submenu');

                // Tutup submenu lain biar rapi
                document.querySelectorAll('.has-submenu.open').forEach(openItem => {
                    if (openItem !== parent) {
                        openItem.classList.remove('open');
                        const openArrow = openItem.querySelector('.arrow');
                        const openSubmenu = openItem.querySelector('.submenu');
                        if (openArrow) openArrow.style.transform = 'rotate(0deg)';
                        if (openSubmenu) openSubmenu.style.maxHeight = null;
                    }
                });

                // Toggle submenu yang diklik
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