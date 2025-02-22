<?php
require_once('includes/init.php');
cek_login($role = array(3)); // Hanya bisa diakses oleh role 3

$page = "data_rotan";
require_once('template/header.php');

// Proses Simpan Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$jenis_rotan = $_POST['jenis_rotan'];
	$kualitas = $_POST['kualitas'];
	$harga = $_POST['harga'];
	$minimal_pembelian = $_POST['minimal_pembelian'];
	$stok = $_POST['stok'];

	// Validasi dan Simpan Data
	if (!empty($jenis_rotan) && !empty($kualitas) && !empty($harga) && !empty($minimal_pembelian) && !empty($stok)) {
		$query = "INSERT INTO rotan (jenis_rotan, kualitas, harga, minimal_pembelian, stok) 
                  VALUES ('$jenis_rotan', '$kualitas', '$harga', '$minimal_pembelian', '$stok')";
		$result = mysqli_query($koneksi, $query);

		if ($result) {
			echo '<div class="alert alert-success">Data rotan berhasil ditambahkan.</div>';
		} else {
			echo '<div class="alert alert-danger">Gagal menambahkan data rotan.</div>';
		}
	} else {
		echo '<div class="alert alert-warning">Harap isi semua field.</div>';
	}
}
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data Rotan</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Tambah Data Rotan</li>
			</ol>
		</nav>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<h5 class="card-title">Form Tambah Data Rotan</h5>
		<form action="proses_input_rotan.php" method="POST">
			<div class="mb-3">
				<label for="jenis_rotan" class="form-label">Jenis Rotan</label>
				<input type="text" class="form-control" id="jenis_rotan" name="jenis_rotan" required>
			</div>

			<div class="mb-3">
				<label for="ukuran" class="form-label">Ukuran</label>
				<input type="text" class="form-control" id="ukuran" name="ukuran" placeholder="contoh: 10-12" required>
			</div>

			<div class="mb-3">
				<label class="form-label">Harga Berdasarkan Kualitas (Opsional)</label>
				<div class="row g-2">
					<div class="col-md-4">
						<label for="harga_ab" class="form-label">Harga AB</label>
						<input type="number" class="form-control" id="harga_ab" name="harga_ab" placeholder="(-) jika tidak tersedia">
					</div>
					<div class="col-md-4">
						<label for="harga_bc" class="form-label">Harga BC</label>
						<input type="number" class="form-control" id="harga_bc" name="harga_bc" placeholder="(-) jika tidak tersedia">
					</div>
					<div class="col-md-4">
						<label for="harga_cd" class="form-label">Harga CD</label>
						<input type="number" class="form-control" id="harga_cd" name="harga_cd" placeholder="(-) jika tidak tersedia">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label for="minimal_pembelian" class="form-label">Minimal Pembelian</label>
					<select class="form-control" id="minimal_pembelian" name="minimal_pembelian" required>
						<option value="">-- Pilih Minimal Pembelian --</option>
						<option value="1 kg">1 Kg</option>
						<option value="10 kg">10 Kg</option>
						<option value="1 Ball">1 Ball (25 Kg)</option>
					</select>
				</div>

				<div class="col-md-6">
					<label for="stok" class="form-label">Ketersediaan Stok</label>
					<select class="form-control" id="stok" name="stok" required>
						<option value="">-- Pilih Ketersediaan Stok --</option>
						<option value="Tersedia">Selalu Tersedia</option>
						<option value="Terbatas">Terbatas</option>
					</select>
				</div>
			</div>

			<button type="submit" class="btn btn-primary mt-3 btn-sm">Simpan Data Rotan</button>
		</form>
	</div>
</div>


<?php require_once('template/footer.php'); ?>