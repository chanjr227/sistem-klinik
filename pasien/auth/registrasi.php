<?php
session_start();
require_once '../../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek apakah email sudah dipakai
    $cek = $koneksi->prepare("SELECT id_pasien FROM pasien_akun WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $error = "Email sudah terdaftar, silakan login.";
    } else {
        $stmt = $koneksi->prepare("INSERT INTO pasien_akun (nama, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['pasien_id'] = $stmt->insert_id;
            $_SESSION['pasien_nama'] = $nama;
            header("Location: ../../index.php");
            exit;
        } else {
            $error = "Terjadi kesalahan. Coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Pasien</title>
    <link rel="stylesheet" href="../../assets/auth.css">
</head>

<body>
    <div class="auth-container">
        <h2>Daftar Akun Pasien</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</body>

</html>