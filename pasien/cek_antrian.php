<?php
require '../config/db.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cek Antrian Pasien</title>
    <link rel="stylesheet" href="../assets/index.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #007bff;
        }

        .refresh-info {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .refresh-info span {
            font-weight: bold;
            color: #007bff;
        }

        .refresh-btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table th,
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #007bff;
            color: white;
            font-size: 15px;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Antrian Pasien</h2>

        <div class="refresh-info">
            Terakhir diperbarui: <span id="last-refresh">Memuat...</span>
        </div>

        <div style="text-align:center;">
            <button class="refresh-btn" onclick="loadAntrian()">🔄 Refresh Sekarang</button>
        </div>

        <div id="tabel-antrian">
            <p class="no-data">Memuat data antrian...</p>
        </div>
    </div>

    <script>
        function formatTime(date) {
            const h = String(date.getHours()).padStart(2, '0');
            const m = String(date.getMinutes()).padStart(2, '0');
            const s = String(date.getSeconds()).padStart(2, '0');
            return `${h}:${m}:${s}`;
        }

        function loadAntrian() {
            fetch('load_antrian.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('tabel-antrian').innerHTML = data;

                    const now = new Date();
                    document.getElementById('last-refresh').textContent = formatTime(now);
                })
                .catch(err => {
                    document.getElementById('tabel-antrian').innerHTML =
                        "<p class='no-data'>Gagal memuat data.</p>";
                });
        }

        loadAntrian();
        setInterval(loadAntrian, 180000); // 3 menit
    </script>
</body>

</html>