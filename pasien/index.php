<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../auth/session_check.php';

// Ambil semua pasien
$stmt = $pdo->query("SELECT * FROM pasien ORDER BY id_pasien DESC");
$pasien = $stmt->fetchAll();

// Hitung jumlah pasien
$stmt = $pdo->query("SELECT COUNT(*) as total FROM pasien");
$jumlah_pasien = $stmt->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Pasien</title>
    <link rel="stylesheet" href="/klinik/assets/css/style.css">
    <link rel="stylesheet" href="/klinik/assets/css/crud.css">
</head>

<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container">
        <h1>Dashboard Pasien</h1>
        <p><strong>Total Pasien:</strong> <?= $jumlah_pasien; ?></p>

        <a href="tambah.php" class="btn">Tambah Pasien</a>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>No HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pasien) > 0): ?>
                    <?php foreach ($pasien as $index => $p): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($p['nama']); ?></td>
                            <td><?= $p['tanggal_lahir']; ?></td>
                            <td><?= $p['jenis_kelamin']; ?></td>
                            <td><?= $p['no_hp']; ?></td>
                            <td>
                                <a href="edit.php?id=<?= $p['id_pasien']; ?>" class="btn">Edit</a>
                                <a href="hapus.php?id=<?= $p['id_pasien']; ?>" class="btn" onclick="return confirm('Hapus pasien ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Belum ada data pasien</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
```

---

## File: `pasien/tambah.php`

```php
<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../auth/session_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    $stmt = $pdo->prepare("INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, alamat, no_hp) VALUES (?,?,?,?,?)");
    $stmt->execute([$nama, $tanggal_lahir, $jenis_kelamin, $alamat, $no_hp]);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pasien</title>
    <link rel="stylesheet" href="/klinik/assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>Tambah Pasien</h1>
        <form method="POST">
            <label>Nama</label><br>
            <input type="text" name="nama" required><br><br>

            <label>Tanggal Lahir</label><br>
            <input type="date" name="tanggal_lahir"><br><br>

            <label>Jenis Kelamin</label><br>
            <select name="jenis_kelamin">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select><br><br>

            <label>No HP</label><br>
            <input type="text" name="no_hp"><br><br>

            <label>Alamat</label><br>
            <textarea name="alamat"></textarea><br><br>

            <button type="submit" class="btn">Simpan</button>
            <a href="index.php" class="btn">Batal</a>
        </form>
    </div>
</body>

</html>