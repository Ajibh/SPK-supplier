<?php
require_once('includes/init.php');
cek_login($role = array(3));

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
        data_rotan.harga_ab,
        data_rotan.harga_bc,
        data_rotan.harga_cd,
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
    $harga_ab = $_POST['harga_ab'];
    $harga_bc = $_POST['harga_bc'];
    $harga_cd = $_POST['harga_cd'];
    $stok = $_POST['stok'];
    $minimal_pembelian = $_POST['minimal_pembelian'];

    // Update data rotan
    $update = mysqli_query($koneksi, "
        UPDATE data_rotan SET 
            id_jenis = '$id_jenis',
            id_ukuran = '$id_ukuran',
            harga_ab = '$harga_ab',
            harga_bc = '$harga_bc',
            harga_cd = '$harga_cd',
            stok = '$stok',
            minimal_pembelian = '$minimal_pembelian'
        WHERE 
            id_rotan = '$id_rotan'
            AND id_supplier = '$id_supplier'
    ");

    if ($update) {
        header('Location: data-rotan.php?status=sukses-edit');
        exit();
    } else {
        echo '<div class="alert alert-danger">Gagal mengedit data rotan!</div>';
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

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Form Edit Data Rotan</h5>
        <form action="" method="POST">
            <div class="mb-3">
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

            <div class="mb-3">
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

            <div class="mb-3">
                <label class="form-label">Harga Berdasarkan Kualitas (Opsional)</label>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="harga_ab" class="form-label">Harga AB</label>
                        <input type="number" class="form-control" id="harga_ab" name="harga_ab"
                            value="<?php echo htmlspecialchars($data['harga_ab']); ?>"
                            placeholder="Isi 0 jika tidak tersedia">
                    </div>
                    <div class="col-md-4">
                        <label for="harga_bc" class="form-label">Harga BC</label>
                        <input type="number" class="form-control" id="harga_bc" name="harga_bc"
                            value="<?php echo htmlspecialchars($data['harga_bc']); ?>"
                            placeholder="Isi 0 jika tidak tersedia">
                    </div>
                    <div class="col-md-4">
                        <label for="harga_cd" class="form-label">Harga CD</label>
                        <input type="number" class="form-control" id="harga_cd" name="harga_cd"
                            value="<?php echo htmlspecialchars($data['harga_cd']); ?>"
                            placeholder="Isi 0 jika tidak tersedia">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="minimal_pembelian" class="form-label">Minimal Pembelian</label>
                    <select class="form-control" id="minimal_pembelian" name="minimal_pembelian" required>
                        <option value="1 kg" <?php echo ($data['minimal_pembelian'] == '1 kg') ? 'selected' : ''; ?>>1 Kg
                        </option>
                        <option value="10 kg" <?php echo ($data['minimal_pembelian'] == '10 kg') ? 'selected' : ''; ?>>10
                            Kg</option>
                        <option value="1 Ball" <?php echo ($data['minimal_pembelian'] == '1 Ball') ? 'selected' : ''; ?>>1
                            Ball (25 Kg)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="stok" class="form-label">Ketersediaan Stok</label>
                    <select class="form-control" id="stok" name="stok" required>
                        <option value="Tersedia Selalu" <?php echo ($data['stok'] == 'Tersedia Selalu') ? 'selected' : ''; ?>>Tersedia Selalu</option>
                        <option value="Terbatas" <?php echo ($data['stok'] == 'Terbatas') ? 'selected' : ''; ?>>Terbatas
                        </option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3 btn-sm">Update Data Rotan</button>
        </form>
    </div>
</div>

<?php require_once('template/footer.php'); ?>