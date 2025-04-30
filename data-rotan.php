<?php
require_once('includes/init.php');
cek_login($role = array(2));

$id_supplier = $_SESSION['id_supplier'];
$page = "data_rotan";
require_once('template/header.php');

// Pastikan session id_supplier sudah ada
if (!isset($_SESSION['id_supplier'])) {
    die('Session id_supplier tidak ditemukan bos!');
}

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek status pada URL untuk alert
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Data Rotan</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Rotan</li>
            </ol>
        </nav>
    </div>
    <a href="tambah-data-rotan.php" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle"></i> Tambah Data
    </a>
</div>

<!-- Alert Status Edit Data -->
<?php if ($status == 'sukses-edit'): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        Data rotan berhasil diupdate!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif ($status == 'gagal-edit'): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        Gagal mengupdate data rotan!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Alert untuk Hapus Data -->
<?php
if (isset($_GET['status']) && $_GET['status'] == 'sukses-hapus') {
    echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Data rotan berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Daftar Rotan yang Tersedia</h5>
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Jenis Rotan</th>
                        <th>Ukuran</th>
                        <th>Kualitas</th>
                        <th>Harga</th>
                        <th>Minimal Pembelian</th>
                        <th>Ketersediaan Stok</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    $query = mysqli_query($koneksi, "
                        SELECT 
                            data_rotan.id_rotan,
                            jenis_rotan.nama_jenis AS jenis_rotan,
                            ukuran_rotan.ukuran,
                            data_rotan.kualitas,
                            data_rotan.harga,
                            data_rotan.minimal_pembelian,
                            data_rotan.stok
                        FROM 
                            data_rotan
                        JOIN 
                            jenis_rotan ON data_rotan.id_jenis = jenis_rotan.id_jenis
                        JOIN 
                            ukuran_rotan ON data_rotan.id_ukuran = ukuran_rotan.id_ukuran
                        WHERE 
                            data_rotan.id_supplier = '$id_supplier'
                        ORDER BY 
                            data_rotan.id_rotan ASC
                    ");

                    if (mysqli_num_rows($query) > 0):
                        while ($data = mysqli_fetch_array($query)):
                            $no++;
                            ?>
                            <tr align="center">
                                <td><?php echo $no; ?></td>
                                <td><?php echo htmlspecialchars($data['jenis_rotan']); ?></td>
                                <td><?php echo htmlspecialchars($data['ukuran']); ?></td>
                                <td><?php echo htmlspecialchars($data['kualitas']); ?></td>
                                <td><?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($data['minimal_pembelian']); ?> Kg</td>
                                <td><?php echo htmlspecialchars($data['stok']); ?></td>
                                <td align="center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- Tombol Edit -->
                                        <a class="btn btn-warning btn-sm d-flex align-items-center gap-1"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Data"
                                            href="edit-data-rotan.php?id=<?= $data['id_rotan']; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <a class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus Data"
                                            href="hapus-data-rotan.php?id=<?= $data['id_rotan']; ?>"
                                            onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="9" align="center">NO-DATA</td>
                        </tr>
                        <?php
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once('template/footer.php'); ?>