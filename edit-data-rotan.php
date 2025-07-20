<?php
require_once('includes/init.php');
cek_login($role = array(2));

// Pastikan session id_supplier sudah ada
if (!isset($_SESSION['id_supplier'])) {
    die('Session id_supplier tidak ditemukan!');
}

$id_supplier = $_SESSION['id_supplier'];
    
// Periksa apakah parameter id ada pada URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID Rotan tidak ditemukan!');
}

$id_rotan = $_GET['id'];

// Ambil data rotan berdasarkan id_rotan dan id_supplier
$query = mysqli_query($koneksi, "
    SELECT 
        data_rotan.id_rotan,
        data_rotan.id_jenis,
        data_rotan.id_ukuran,
        data_rotan.kualitas,
        data_rotan.harga,
        data_rotan.stok,
        data_rotan.minimal_pembelian
    FROM 
        data_rotan
    WHERE 
        data_rotan.id_rotan = '$id_rotan'
        AND data_rotan.id_supplier = '$id_supplier'
");

$data = mysqli_fetch_assoc($query);
if (!$data) {
    die('Data rotan tidak ditemukan!');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_jenis = $_POST['id_jenis'];
    $id_ukuran = $_POST['id_ukuran'];
    $kualitas = $_POST['kualitas'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $minimal_pembelian = $_POST['minimal_pembelian'];

    // Cek apakah kombinasi jenis, ukuran, dan kualitas sudah ada untuk supplier ini
    $cek_duplikat = mysqli_query($koneksi, "
        SELECT id_rotan 
        FROM data_rotan 
        WHERE id_supplier = '$id_supplier'
        AND id_jenis = '$id_jenis'
        AND id_ukuran = '$id_ukuran'
        AND kualitas = '$kualitas'
        AND id_rotan != '$id_rotan'
    ");

    if (mysqli_num_rows($cek_duplikat) > 0) {
        $error_message = 'Kombinasi Jenis Rotan, Ukuran, dan Kualitas sudah ada!';

    } else {
        // CEK APAKAH ADA PERUBAHAN DATA
        if (
            $data['id_jenis'] == $id_jenis &&
            $data['id_ukuran'] == $id_ukuran &&
            $data['kualitas'] == $kualitas &&
            $data['harga'] == $harga &&
            $data['stok'] == $stok &&
            $data['minimal_pembelian'] == $minimal_pembelian
        ) {
            // Tidak ada perubahan data, kembali tanpa update
            header('Location: data-rotan.php');
            exit();
        }

        // Ada perubahan, lanjut update
        $update = mysqli_query($koneksi, "UPDATE data_rotan SET 
            id_jenis = '$id_jenis', 
            id_ukuran = '$id_ukuran', 
            kualitas = '$kualitas', 
            harga = '$harga', 
            minimal_pembelian = '$minimal_pembelian', 
            stok = '$stok',
            updated_at = CURRENT_TIMESTAMP
            WHERE id_rotan = '$id_rotan'
            AND id_supplier = '$id_supplier'
        ");

        if ($update) {
            header('Location: data-rotan.php?status=sukses-edit');
            exit();
        } else {
            header('Location: data-rotan.php?status=gagal-edit');
            exit();
        }
    }
}



$page = "edit_data_rotan";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Data Rotan</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="Data Rotan.php">Data Rotan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Data Rotan</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Alert Cek Duplikasi -->
<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>


<div class="card">
    <div class="card-body">
        <h5 class="card-title">Form Edit Data Rotan</h5>
        <form action="" method="POST">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="id_jenis" class="form-label">Jenis Rotan</label>
                    <select class="form-control" id="id_jenis" name="id_jenis" required>
                        <option value="">Pilih Jenis Rotan</option>
                        <?php
                        $query_jenis = "SELECT id_jenis, nama_jenis FROM jenis_rotan";
                        $result_jenis = mysqli_query($koneksi, $query_jenis);
                        while ($row = mysqli_fetch_assoc($result_jenis)) {
                            $selected = ($row['id_jenis'] == $data['id_jenis']) ? 'selected' : '';
                            echo '<option value="' . $row['id_jenis'] . '" ' . $selected . '>' . $row['nama_jenis'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="id_ukuran" class="form-label">Ukuran</label>
                    <select class="form-control" id="id_ukuran" name="id_ukuran" required>
                        <option value="">Pilih Ukuran</option>
                        <?php
                        $query_ukuran = "SELECT id_ukuran, ukuran FROM ukuran_rotan";
                        $result_ukuran = mysqli_query($koneksi, $query_ukuran);
                        while ($row = mysqli_fetch_assoc($result_ukuran)) {
                            $selected = ($row['id_ukuran'] == $data['id_ukuran']) ? 'selected' : '';
                            echo '<option value="' . $row['id_ukuran'] . '" ' . $selected . '>' . $row['ukuran'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <label for="kualitas" class="form-label">Kualitas</label>
                        <select class="form-control" id="kualitas" name="kualitas" required>
                            <option value="" disabled>-- Pilih Kualitas --</option>
                            <option value="AB" <?= ($data['kualitas'] == 'AB') ? 'selected' : ''; ?>>AB</option>
                            <option value="BC" <?= ($data['kualitas'] == 'BC') ? 'selected' : ''; ?>>BC</option>
                            <option value="CD" <?= ($data['kualitas'] == 'CD') ? 'selected' : ''; ?>>CD</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?= $data['harga']; ?>"
                            required>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="minimal_pembelian" class="form-label">Minimal Pembelian (Kg)</label>
                    <input type="number" class="form-control" id="minimal_pembelian" name="minimal_pembelian"
                        value="<?= $data['minimal_pembelian']; ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="stok" class="form-label">Stok (Kg)</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?= $data['stok']; ?>"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </form>
    </div>
</div>


<?php require_once('template/footer.php'); ?>