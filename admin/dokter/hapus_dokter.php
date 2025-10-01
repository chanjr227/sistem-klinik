<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../../config/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $koneksi->prepare("DELETE FROM dokter WHERE id_dokter = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: data_dokter.php");
    exit;
} else {
    echo "Gagal menghapus dokter.";
}
