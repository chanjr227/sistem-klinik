<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
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
                <li><a href="dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

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

                <li><a href="antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="../laporan/index.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
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
                        <p class="number">120</p>
                    </div>
                </div>

                <div class="card green d-flex align-items-center">
                    <i class="fa-solid fa-user-doctor fa-2x me-3"></i>
                    <div>
                        <h3>Dokter Aktif</h3>
                        <p class="number">8</p>
                    </div>
                </div>

                <div class="card orange d-flex align-items-center">
                    <i class="fa-solid fa-list-check fa-2x me-3"></i>
                    <div>
                        <h3>Antrian Hari Ini</h3>
                        <p class="number">35</p>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script>
        // === DARK MODE TOGGLE ===
        const body = document.body;
        const toggleBtn = document.getElementById('darkToggle');

        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark');
            toggleBtn.textContent = "☀️ Light Mode";
        }

        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark');
            if (body.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                toggleBtn.textContent = "☀️ Light Mode";
            } else {
                localStorage.setItem('theme', 'light');
                toggleBtn.textContent = "🌙 Dark Mode";
            }
        });

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

        // === CHART.JS ===
        const ctx = document.getElementById('kunjunganChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: [15, 25, 20, 30, 40, 35, 28],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
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
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>