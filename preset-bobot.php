<?php
require_once('includes/init.php');
cek_login($role = array(1));

$page = "Preset Bobot";
require_once('template/header.php');
include 'preset-bobot-form.php';

?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Data Preset Bobot</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Preset Bobot</li>
            </ol>
        </nav>
    </div>
    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">+ Buat Preset</a>
</div>

<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = '';
switch ($status):
    case 'sukses-baru':
        $msg = 'Data preset berhasil disimpan';
        break;
    case 'sukses-hapus':
        $msg = 'Data preset berhasil dihapus';
        break;
    case 'sukses-edit':
        $msg = 'Data preset berhasil diupdate';
        break;
    case 'gagal-edit':
        $msg = 'Gagal mengupdate preset bobot!';
        break;
    case 'nama-sama':
        $msg = 'Nama preset sudah ada!';
        break;
    case 'gagal-tambah':
        $msg = 'Gagal menambahkan preset bobot!';
        break;
endswitch;

if ($msg):
    echo '<div class="alert alert-info">' . $msg . '</div>';
endif;
?>

<!-- Tabel Preset Bobot -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title text-center m-0">Daftar Preset Bobot</h5>
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead class="text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Preset</th>
                        <th width="15%">Harga</th>
                        <th width="15%">Stok</th>
                        <th width="15%">Min. Pembelian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = "SELECT * FROM preset_bobot ORDER BY id_preset ASC";
                    $result = mysqli_query($koneksi, $query);
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td align="center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_preset']) ?></td>
                            <td align="center"><?= $row['harga'] ?></td>
                            <td align="center"><?= $row['stok'] ?></td>
                            <td align="center"><?= $row['minimal_pembelian'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="<?= $row['id_preset'] ?>"
                                        data-nama="<?= htmlspecialchars($row['nama_preset']) ?>"
                                        data-harga="<?= number_format($row['harga'], 1) ?>"
                                        data-stok="<?= number_format($row['stok'], 1) ?>"
                                        data-minimal="<?= number_format($row['minimal_pembelian'], 1) ?>"
                                        data-status="<?= $row['status'] ?>" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <a href="hapus-preset.php?id=<?= $row['id_preset'] ?>" class="btn btn-sm btn-danger"
                                        data-bs-toggle="tooltip" title="Hapus Data"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus preset bobot ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalElements = {
            harga: document.getElementById('harga'),
            stok: document.getElementById('stok'),
            minimal: document.getElementById('minimal_pembelian'),
            hargaVal: document.getElementById('harga_val'),
            stokVal: document.getElementById('stok_val'),
            minimalVal: document.getElementById('minimal_val'),
            alertDiv: document.getElementById('bobotAlert'),
            totalSpan: document.getElementById('currentTotal')
        };

        const updateSliderValues = (harga, stok, minimal, hargaVal, stokVal, minimalVal, alertDiv, totalSpan) => {
            hargaVal.textContent = parseFloat(harga.value).toFixed(1);
            stokVal.textContent = parseFloat(stok.value).toFixed(1);
            minimalVal.textContent = parseFloat(minimal.value).toFixed(1);
            const total = parseFloat(harga.value) + parseFloat(stok.value) + parseFloat(minimal.value);
            totalSpan.textContent = total.toFixed(1);
            alertDiv.style.display = (Math.abs(total - 1.0) > 0.001) ? "block" : "none";
        };

        [modalElements.harga, modalElements.stok, modalElements.minimal].forEach(input =>
            input.addEventListener('input', () => updateSliderValues(modalElements.harga, modalElements.stok, modalElements.minimal, modalElements.hargaVal, modalElements.stokVal, modalElements.minimalVal, modalElements.alertDiv, modalElements.totalSpan))
        );

        document.getElementById("formPreset").addEventListener("submit", function (e) {
            const total = parseFloat(modalElements.harga.value) + parseFloat(modalElements.stok.value) + parseFloat(modalElements.minimal.value);
            if (Math.abs(total - 1.0) > 0.001) {
                e.preventDefault();
                updateSliderValues(modalElements.harga, modalElements.stok, modalElements.minimal, modalElements.hargaVal, modalElements.stokVal, modalElements.minimalVal, modalElements.alertDiv, modalElements.totalSpan);
            }
        });

        const setModalValues = (button, modalElements) => {
            const id = button.getAttribute('data-id');
            const harga = button.getAttribute('data-harga');
            const stok = button.getAttribute('data-stok');
            const minimal = button.getAttribute('data-minimal');
            const status = button.getAttribute('data-status');

            document.getElementById('edit_id_preset').value = id;
            document.getElementById('edit_nama_preset').value = button.getAttribute('data-nama');
            document.getElementById('edit_status').value = status;

            const editElements = {
                harga: document.getElementById('edit_harga'),
                stok: document.getElementById('edit_stok'),
                minimal: document.getElementById('edit_minimal_pembelian'),
                hargaVal: document.getElementById('edit_harga_val'),
                stokVal: document.getElementById('edit_stok_val'),
                minimalVal: document.getElementById('edit_min_val')
            };

            [editElements.harga, editElements.stok, editElements.minimal].forEach((slider, index) => {
                slider.value = [harga, stok, minimal][index];
                [editElements.hargaVal, editElements.stokVal, editElements.minimalVal][index].textContent = parseFloat([harga, stok, minimal][index]).toFixed(1);
            });

            updateSliderValues(editElements.harga, editElements.stok, editElements.minimal, editElements.hargaVal, editElements.stokVal, editElements.minimalVal, modalElements.alertDiv, modalElements.totalSpan);
        };

        document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
            setModalValues(event.relatedTarget, modalElements);
        });

        [document.getElementById('edit_harga'), document.getElementById('edit_stok'), document.getElementById('edit_minimal_pembelian')].forEach(input =>
            input.addEventListener('input', () => updateSliderValues(document.getElementById('edit_harga'), document.getElementById('edit_stok'), document.getElementById('edit_minimal_pembelian'), document.getElementById('edit_harga_val'), document.getElementById('edit_stok_val'), document.getElementById('edit_min_val'), modalElements.alertDiv, modalElements.totalSpan))
        );
    });
</script>



<?php
require_once('template/footer.php');
?>