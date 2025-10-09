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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom Style -->
    <style>
        body {
            font-family: "Inter", sans-serif;
            background: #f4f6f8;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #243447, #1b2838);
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.15);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 1.3rem;
            background: rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            flex-grow: 1;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.9rem 1.4rem;
            color: #cfd8dc;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.25s ease;
            font-size: 0.95rem;
        }

        .sidebar-menu li a i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-menu li a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-left: 3px solid #2d89ef;
        }

        .sidebar-menu li a.active {
            background: rgba(45, 137, 239, 0.2);
            border-left: 3px solid #2d89ef;
            color: #fff;
            font-weight: 600;
        }

        .sidebar-menu .submenu {
            list-style: none;
            padding-left: 35px;
            display: none;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu li.open .submenu {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logout {
            color: #ff6b6b !important;
        }

        .logout:hover {
            background: rgba(255, 107, 107, 0.15) !important;
        }

        /* === MAIN CONTENT === */
        .content {
            flex: 1;
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* === TOGGLE BUTTON === */
        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 270px;
            z-index: 1000;
            background: #2d89ef;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed+.toggle-btn {
            left: 100px;
        }

        .toggle-btn:hover {
            background: #1a6ad9;
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                z-index: 999;
            }

            .sidebar.show {
                left: 0;
            }

            .toggle-btn {
                left: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-hospital"></i>
            <span>Klinik Sehat</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>
            <li class="has-submenu">
                <a href="#"><i class="fa-solid fa-user-md"></i> Data Dokter ▸</a>
                <ul class="submenu">
                    <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                </ul>
            </li>
            <li><a href="../antrian/list.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
            <li><a href="tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
            <li><a href="../laporan/index.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
            <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- TOGGLE BUTTON -->
    <button class="toggle-btn" id="toggleSidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    <!-- CONTENT -->
    <main class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📋 Data Rekam Medis Pasien</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">+ Tambah Rekam Medis</button>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Rekam Medis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
        // Toggle submenu
        document.querySelectorAll(".has-submenu > a").forEach(menu => {
            menu.addEventListener("click", function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle("open");
                this.innerHTML = parent.classList.contains("open") ?
                    '<i class="fa-solid fa-user-md"></i> Data Dokter ▾' :
                    '<i class="fa-solid fa-user-md"></i> Data Dokter ▸';
            });
        });

        // Sidebar toggle
        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.getElementById("toggleSidebar");

        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
            if (window.innerWidth < 768) {
                sidebar.classList.toggle("show");
            }
        });
    </script>
</body>

</html>