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
                    </ul>
                </li>

                <li><a href="antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="../laporan/index.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="content">
            <h1>Tambah Akun Admin</h1>
            <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

            <form method="POST" class="form-admin">
                <label>Nama</label>
                <input type="text" name="nama" required>

                <label>Username</label>
                <input type="text" name="username" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>Role</label>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
                </select>

                <button type="submit" class="btn-primary">Tambah Admin</button>
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