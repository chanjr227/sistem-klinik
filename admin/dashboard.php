<?php
session_start();

// 🔐 CEK LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 🔐 CEK ROLE
if (!in_array($_SESSION['role'], ['admin', 'superadmin'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// === TOTAL PASIEN ===
$total_pasien = 0;
$q1 = $koneksi->query("SELECT COUNT(*) as total FROM pasien");
if ($q1) {
    $total_pasien = $q1->fetch_assoc()['total'] ?? 0;
}

// === TOTAL DOKTER ===
$total_dokter = 0;
$q2 = $koneksi->query("SELECT COUNT(*) as total FROM dokter");
if ($q2) {
    $total_dokter = $q2->fetch_assoc()['total'] ?? 0;
}

// === ANTRIAN ===
$total_antrian = 0;
$q3 = $koneksi->query("SELECT COUNT(*) as total FROM pendaftaran WHERE status != 'selesai'");
if ($q3) {
    $total_antrian = $q3->fetch_assoc()['total'] ?? 0;
}

// === CHART DATA ===
$labels = [];
$values = [];

$sql = "SELECT DATE(tanggal_daftar) as tanggal, COUNT(*) as total
        FROM pendaftaran
        GROUP BY DATE(tanggal_daftar)
        ORDER BY tanggal ASC";

$result = $koneksi->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = date('d M', strtotime($row['tanggal']));
        $values[] = (int)$row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Klinik Sehat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/dashboard.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <li><a href="dashboard.php" class="active">
                        <i class="fa-solid fa-gauge"></i> Dashboard</a>
                </li>

                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
                    <li><a href="data_pasien.php">
                            <i class="fa-solid fa-users"></i> Data Pasien</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
                    <li class="has-submenu">
                        <a href="#" class="submenu-toggle">
                            <i class="fa-solid fa-user-doctor"></i>
                            Data Dokter
                            <i class="fa-solid fa-angle-right arrow"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                            <li><a href="../admin/dokter/riwayat_konsultasi.php">Riwayat konsultasi</a></li>

                            <?php if ($_SESSION['role'] === 'superadmin'): ?>
                                <li><a href="../admin/dokter/tambah_dokter.php">Tambah Dokter</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
                    <li class="has-submenu">
                        <a href="#" class="submenu-toggle">
                            <i class="fa-solid fa-prescription-bottle"></i>
                            Data Obat
                            <i class="fa-solid fa-angle-right arrow"></i>
                        </a>
                        <ul class="submenu">
                            <li><a href="../farmasi/resep.php">
                                    <i class="fa-solid fa-file-medical"></i> Resep Masuk</a>
                            </li>
                            <li><a href="../farmasi/obat.php">
                                    <i class="fa-solid fa-capsules"></i> Manajemen Obat</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'dokter'): ?>
                    <li><a href="konsultasi.php">
                            <i class="fa-solid fa-stethoscope"></i> Konsultasi</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin'): ?>
                    <li><a href="antrian_pasien.php">
                            <i class="fa-solid fa-list"></i> Antrian</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'superadmin'): ?>
                    <li><a href="tambah_admin.php">
                            <i class="fa-solid fa-user-plus"></i> Tambah Admin</a>
                    </li>
                <?php endif; ?>

                <li><a href="laporan.php">
                        <i class="fa-solid fa-file-lines"></i> Laporan</a>
                </li>

                <li><a href="logout.php" class="logout">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <header class="content-header">
                <div>
                    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?></h1>
                    <p>Kelola klinik Anda dengan mudah dari dashboard ini.</p>
                    <button id="darkToggle" class="dark-btn">🌙 Dark Mode</button>
                </div>
            </header>

            <section class="cards">
                <div class="card blue d-flex align-items-center">
                    <i class="fa-solid fa-users fa-2x me-3"></i>
                    <div>
                        <h3>Total Pasien</h3>
                        <p class="number"><?= $total_pasien ?></p>
                    </div>
                </div>

                <div class="card green d-flex align-items-center">
                    <i class="fa-solid fa-user-doctor fa-2x me-3"></i>
                    <div>
                        <h3>Dokter Aktif</h3>
                        <p class="number"><?= $total_dokter ?></p>
                    </div>
                </div>

                <div class="card orange d-flex align-items-center">
                    <i class="fa-solid fa-list-check fa-2x me-3"></i>
                    <div>
                        <h3>Antrian Hari Ini</h3>
                        <p class="number"><?= $total_antrian ?></p>
                    </div>
                </div>
            </section>

            <section class="chart-section">
                <h2>Statistik Kunjungan Pasien</h2>
                <canvas id="kunjunganChart"></canvas>
            </section>


        </main>
    </div>
    <script>
        const body = document.body;
        const toggleBtn = document.getElementById('darkToggle');

        // INIT THEME
        let isDark = localStorage.getItem('theme') === 'dark';

        if (isDark) {
            body.classList.add('dark');
            toggleBtn.textContent = "☀️ Light Mode";
        }

        // === CHART ===
        const ctx = document.getElementById('kunjunganChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: <?= json_encode($values) ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: isDark ? '#fff' : '#000'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: isDark ? '#fff' : '#000'
                        }
                    }
                }
            }
        });

        // === DARK MODE TOGGLE ===
        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark');
            isDark = body.classList.contains('dark');

            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            toggleBtn.textContent = isDark ? "☀️ Light Mode" : "🌙 Dark Mode";

            // update chart warna
            chart.options.scales.x.ticks.color = isDark ? '#fff' : '#000';
            chart.options.scales.y.ticks.color = isDark ? '#fff' : '#000';
            chart.update();
        });

        // === SUBMENU ===
        document.querySelectorAll(".submenu-toggle").forEach(menu => {
            menu.addEventListener("click", function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle("open");

                const arrow = this.querySelector(".arrow");
                arrow.style.transform = parent.classList.contains("open") ?
                    "rotate(90deg)" :
                    "rotate(0deg)";
            });
        });
    </script>
</body>

</html>