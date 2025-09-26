<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_dokter = $_POST['id_dokter'];

    $sql = "INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, alamat, no_hp, id_dokter, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sssssi", $nama, $tanggal_lahir, $jenis_kelamin, $alamat, $no_hp, $id_dokter);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = "Pendaftaran berhasil! Anda masuk dalam antrian.";
        $_SESSION['flash_type'] = "success"; // bisa 'success' atau 'error'
    } else {
        $_SESSION['flash_message'] = "Terjadi kesalahan: " . $koneksi->error;
        $_SESSION['flash_type'] = "error";
    }

    $stmt->close();
    $koneksi->close();

    header("Location: ../index.php");
    exit;
}
