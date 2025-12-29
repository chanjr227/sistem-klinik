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

$id = (int) $_POST['id_konsultasi'];

/* ==========================
   AMBIL RESEP & STATUS
========================== */
$q = $koneksi->prepare("
    SELECT resep_obat, status_obat
    FROM riwayat_konsultasi
    WHERE id = ?
");
$q->bind_param('i', $id);
$q->execute();
$data = $q->get_result()->fetch_assoc();

if (!$data) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Data konsultasi tidak ditemukan'
    ]);
    exit;
}

if ($data['status_obat'] === 'selesai') {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Resep sudah diproses sebelumnya'
    ]);
    exit;
}

if (trim($data['resep_obat']) === '') {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Resep kosong'
    ]);
    exit;
}

$lines = explode("\n", trim($data['resep_obat']));
$total = 0;

/* ==========================
   TRANSAKSI
========================== */
$koneksi->begin_transaction();

try {

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;

        // Format: nama_obat jumlah
        if (!preg_match('/(.+)\s+(\d+)$/', $line, $match)) {
            throw new Exception("Format resep salah: $line");
        }

        $nama   = trim($match[1]);
        $jumlah = (int) $match[2];

        // ambil data obat
        $stmt = $koneksi->prepare("
            SELECT id_obat, stok, harga
            FROM obat
            WHERE nama_obat = ?
        ");
        $stmt->bind_param('s', $nama);
        $stmt->execute();
        $obat = $stmt->get_result()->fetch_assoc();

        if (!$obat) {
            throw new Exception("Obat \"$nama\" tidak ditemukan");
        }

        if ($obat['stok'] < $jumlah) {
            throw new Exception("Stok $nama tidak cukup");
        }

        // hitung subtotal
        $subtotal = $obat['harga'] * $jumlah;
        $total += $subtotal;

        // kurangi stok
        $upd = $koneksi->prepare("
            UPDATE obat
            SET stok = stok - ?
            WHERE id_obat = ?
        ");
        $upd->bind_param('ii', $jumlah, $obat['id_obat']);
        $upd->execute();
    }

    // tandai resep selesai
    $updStatus = $koneksi->prepare("
        UPDATE riwayat_konsultasi
        SET status_obat = 'selesai'
        WHERE id = ?
    ");
    $updStatus->bind_param('i', $id);
    $updStatus->execute();

    $koneksi->commit();

    echo json_encode([
        'status' => 'ok',
        'total'  => number_format($total, 0, ',', '.')
    ]);
} catch (Exception $e) {

    $koneksi->rollback();

    echo json_encode([
        'status' => 'error',
        'msg' => $e->getMessage()
    ]);
}
