<?php
session_start();
require_once '../config/db.php';

// Pastikan hanya admin login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Cek username unik
    $cek = $koneksi->prepare("SELECT id_admin FROM admin WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $result = $cek->get_result();

    if ($result && $result->num_rows > 0) {
        $error = "Username sudah dipakai, pilih username lain.";
    } else {
        $stmt = $koneksi->prepare("INSERT INTO admin (nama, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $username, $password, $role);

        if ($stmt->execute()) {
            $success = "Akun admin berhasil dibuat!";
        } else {
            $error = "Terjadi kesalahan saat membuat akun.";
        }
        $stmt->close();
    }
    $cek->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Admin</title>
    <link rel="stylesheet" href="assets/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            margin: 0;
        }

        /* Sidebar submenu */
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

        /* === TAMBAH ADMIN FORM === */
        .content-header {
            text-align: center;
            margin-top: 2rem;
        }

        .content-header h1 {
            font-size: 1.8rem;
            color: #1e293b;
        }

        .content-header p {
            color: #64748b;
            margin-top: 0.3rem;
        }

        .form-admin {
            background: #ffffff;
            max-width: 480px;
            margin: 2rem auto;
            padding: 2rem 2.5rem;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .input-group {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
            outline: none;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 6px rgba(59, 130, 246, 0.3);
        }

        .btn-primary {
            width: 100%;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        /* Alert messages */
        .success,
        .error {
            max-width: 480px;
            margin: 1rem auto;
            padding: 0.9rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
        }

        .success {
            background: #e8f9ee;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .error {
            background: #fdecea;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        @media (max-width: 600px) {
            .form-admin {
                margin: 1.5rem;
                padding: 1.5rem;
            }
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
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-prescription-bottle"></i>
                        Data Obat
                        <i class="fa-solid fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="../farmasi/resep.php" class="active"><i class="fa-solid fa-file-medical"></i> Resep Masuk</a></li>
                        <li><a href="../farmasi/obat.php" class="active"><i class="fa-solid fa-capsules"></i> Manajemen Obat</a></li>
                    </ul>
                </li>


                <li><a href="antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="laporan.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>
        <main class="content">
            <div class="content-header">
                <h1><i class="fa-solid fa-user-plus"></i> Tambah Akun Admin</h1>
                <p>Isi formulir berikut untuk membuat akun admin baru.</p>
            </div>

            <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

            <form method="POST" class="form-admin">
                <div class="input-group">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-user-gear"></i>
                    <select name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Admin
                </button>
            </form>
        </main>
    </div>

    <script>
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
    </script>
</body>

</html>