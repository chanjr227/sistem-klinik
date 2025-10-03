<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_dokter'] ?? 0;
    $nama = $_POST['nama'] ?? '';
    $spesialisasi = $_POST['spesialisasi'] ?? '';
    $jadwal = $_POST['jadwal_praktik'] ?? '';

    if ($id && $nama) {
        $stmt = $koneksi->prepare("UPDATE dokter SET nama=?, spesialisasi=?, jadwal_praktik=? WHERE id_dokter=?");
        $stmt->bind_param("sssi", $nama, $spesialisasi, $jadwal, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Data dokter berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate data dokter!";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Data tidak valid!";
    }
} else {
    $_SESSION['error'] = "Akses tidak sah!";
}

header("Location: data_dokter.php");
exit;
