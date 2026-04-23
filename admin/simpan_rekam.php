<?php
include '../config/db.php';

$id_pasien = $_POST['id_pasien'];
$diagnosa = $_POST['diagnosa'];
$tindakan = $_POST['tindakan'];
$catatan = $_POST['catatan'];
$tanggal = date('Y-m-d');

// 🔥 AMBIL ID DOKTER DARI DATABASE
$get = $koneksi->prepare("SELECT id_dokter FROM pasien WHERE id_pasien = ?");
$get->bind_param("i", $id_pasien);
$get->execute();
$res = $get->get_result();
$data = $res->fetch_assoc();

$id_dokter = $data['id_dokter'];

if (!$id_dokter) {
    die("Dokter tidak ditemukan!");
}

// 1. INSERT KE PENDAFTARAN
$stmt0 = $koneksi->prepare("
    INSERT INTO pendaftaran (id_pasien, id_dokter, tanggal_daftar, status) 
    VALUES (?, ?, NOW(), 'menunggu')
");
$stmt0->bind_param("ii", $id_pasien, $id_dokter);
$stmt0->execute();

$id_pendaftaran = $stmt0->insert_id;

// 2. INSERT KE RIWAYAT
$stmt1 = $koneksi->prepare("INSERT INTO riwayat_konsultasi (id_pasien, tanggal) VALUES (?, ?)");
$stmt1->bind_param("is", $id_pasien, $tanggal);
$stmt1->execute();

// 3. INSERT KE REKAM MEDIS
$stmt2 = $koneksi->prepare("
    INSERT INTO rekam_medis (id_pendaftaran, diagnosa, tindakan, catatan) 
    VALUES (?, ?, ?, ?)
");
$stmt2->bind_param("isss", $id_pendaftaran, $diagnosa, $tindakan, $catatan);
$stmt2->execute();

header("Location: data_pasien.php");
