<?php 
require_once('includes/init.php');
cek_login($role = array(1));

$page = "Detail Supplier";
require_once('template/header.php');

$id_supplier = isset($_GET['id']) ? intval($_GET['id']) : 0;
$supplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier = '$id_supplier'");
$data_supplier = mysqli_fetch_assoc($supplier);
?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Detail Supplier</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="list-alternatif.php">Data Supplier</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Supplier</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Informasi Supplier</h5>
        <table class="table">
            <tbody>
                <tr>
                    <th style="width: 15%;">Nama Supplier</th>
                    <td>: <?php echo htmlspecialchars($data_supplier['nama'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Kontak</th>
                    <td>: <?php echo htmlspecialchars($data_supplier['kontak'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>: <?php echo htmlspecialchars($data_supplier['email'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>: <?php echo htmlspecialchars($data_supplier['alamat'] ?? ''); ?></td>
                </tr>
            </tbody>

        </table>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title"> Daftar Rotan yang Dijual </h5>
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Jenis Rotan</th>
                        <th>Ukuran</th>
                        <th>Kualitas</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Min. Pembelian</th>
                        <th class="fst-italic">last update</th>
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
                        data_rotan.stok,
                        data_rotan.minimal_pembelian,
                        data_rotan.updated_at
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
                                <td>Rp. <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($data['stok']); ?> Kg</td>
                                <td><?php echo htmlspecialchars($data['minimal_pembelian']); ?> Kg</td>
                                <td class="fst-italic">
                                    <?php
                                    if (!empty($data['updated_at'])) {
                                        echo date('d-m-Y H:i:s', strtotime($data['updated_at']));
                                    } else {
                                        echo "-"; // atau boleh kamu ganti teks lain, misal: "Belum pernah diupdate"
                                    }
                                    ?>
                                </td>

                            </tr>
                            <?php
                        endwhile;

                    else:
                        ?>
                        <tr>
                            <td colspan="8" align="center">NO-DATA</td>
                        </tr>
                        <?php
                    endif;
                    ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<a href="list-alternatif.php" class="btn btn-primary btn-sm mt-2">Kembali</a>

<?php require_once('template/footer.php'); ?>