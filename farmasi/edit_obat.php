<?php
include '../config/db.php';

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM obat WHERE id_obat = '$id'");
$obat = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama = $_POST['nama_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = mysqli_query($koneksi, "UPDATE obat SET 
                                     nama_obat='$nama', 
                                     stok='$stok', 
                                     harga='$harga'
                                     WHERE id_obat='$id'");

    if ($query) {
        header("Location: obat.php?msg=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Obat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3>Edit Obat</h3>

        <form method="POST">
            <div class="mb-3">
                <label>Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control" value="<?= $obat['nama_obat'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= $obat['stok'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Harga</label>
                <input type="number" step="0.01" name="harga" class="form-control" value="<?= $obat['harga'] ?>" required>
            </div>

            <button type="submit" name="update" class="btn btn-warning">Update</button>
            <a href="obat.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

</body>

</html>