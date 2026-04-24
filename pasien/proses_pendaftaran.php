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

    //SIMPAN PASIEN
    $stmt = $koneksi->prepare("
        INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, alamat, no_hp, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("sssss", $nama, $tanggal_lahir, $jenis_kelamin, $alamat, $no_hp);
    $stmt->execute();

    // ambil id pasien baru
    $id_pasien = $stmt->insert_id;

    //SIMPAN PENDAFTARAN
    $stmt2 = $koneksi->prepare("
        INSERT INTO pendaftaran (id_pasien, id_dokter, tanggal_daftar, status)
        VALUES (?, ?, NOW(), 'menunggu')
    ");
    $stmt2->bind_param("ii", $id_pasien, $id_dokter);
    $stmt2->execute();

    $_SESSION['flash_message'] = "Pendaftaran berhasil! Anda masuk dalam antrian.";
    $_SESSION['flash_type'] = "success";

    header("Location: ../index.php");
    exit;
}
