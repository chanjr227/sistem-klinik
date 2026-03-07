<?php
session_start();
include '../config/db.php';

$id_pasien = $_SESSION['id_pasien']; // dari login pasien

$stmt = $koneksi->prepare("
    INSERT INTO pendaftaran (id_pasien, tanggal_daftar, status)
    VALUES (?, CURDATE(), 'menunggu')
");
$stmt->bind_param("i", $id_pasien);
$stmt->execute();

header("Location: dashboard_pasien.php");
exit;
