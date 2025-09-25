<?php
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
        echo "<script>alert('Pendaftaran berhasil!'); window.location.href='../index.php';</script>";
    } else {
        echo "Terjadi kesalahan: " . $koneksi->error;
    }

    $stmt->close();
    $koneksi->close();
}
