<?php
include '../config/db.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_obat'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = mysqli_query($koneksi, "INSERT INTO obat (nama_obat, stok, harga) 
                                     VALUES ('$nama', '$stok', '$harga')");

    if ($query) {
        header("Location: obat.php?msg=added");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Obat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3>Tambah Obat</h3>

        <form method="POST">
            <div class="mb-3">
                <label>Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Harga</label>
                <input type="number" step="0.01" name="harga" class="form-control" required>
            </div>

            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="obat.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

</body>

</html>