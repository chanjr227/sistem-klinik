<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// Ambil data riwayat konsultasi
$sql = "
    SELECT 
        r.id, r.tanggal, r.diagnosa,
        p.nama AS nama_pasien,
        d.nama AS nama_dokter, d.spesialisasi
    FROM riwayat_konsultasi r
    LEFT JOIN pasien p ON r.id_pasien = p.id_pasien
    LEFT JOIN dokter d ON r.id_dokter = d.id_dokter
    ORDER BY r.id DESC
";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Resep Masuk - Farmasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../admin/assets/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>

    <div class="wrapper d-flex">

        <!-- ============ SIDEBAR ============= -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Klinik Sehat</span>
            </div>

            <ul class="sidebar-menu">
                <li><a href="../admin/dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="../admin/data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-user-doctor"></i> Data Dokter
                        <i class="fa-solid fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                        <li><a href="../admin/dokter/riwayat_konsultasi.php">Riwayat konsultasi</a></li>
                        <li><a href="../admin/dokter/tambah_dokter.php">Tambah Dokter</a></li>
                    </ul>
                </li>

                <li class="has-submenu open">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-prescription-bottle"></i> Farmasi
                        <i class="fa-solid fa-angle-right arrow" style="transform: rotate(90deg);"></i>
                    </a>
                    <ul class="submenu" style="display:block;">
                        <li><a href="../farmasi/resep.php" class="active"><i class="fa-solid fa-file-medical"></i> Resep Masuk</a></li>
                        <li><a href="../farmasi/obat.php"><i class="fa-solid fa-capsules"></i> Manajemen Obat</a></li>
                    </ul>
                </li>

                <li><a href="../admin/antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="../admin/tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="../admin/laporan.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="../auth/logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>
        <!-- ============ END SIDEBAR ============= -->

        <!-- ============ MAIN CONTENT ============= -->
        <main class="content flex-fill p-4">
            <header class="content-header mb-4">
                <h1>💊 Resep Masuk dari Dokter</h1>
                <p>Daftar resep hasil konsultasi pasien.</p>
            </header>

            <div class="card p-3">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Diagnosa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['nama_pasien'] ?></td>
                                    <td><?= $row['nama_dokter'] ?> (<?= $row['spesialisasi'] ?>)</td>
                                    <td><?= $row['tanggal'] ?></td>
                                    <td><?= $row['diagnosa'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary detail-btn" data-id="<?= $row['id'] ?>">
                                            <i class="fa-solid fa-eye"></i> Detail Resep
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada resep masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Detail Resep -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-file-medical"></i> Detail Resep</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <p class="text-center">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Submenu toggle
            $('.submenu-toggle').click(function(e) {
                e.preventDefault();
                var parent = $(this).parent();
                parent.toggleClass('open');
                var submenu = parent.find('.submenu');
                var arrow = $(this).find('.arrow');
                if (parent.hasClass('open')) {
                    submenu.show();
                    arrow.css('transform', 'rotate(90deg)');
                } else {
                    submenu.hide();
                    arrow.css('transform', 'rotate(0deg)');
                }
            });

            // Tombol Detail Resep
            $('.detail-btn').click(function() {
                var id = $(this).data('id');
                $('#modalBody').html('<p class="text-center">Memuat data...</p>');
                var myModal = new bootstrap.Modal(document.getElementById('detailModal'), {});
                myModal.show();

                $.ajax({
                    url: 'ajax_detail_resep.php',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('#modalBody').html(response);
                    },
                    error: function() {
                        $('#modalBody').html('<p class="text-danger text-center">Gagal memuat data.</p>');
                    }
                });
            });
        });
    </script>

</body>

</html>