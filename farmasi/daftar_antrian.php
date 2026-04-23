<?php
session_start();
include '../config/db.php';

// ✅ TEST SESSION DI SINI
var_dump($_SESSION);
die();

if (!isset($_SESSION['id_pasien'])) {
    die("❌ Session id_pasien tidak ada. Silakan login ulang.");
}

$id_pasien = $_SESSION['id_pasien'];

$stmt = $koneksi->prepare("
    INSERT INTO pendaftaran (id_pasien, tanggal_daftar, status)
    VALUES (?, NOW(), 'menunggu')
");

$stmt->bind_param("i", $id_pasien);

if (!$stmt->execute()) {
    die("Error: " . $stmt->error);
}

echo "✅ Pendaftaran berhasil";
