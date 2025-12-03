<?php
include '../config/db.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM obat WHERE id_obat='$id'");

if ($query) {
    header("Location: obat.php?msg=deleted");
    exit;
} else {
    echo "Gagal menghapus data.";
}
