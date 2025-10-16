<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../../config/db.php';

// Proses tambah data konsultasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $tanggal = $_POST['tanggal'];
    $diagnosa = $_POST['diagnosa'];
    $resep_obat = $_POST['resep_obat'];

    $stmt = $koneksi->prepare("INSERT INTO riwayat_konsultasi (id_pasien, id_dokter, tanggal, diagnosa, resep_obat) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $id_pasien, $id_dokter, $tanggal, $diagnosa, $resep_obat);
    $stmt->execute();
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Konsultasi - Klinik Sehat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/dashboard.css">
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-hospital-user"></i>
            <span>Klinik Sehat</span>
        </div>

        <ul class="sidebar-menu">
            <li><a href="../dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="../data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>
            <li><a href="../antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>

            <li class="has-submenu">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fa-solid fa-user-doctor"></i> Data Dokter
                    <i class="fa-solid fa-angle-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="../dokter/data_dokter.php">Lihat Data Dokter</a></li>
                </ul>
            </li>

            <li><a href="riwayat_konsultasi.php" class="active"><i class="fa-solid fa-stethoscope"></i> Riwayat Konsultasi</a></li>
            <li><a href="../tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
            <li><a href="../../laporan/index.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
            <li><a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🩺 Riwayat Konsultasi Pasien</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                + Tambah Riwayat
            </button>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">✅ Data konsultasi berhasil disimpan!</div>
        <?php endif; ?>

        <div class="card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Diagnosa</th>
                            <th>Resep Obat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ✅ Query SQL sudah diperbaiki sesuai struktur tabel
                        $sql = "
                            SELECT 
                                r.*, 
                                p.nama AS nama_pasien, 
                                d.nama AS nama_dokter, 
                                d.spesialisasi, 
                                d.jadwal_praktik
                            FROM riwayat_konsultasi r
                            LEFT JOIN pasien p ON r.id_pasien = p.id_pasien
                            LEFT JOIN dokter d ON r.id_dokter = d.id_dokter
                            ORDER BY r.id DESC
                        ";
                        $result = $koneksi->query($sql);
                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama_pasien']}</td>
                                    <td>{$row['nama_dokter']} ({$row['spesialisasi']})</td>
                                    <td>{$row['tanggal']}</td>
                                    <td>{$row['diagnosa']}</td>
                                    <td>{$row['resep_obat']}</td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada data konsultasi.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>