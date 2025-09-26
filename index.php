<?php
session_start();
$isLoggedInAdmin = isset($_SESSION['user_id']);
$isLoggedInPasien = isset($_SESSION['pasien_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Klinik Sehat - Sistem Manajemen Klinik</title>
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/pendaftaran.css">
    <style>
        /* Responsif Modal */
        @media (max-width: 600px) {
            .modal-content {
                width: 95%;
                padding: 15px;
            }
        }

        /* Tambahan tombol batal */
        .btn-secondary {
            background: #ccc;
            color: #000;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #aaa;
        }

        /* Flash message */
        .alert {
            margin: 10px auto;
            padding: 10px;
            border-radius: 6px;
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="logo">
            <img src="assets/logo.png" alt="Logo Klinik">
            <h1>Klinik Sehat</h1>
        </div>
        <nav>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Beranda</a>
            <a href="pasien/cek_antrian.php" class="<?= basename($_SERVER['PHP_SELF']) == 'cek_antrian.php' ? 'active' : '' ?>">Cek Antrian</a>
            <a href="pasien/riwayat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : '' ?>">Riwayat Kunjungan</a>
            <a href="pasien/jadwal_dokter.php" class="<?= basename($_SERVER['PHP_SELF']) == 'jadwal_dokter.php' ? 'active' : '' ?>">Jadwal Dokter</a>

            <?php if ($isLoggedInPasien): ?>
                <span class="welcome">Halo, <?= htmlspecialchars($_SESSION['pasien_nama']) ?></span>
                <a href="pasien/auth/logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="pasien/auth/login.php" class="login-btn">Login Pasien</a>
            <?php endif; ?>

            <?php if ($isLoggedInAdmin): ?>
                <a href="dashboard.php">Dashboard Admin</a>
                <a href="auth/logout.php" class="logout-btn">Logout Admin</a>
            <?php else: ?>
                <a href="auth/login.php" class="login-btn">Login Admin</a>
            <?php endif; ?>

            <button class="toggle-dark" id="darkToggle">🌙</button>
        </nav>
    </header>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert <?= $_SESSION['flash_type'] ?? 'success' ?>">
            <?= $_SESSION['flash_message'] ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <section class="hero">
        <div class="hero-content">
            <h2>Selamat Datang di Klinik Sehat</h2>
            <p>Kami hadir untuk memberikan pelayanan kesehatan terbaik dengan sistem manajemen modern.</p>
            <div class="cta-buttons">
                <a href="#" class="btn-primary" id="openModalBtn">Daftar Antrian Pasien</a>
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

    <!-- Modal Pendaftaran -->
    <div class="modal" id="pendaftaranModal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Pendaftaran Pasien</h2>
            <p>Isi data berikut untuk mendaftar antrian konsultasi.</p>

            <form action="pasien/proses_pendaftaran.php" method="POST">
                <div class="form-group">
                    <label for="nama">Nama Pasien</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($_SESSION['pasien_nama'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" required></textarea>
                </div>

                <div class="form-group">
                    <label for="no_hp">No HP</label>
                    <input type="text" id="no_hp" name="no_hp" maxlength="15" pattern="[0-9]+" title="Hanya angka yang diperbolehkan" required>
                </div>

                <?php
                include 'config/db.php';
                $dokterQuery = $koneksi->query("SELECT id_dokter, nama, spesialisasi FROM dokter");
                ?>
                <div class="form-group">
                    <label for="dokter">Pilih Dokter</label>
                    <select id="dokter" name="id_dokter" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php if ($dokterQuery && $dokterQuery->num_rows > 0): ?>
                            <?php while ($dokter = $dokterQuery->fetch_assoc()): ?>
                                <option value="<?= $dokter['id_dokter'] ?>">
                                    <?= $dokter['nama'] ?> <?= $dokter['spesialisasi'] ? "- " . $dokter['spesialisasi'] : "" ?>
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option disabled>Tidak ada dokter tersedia</option>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="submit" class="btn-primary">Daftar Sekarang</button>
                <button type="button" class="btn-secondary" id="cancelModal">Batal</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date("Y") ?> Klinik Sehat. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        // Dark Mode Toggle
        const toggle = document.getElementById('darkToggle');
        const body = document.body;
        if (localStorage.getItem('dark-mode') === 'enabled') {
            body.classList.add('dark-mode');
            toggle.textContent = "☀️";
        }
        toggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('dark-mode', 'enabled');
                toggle.textContent = "☀️";
            } else {
                localStorage.setItem('dark-mode', 'disabled');
                toggle.textContent = "🌙";
            }
        });

        // Modal Logic
        const modal = document.getElementById("pendaftaranModal");
        const openBtn = document.getElementById("openModalBtn");
        const closeBtn = document.getElementById("closeModal");
        const cancelBtn = document.getElementById("cancelModal");
        const pasienLoggedIn = <?= $isLoggedInPasien ? 'true' : 'false' ?>;

        openBtn.addEventListener("click", (e) => {
            e.preventDefault();
            if (pasienLoggedIn) {
                modal.style.display = "flex";
                document.getElementById("nama").focus();
            } else {
                window.location.href = "pasien/auth/login.php";
            }
        });

        closeBtn.addEventListener("click", () => modal.style.display = "none");
        cancelBtn.addEventListener("click", () => modal.style.display = "none");

        window.addEventListener("click", (e) => {
            if (e.target === modal) modal.style.display = "none";
        });
    </script>
</body>

</html>