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
    <link rel="stylesheet" href="assets/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <div>
                    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?></h1>
                    <p>Kelola klinik Anda dengan mudah dari dashboard ini.</p>
                    <button id="darkToggle" class="dark-btn">🌙 Dark Mode</button>
                </div>
            </header>

            <section class="cards">
                <div class="card blue">
                    <h3>Total Pasien</h3>
                    <p class="number">120</p>
                </div>
                <div class="card green">
                    <h3>Dokter Aktif</h3>
                    <p class="number">8</p>
                </div>
                <div class="card orange">
                    <h3>Antrian Hari Ini</h3>
                    <p class="number">35</p>
                </div>
            </section>

            <section class="chart-section">
                <h2>📊 Statistik Kunjungan</h2>
                <canvas id="kunjunganChart"></canvas>
            </section>
        </main>
    </div>

    <script>
        // Sidebar toggle (mobile)
        document.querySelector('.sidebar-header').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Chart.js Example
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

        const body = document.body;
        const toggleBtn = document.getElementById('darkToggle');

        // Dark mode toggle
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