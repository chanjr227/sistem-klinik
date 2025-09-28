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
</head>

<body>
    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Klinik Sehat</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">🏠 Dashboard</a></li>
                <li><a href="../pasien/list.php">👥 Data Pasien</a></li>
                <li><a href="../dokter/list.php">🩺 Data Dokter</a></li>
                <li><a href="../antrian/list.php">📋 Antrian</a></li>
                <li><a href="tambah_admin.php" class="active">➕ Tambah Admin</a></li>
                <li><a href="../auth/logout.php" class="logout">🚪 Logout</a></li>
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
</body>

</html>