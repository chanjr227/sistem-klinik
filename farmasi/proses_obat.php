<?php
include '../config/db.php';
header('Content-Type: application/json');

if (!isset($_POST['id_konsultasi'])) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'ID konsultasi tidak ditemukan'
    ]);
    exit;
}

$id_konsultasi = intval($_POST['id_konsultasi']);

/* ===============================
   AMBIL RESEP DOKTER
=============================== */
$q = $koneksi->query("
    SELECT resep_obat
    FROM riwayat_konsultasi
    WHERE id = $id_konsultasi
");

$data = $q->fetch_assoc();

if (!$data) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Data konsultasi tidak ditemukan'
    ]);
    exit;
}

$resep = trim($data['resep_obat']);
if ($resep === '') {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Resep kosong'
    ]);
    exit;
}

/* ===============================
   SEMENTARA (BELUM PARSING OBAT)
=============================== */
echo json_encode([
    'status' => 'ok',
    'total' => 0
]);
