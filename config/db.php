<?php
$host = "localhost";     // ganti sesuai server
$user = "root";          // default XAMPP/MAMP
$pass = "";              // password MySQL (default kosong di XAMPP)
$db   = "klinik";        // nama database

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
