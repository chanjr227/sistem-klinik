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
    <link rel="stylesheet" href="assets/dashboard.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Klinik Sehat</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">🏠 Dashboard</a></li>
                <li><a href="../pasien/list.php">👥 Data Pasien</a></li>
                <li><a href="../dokter/list.php">🩺 Data Dokter</a></li>
                <li><a href="../antrian/list.php">📋 Antrian</a></li>
                <li><a href="tambah_admin.php">Tambah akun</a></li>
                <li><a href="../laporan/index.php">📊 Laporan</a></li>
                <li><a href="../auth/logout.php" class="logout">🚪 Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <header class="content-header">
                <h1>Dashboard Admin</h1>
                <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?> 👋</p>
            </header>

            <section class="cards">
                <div class="card">
                    <h3>Total Pasien</h3>
                    <p class="number">120</p>
                </div>
                <div class="card">
                    <h3>Dokter Aktif</h3>
                    <p class="number">8</p>
                </div>
                <div class="card">
                    <h3>Antrian Hari Ini</h3>
                    <p class="number">35</p>
                </div>
            </section>

            <section class="chart-section">
                <h2>Statistik Kunjungan</h2>
                <div class="chart-placeholder">
                    <p>📊 Chart akan tampil di sini (gunakan Chart.js)</p>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.querySelector('.sidebar-header').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
    </script>
</body>

</html>