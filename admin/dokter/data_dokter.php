<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../../config/db.php';

$user_id = $_SESSION['user_id'];
$query = $koneksi->query("SELECT * FROM admin WHERE id_admin = '$user_id'");
$user = $query->fetch_assoc();

// jika bukan superadmin
$akses_ditolak = false;
if (!$user || $user['role'] !== 'superadmin') {
    $akses_ditolak = true;
}
?>
<!DOCTYPE html>
<html lang='id'>

<head>
    <meta charset='UTF-8'>
    <title>Data Dokter - Klinik Sehat</title>
    <link rel='stylesheet' href='../assets/dashboard.css'>
    <link rel='stylesheet' href='../assets/dokter.css'>
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css'>
    <style>
        /* === Modal Notifikasi Akses Ditolak === */
        .akses-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .akses-modal-content {
            background: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            animation: popIn 0.3s ease;
        }

        @keyframes popIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .akses-modal-content i {
            font-size: 50px;
            color: #ef4444;
            margin-bottom: 15px;
        }

        .akses-modal-content h2 {
            color: #dc2626;
            margin-bottom: 10px;
        }

        .akses-modal-content p {
            color: #475569;
            font-size: 15px;
            margin-bottom: 20px;
        }

        .akses-modal-content button {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .akses-modal-content button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>

<body>
    <div class='wrapper'>
        <aside class='sidebar'>
            <div class='sidebar-header'>
                <i class='fa-solid fa-hospital-user'></i>
                <span>Klinik Sehat</span>
            </div>

            <ul class='sidebar-menu'>
                <li><a href='../dashboard.php'><i class='fa-solid fa-gauge'></i> Dashboard</a></li>
                <li><a href='../data_pasien.php'><i class='fa-solid fa-users'></i> Data Pasien</a></li>
                <li><a href='data_dokter.php' class='active'><i class='fa-solid fa-user-doctor'></i> Data Dokter</a></li>
                <li><a href='../antrian_pasien.php'><i class='fa-solid fa-list'></i> Antrian</a></li>
                <li><a href='../laporan.php'><i class='fa-solid fa-file-lines'></i> Laporan</a></li>
                <li><a href='../logout.php' class='logout'><i class='fa-solid fa-right-from-bracket'></i> Logout</a></li>
            </ul>
        </aside>

        <main class='content'>
            <header class='content-header'>
                <h1>Data Dokter</h1>
            </header>

            <section class='table-section'>
                <table class='data-table'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Spesialisasi</th>
                            <th>Jadwal Praktik</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan='5' style='text-align:center;'>Data dokter akan tampil di sini...</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <!-- Modal Notifikasi Akses Ditolak -->
    <div class='akses-modal' id='aksesModal'>
        <div class='akses-modal-content'>
            <i class='fa-solid fa-triangle-exclamation'></i>
            <h2>Akses Ditolak</h2>
            <p>Halaman ini hanya dapat diakses oleh <b>Superadmin</b>.</p>
            <button onclick="window.location.href='../dashboard.php'">Kembali ke Dashboard</button>
        </div>
    </div>

    <script>
        <?php if ($akses_ditolak): ?>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("aksesModal").style.display = "flex";
            });
        <?php endif; ?>
    </script>
</body>

</html>