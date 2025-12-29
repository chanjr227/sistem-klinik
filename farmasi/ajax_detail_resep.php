<?php
include '../config/db.php';

$id = intval($_GET['id']);

$q = $koneksi->query("
    SELECT r.resep_obat, p.nama AS nama_pasien
    FROM riwayat_konsultasi r
    JOIN pasien_akun p ON r.id_pasien = p.id_pasien
    WHERE r.id = $id
");

$data = $q->fetch_assoc();
?>

<h5>👤 Pasien: <?= $data['nama_pasien'] ?></h5>

<hr>

<p><strong>Catatan Resep Dokter:</strong></p>
<div class="alert alert-info">
    <?= nl2br($data['resep_obat']) ?>
</div>

<a href="cetak_resep.php?id=<?= $id ?>" target="_blank" class="btn btn-success mb-2">
    🖨️ Cetak Resep
</a>

<form id="prosesForm">
    <input type="hidden" name="id_konsultasi" value="<?= $id ?>">
    <button type="submit" class="btn btn-warning">
        ⚙️ Proses Obat
    </button>
</form>

<div id="hasilProses" class="mt-3"></div>

<script>
    $(document).on('submit', '#prosesForm', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'proses_obat.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json', // ⬅️ PENTING
            success: function(data) {

                if (data.status === 'ok') {
                    $('#hasilProses').html(`
                    <div class="alert alert-success">
                        ✅ Obat berhasil diproses<br>
                        <strong>Total Harga:</strong> Rp ${data.total}
                    </div>
                `);
                } else {
                    $('#hasilProses').html(`
                    <div class="alert alert-danger">
                        ❌ ${data.msg}
                    </div>
                `);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                $('#hasilProses').html(`
                <div class="alert alert-danger">
                    ❌ Terjadi kesalahan server
                </div>
            `);
            }
        });
    });
</script>