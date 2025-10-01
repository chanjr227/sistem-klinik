<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../../config/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $jadwal = $_POST['jadwal_praktik'];

    $stmt = $koneksi->prepare("INSERT INTO dokter (nama, spesialisasi, jadwal_praktik) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $spesialisasi, $jadwal);

    if ($stmt->execute()) {
        $success = "Dokter berhasil ditambahkan!";
    } else {
        $error = "Gagal menambah dokter: " . $koneksi->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Dokter</title>
    <link rel="stylesheet" href="assets/form.css">
</head>

<body>
    <div class="form-admin">
        <h2>Tambah Dokter</h2>
        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Dokter" required>
            <input type="text" name="spesialisasi" placeholder="Spesialisasi">
            <input type="text" name="jadwal_praktik" placeholder="Jadwal Praktik (contoh: Senin - Rabu 09:00 - 12:00)">
            <button type="submit">Simpan</button>
        </form>
        <a href="data_dokter.php">⬅ Kembali</a>
    </div>
</body>

</html>