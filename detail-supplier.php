<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$page = "Detail Supplier";
require_once('template/header.php');

$id_supplier = isset($_GET['id']) ? intval($_GET['id']) : 0;
$supplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier = '$id_supplier'");
$data_supplier = mysqli_fetch_assoc($supplier);

if (!$data_supplier) {
    echo '<div class="alert alert-danger">Data supplier tidak ditemukan!</div>';
    require_once('template/footer.php');
    exit;
}
?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Detail Supplier</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="data-supplier.php">Data Supplier</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Supplier</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Informasi Supplier</h5>
        <table class="table table-bordered">
            <tr>
                <th>Nama Supplier</th>
                <td><?php echo htmlspecialchars($data_supplier['nama']); ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?php echo htmlspecialchars($data_supplier['alamat']); ?></td>
            </tr>
            <tr>
                <th>Kontak</th>
                <td><?php echo htmlspecialchars($data_supplier['kontak']); ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Daftar Rotan yang Dijual</h5>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr align="center">
                        <th>No</th>
                        <th>Jenis Rotan</th>
                        <th>Kualitas</th>
                        <th>Harga per Kg</th>
                        <th>Ketersediaan Stok</th>
                        <th>Minimal Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    $query = mysqli_query($koneksi, "SELECT * FROM rotan WHERE id_supplier = '$id_supplier'");
                    while ($data = mysqli_fetch_array($query)):
                        $no++;
                        ?>
                        <tr align="center">
                            <td><?php echo $no; ?></td>
                            <td><?php echo htmlspecialchars($data['jenis_rotan']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($data['harga'], 0, ',', '.')); ?> IDR</td>
                            <td><?php echo htmlspecialchars($data['stok']); ?> kg</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<a href="data-supplier.php" class="btn btn-primary mt-3">Kembali</a>

<?php require_once('template/footer.php'); ?>