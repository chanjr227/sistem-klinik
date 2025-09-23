<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Klinik Sehat - Sistem Manajemen Klinik</title>
    <link rel="stylesheet" href="assets/index.css">
</head>

<body>
    <header class="navbar">
        <div class="logo">
            <img src="assets/logo.png" alt="Logo Klinik">
            <h1>Klinik Sehat</h1>
        </div>
        <nav>
            <a href="index.php" class="active">Beranda</a>
            <a href="pasien/index.php">Daftar Pasien</a>
            <a href="pendaftaran/index.php">Cek Antrian</a>
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php">Dashboard Admin</a>
                <a href="auth/logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="login-btn">Login Admin</a>
            <?php endif; ?>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Selamat Datang di Klinik Sehat</h2>
            <p>Kami hadir untuk memberikan pelayanan kesehatan terbaik dengan sistem manajemen modern.</p>
            <div class="cta-buttons">
                <a href="pendaftaran/index.php" class="btn-primary">Daftar Antrian Pasien</a>
                <a href="auth/login.php" class="btn-secondary">Login Admin</a>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="feature-card">
            <h3>Pelayanan Cepat</h3>
            <p>Proses pendaftaran dan antrian lebih singkat dengan sistem online.</p>
        </div>
        <div class="feature-card">
            <h3>Manajemen Pasien</h3>
            <p>Data pasien tercatat dengan rapi dan mudah dicari.</p>
        </div>
        <div class="feature-card">
            <h3>Jadwal Dokter</h3>
            <p>Pasien bisa mengetahui jadwal dokter sebelum datang.</p>
        </div>
    </section>

    <footer>
        <p>&copy; <?= date("Y") ?> Klinik Sehat. Semua Hak Dilindungi.</p>
    </footer>
</body>

</html>