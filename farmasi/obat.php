<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

$query = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY id_obat DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Obat - Klinik Sehat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS Dashboard -->
    <link rel="stylesheet" href="../admin/assets/dashboard.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="wrapper">

        <!-- Sidebar -->
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

                <li><a href="../farmasi/obat.php" class="active"><i class="fa-solid fa-capsules"></i> Menu Obat</a></li>

                <li><a href="../admin/antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="../admin/tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="../admin/laporan.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="../admin/logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <header class="content-header">
                <h1>Data Obat</h1>
                <p>Manajemen stok & harga obat.</p>
            </header>

            <div class="card p-3">

                <!-- Tombol Tambah -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    + Tambah Obat
                </button>

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Obat</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?= $row['id_obat'] ?></td>
                                <td><?= $row['nama_obat'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td>Rp <?= number_format($row['harga'], 2, ',', '.') ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit"
                                        data-id="<?= $row['id_obat'] ?>"
                                        data-nama="<?= $row['nama_obat'] ?>"
                                        data-stok="<?= $row['stok'] ?>"
                                        data-harga="<?= $row['harga'] ?>">
                                        Edit
                                    </button>

                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapus"
                                        data-id="<?= $row['id_obat'] ?>"
                                        data-nama="<?= $row['nama_obat'] ?>">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </main>

    </div>

    <!-- ================== MODAL TAMBAH ================== -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="obat_add.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Obat</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Nama Obat</label>
                        <input type="text" name="nama_obat" class="form-control" required>

                        <label class="mt-2">Stok</label>
                        <input type="number" name="stok" class="form-control" required>

                        <label class="mt-2">Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ================== MODAL EDIT ================== -->
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="obat_edit.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Obat</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">

                        <label>Nama Obat</label>
                        <input type="text" id="edit_nama" name="nama_obat" class="form-control" required>

                        <label class="mt-2">Stok</label>
                        <input type="number" id="edit_stok" name="stok" class="form-control" required>

                        <label class="mt-2">Harga</label>
                        <input type="number" id="edit_harga" name="harga" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ================== MODAL HAPUS ================== -->
    <div class="modal fade" id="modalHapus">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="obat_delete.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Obat</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hapus_id" name="id">
                        <p>Hapus obat: <b id="hapus_nama"></b> ?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger">Hapus</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Script Submenu -->
    <script>
        document.querySelectorAll(".submenu-toggle").forEach(menu => {
            menu.addEventListener("click", function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle("open");

                const arrow = this.querySelector(".arrow");
                arrow.style.transform = parent.classList.contains("open") ? "rotate(90deg)" : "rotate(0deg)";
            });
        });

        // Pass data ke modal edit
        $('#modalEdit').on('show.bs.modal', function(e) {
            let btn = $(e.relatedTarget);
            $('#edit_id').val(btn.data('id'));
            $('#edit_nama').val(btn.data('nama'));
            $('#edit_stok').val(btn.data('stok'));
            $('#edit_harga').val(btn.data('harga'));
        });

        // Pass data ke modal hapus
        $('#modalHapus').on('show.bs.modal', function(e) {
            let btn = $(e.relatedTarget);
            $('#hapus_id').val(btn.data('id'));
            $('#hapus_nama').text(btn.data('nama'));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>