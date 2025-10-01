<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../../config/db.php';

$id = $_GET['id'] ?? 0;
$result = $koneksi->query("SELECT * FROM dokter WHERE id_dokter = $id");
$dokter = $result->fetch_assoc();

if (!$dokter) {
    die("Dokter tidak ditemukan!");
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $jadwal = $_POST['jadwal_praktik'];

    $stmt = $koneksi->prepare("UPDATE dokter SET nama=?, spesialisasi=?, jadwal_praktik=? WHERE id_dokter=?");
    $stmt->bind_param("sssi", $nama, $spesialisasi, $jadwal, $id);

    if ($stmt->execute()) {
        $success = "Data dokter berhasil diperbarui!";
    } else {
        $error = "Gagal mengupdate data!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Dokter</title>
    <link rel="stylesheet" href="assets/form.css">
</head>

<body>
    <div class="form-admin">
        <h2>Edit Dokter</h2>
        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <form method="POST">
            <input type="text" name="nama" value="<?= htmlspecialchars($dokter['nama']) ?>" required>
            <input type="text" name="spesialisasi" value="<?= htmlspecialchars($dokter['spesialisasi']) ?>">
            <input type="text" name="jadwal_praktik" value="<?= htmlspecialchars($dokter['jadwal_praktik']) ?>">
            <button type="submit">Update</button>
        </form>
        <a href="data_dokter.php">⬅ Kembali</a>
    </div>
</body>

</html>