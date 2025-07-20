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
$type = '';
$msg = '';

switch ($status):
    case 'sukses-baru':
        $msg = 'Data preset berhasil disimpan';
        $type = 'success';
        break;
    case 'sukses-hapus':
        $msg = 'Data preset berhasil dihapus';
        $type = 'success';
        break;
    case 'sukses-edit':
        $msg = 'Data preset berhasil diupdate';
        $type = 'success';
        break;
    case 'gagal-edit':
        $msg = 'Gagal mengupdate preset bobot!';
        $type = 'danger';
        break;
    case 'nama-sama':
        $msg = 'Nama preset sudah ada!';
        $type = 'danger';
        break;
    case 'bobot-sama':
        $msg = 'Kombinasi nilai bobot sudah ada!';
        $type = 'danger';
        break;
    case 'gagal-tambah':
        $msg = 'Gagal menambahkan preset bobot!';
        $type = 'danger';
        break;
endswitch;

if (!empty($msg)):
    ?>
    <div class="alert alert-<?= $type ?> alert-dismissible fade show mt-3" role="alert">
        <?= $msg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


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
                        <th width="15%">Harga <small>(Cost)</small></th>
                        <th width="15%">Stok <small>(Benefit)</small></th>
                        <th width="20%">Min.Pembelian <small>(Cost)</small></th>
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

<?php
require_once('template/footer.php');
?>