<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(3)); ?>

<?php
$page = "data_rotan";
require_once('template/header.php');
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
    <a href="input-data-rotan.php" class="btn btn-success btn-sm">Tambah Data </a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"></h5>
        <div class="table-responsive">
            <table class="table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th width="20%">Jenis Rotan</th>
                        <th width="15%">Kualitas</th>
                        <th width="15%">Harga (Rp)</th>
                        <th width="15%">Ketersediaan Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    $query = mysqli_query($koneksi, "SELECT * FROM rotan");

                    while ($data = mysqli_fetch_array($query)):
                        $no++;
                        ?>
                        <tr align="center">
                            <td><?php echo $no; ?></td>
                            <td align="left"> <?php echo $data['jenis_rotan']; ?> </td>
                            <td>
                                <select name="kualitas[]" class="form-control" required>
                                    <option value="">--Pilih--</option>
                                    <option value="AB" <?php if ($data['kualitas'] == 'AB')
                                        echo 'selected'; ?>>AB</option>
                                    <option value="BC" <?php if ($data['kualitas'] == 'BC')
                                        echo 'selected'; ?>>BC</option>
                                    <option value="CD" <?php if ($data['kualitas'] == 'CD')
                                        echo 'selected'; ?>>CD</option>
                                </select>
                            </td>
                            <td><input type="number" name="harga[]" class="form-control"
                                    value="<?php echo $data['harga']; ?>" required></td>
                            <td><input type="number" name="stok[]" class="form-control" value="<?php echo $data['stok']; ?>"
                                    required></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once('template/footer.php'); ?>