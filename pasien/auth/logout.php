<?php
session_start();

// Hapus semua session pasien
if (isset($_SESSION['pasien_id'])) {
    unset($_SESSION['pasien_id']);
    unset($_SESSION['pasien_nama']);
}

// Optional: hancurkan semua session
session_destroy();

// Flash message untuk info berhasil logout
session_start();
$_SESSION['flash_message'] = "Anda berhasil logout.";
$_SESSION['flash_type'] = "success";

// Arahkan ke halaman login
header("Location: login.php");
exit;
