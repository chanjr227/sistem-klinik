<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/db.php';

// ====================== HANDLE TAMBAH ======================
if (isset($_POST['tambah_obat'])) {
    $nama = $_POST['nama_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = mysqli_query($koneksi, "INSERT INTO obat (nama_obat, stok, harga) VALUES ('$nama', '$stok', '$harga')");
    if ($query) {
        $notif = "tambah_success";
    } else {
        $notif = "tambah_fail";
    }
}

// ====================== HANDLE EDIT ======================
if (isset($_POST['edit_obat'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = mysqli_query($koneksi, "UPDATE obat SET nama_obat='$nama', stok='$stok', harga='$harga' WHERE id_obat='$id'");
    if ($query) {
        $notif = "edit_success";
    } else {
        $notif = "edit_fail";
    }
}

// ====================== HANDLE DELETE ======================
if (isset($_POST['hapus_obat'])) {
    $id = $_POST['id'];

    $query = mysqli_query($koneksi, "DELETE FROM obat WHERE id_obat='$id'");
    if ($query) {
        $notif = "hapus_success";
    } else {
        $notif = "hapus_fail";
    }
}

// Ambil data obat
$data = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY id_obat DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Obat - Klinik Sehat</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="../admin/assets/dashboard.css">
</head>

<body>
    <div class="wrapper">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Klinik Sehat</span>
            </div>

            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="data_pasien.php"><i class="fa-solid fa-users"></i> Data Pasien</a></li>

                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">
                        <i class="fa-solid fa-user-doctor"></i>
                        Data Dokter
                        <i class="fa-solid fa-angle-right arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="../admin/dokter/data_dokter.php">Lihat Data Dokter</a></li>
                        <li><a href="../admin/dokter/riwayat_konsultasi.php">Riwayat konsultasi</a></li>
                        <li><a href="../admin/dokter/tambah_dokter.php">Tambah Dokter</a></li>
                    </ul>
                </li>

                <li><a href="../farmasi/obat.php" class="active"><i class="fa-solid fa-capsules"></i> Menu Obat</a></li>

                <li><a href="antrian_pasien.php"><i class="fa-solid fa-list"></i> Antrian</a></li>
                <li><a href="tambah_admin.php"><i class="fa-solid fa-user-plus"></i> Tambah Akun</a></li>
                <li><a href="laporan.php"><i class="fa-solid fa-file-lines"></i> Laporan</a></li>
                <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content">
            <header class="content-header">
                <h1>Data Obat</h1>
                <p>Manajemen stok & harga obat.</p>
            </header>

            <div class="card p-3">

                <!-- NOTIFIKASI -->
                <?php if (isset($notif)) { ?>
                    <?php if ($notif == "tambah_success") { ?>
                        <div class="alert alert-success">Obat berhasil ditambah!</div>
                    <?php } elseif ($notif == "edit_success") { ?>
                        <div class="alert alert-warning">Data obat berhasil diperbarui!</div>
                    <?php } elseif ($notif == "hapus_success") { ?>
                        <div class="alert alert-danger">Obat berhasil dihapus!</div>
                    <?php } else { ?>
                        <div class="alert alert-danger">Terjadi kesalahan, coba lagi!</div>
                    <?php } ?>
                <?php } ?>

                <!-- TOMBOL TAMBAH -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    + Tambah Obat
                </button>

                <!-- TABLE -->
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Obat</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                            <tr>
                                <td><?= $row['id_obat'] ?></td>
                                <td><?= $row['nama_obat'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>

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

    <!-- ====================== MODAL TAMBAH ====================== -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="post">
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
                        <button class="btn btn-primary" name="tambah_obat">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ====================== MODAL EDIT ====================== -->
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="post">
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
                        <button class="btn btn-warning" name="edit_obat">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ====================== MODAL HAPUS ====================== -->
    <div class="modal fade" id="modalHapus">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Obat</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="hapus_id" name="id">
                        <p>Hapus obat <b id="hapus_nama"></b> ?</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger" name="hapus_obat">Hapus</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Script Modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#modalEdit').on('show.bs.modal', function(e) {
            let btn = $(e.relatedTarget);
            $('#edit_id').val(btn.data('id'));
            $('#edit_nama').val(btn.data('nama'));
            $('#edit_stok').val(btn.data('stok'));
            $('#edit_harga').val(btn.data('harga'));
        });

        $('#modalHapus').on('show.bs.modal', function(e) {
            let btn = $(e.relatedTarget);
            $('#hapus_id').val(btn.data('id'));
            $('#hapus_nama').text(btn.data('nama'));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>