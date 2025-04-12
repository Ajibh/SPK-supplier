<?php
require_once('includes/init.php');
cek_login($role = array(2)); // Hanya bisa diakses oleh role 3

$page = "data_rotan";
require_once('template/header.php');

// Ambil id_user yang sedang login
$id_user = $_SESSION['id_user'];
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data Rotan</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item"><a href="Data Rotan.php">Data Rotan</a></li>
				<li class="breadcrumb-item active" aria-current="page">Tambah Data Rotan</li>
			</ol>
		</nav>
	</div>
</div>

<?php
// Ambil id_supplier berdasarkan id_user yang sedang login
$query_supplier = "SELECT id_supplier FROM supplier WHERE id_user = '$id_user'";
$result_supplier = mysqli_query($koneksi, $query_supplier);
$supplier = mysqli_fetch_assoc($result_supplier);
$id_supplier = $supplier['id_supplier'];

// Proses Simpan Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id_jenis = $_POST['id_jenis'];
	$id_ukuran = $_POST['id_ukuran'];
	$kualitas = $_POST['kualitas'];
	$harga = $_POST['harga'];
	$minimal_pembelian = $_POST['minimal_pembelian'];
	$stok = $_POST['stok'];

	// Validasi Data
	if (!empty($id_jenis) && !empty($id_ukuran) && !empty($minimal_pembelian) && !empty($stok)) {
		// Cek apakah data sudah ada untuk supplier yang sama
		$cek_query = "SELECT * FROM data_rotan WHERE id_jenis = '$id_jenis' 
                      AND id_ukuran = '$id_ukuran' AND kualitas = '$kualitas' 
                      AND id_supplier = '$id_supplier'";
		$cek_result = mysqli_query($koneksi, $cek_query);

		if (mysqli_num_rows($cek_result) > 0) {
			echo '<div class="alert alert-warning">Data rotan dengan jenis, ukuran, dan kualitas yang sama sudah ada untuk supplier ini.</div>';
		} else {
			// Input ke database jika belum ada
			$query = "INSERT INTO data_rotan (id_jenis, id_ukuran, kualitas, harga, minimal_pembelian, stok, id_supplier) 
			          VALUES ('$id_jenis', '$id_ukuran', '$kualitas', '$harga', '$minimal_pembelian', '$stok', '$id_supplier')";
			$result = mysqli_query($koneksi, $query);

			if ($result) {
				echo '<div class="alert alert-success">Data rotan berhasil ditambahkan.</div>';
			} else {
				echo '<div class="alert alert-danger">Gagal menambahkan data rotan.</div>';
			}
		}
	} else {
		echo '<div class="alert alert-warning">Harap isi semua field yang diperlukan.</div>';
	}
}
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title">Form Tambah Data Rotan</h5>
		<form action="" method="POST">
			<div class="row mb-4">
				<div class="col-md-6">
					<label for="id_jenis" class="form-label">Jenis Rotan</label>
					<div class="dropdown">
						<button class="form-control text-start" type="button" id="dropdownJenisRotan"
							data-bs-toggle="dropdown" aria-expanded="false">
							-- Pilih Jenis Rotan --
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownJenisRotan"
							style="max-height: 200px; overflow-y: auto;">
							<?php
							$query_jenis = "SELECT id_jenis, nama_jenis FROM jenis_rotan";
							$result_jenis = mysqli_query($koneksi, $query_jenis);
							while ($row = mysqli_fetch_assoc($result_jenis)):
								?>
								<li>
									<a class="dropdown-item" href="#" data-value="<?= $row['id_jenis']; ?>">
										<?= $row['nama_jenis']; ?>
									</a>
								</li>
							<?php endwhile; ?>
						</ul>
						<input type="hidden" name="id_jenis" id="id_jenis" required>
					</div>
				</div>

				<div class="col-md-6">
					<label for="id_ukuran" class="form-label">Ukuran</label>
					<div class="dropdown">
						<button class="form-control text-start" type="button" id="dropdownUkuranRotan"
							data-bs-toggle="dropdown" aria-expanded="false">
							-- Pilih Ukuran --
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownUkuranRotan"
							style="max-height: 200px; overflow-y: auto;">
							<?php
							$query_ukuran = "SELECT id_ukuran, ukuran FROM ukuran_rotan";
							$result_ukuran = mysqli_query($koneksi, $query_ukuran);
							while ($row = mysqli_fetch_assoc($result_ukuran)):
								?>
								<li>
									<a class="dropdown-item" href="#" data-value="<?= $row['id_ukuran']; ?>">
										<?= $row['ukuran']; ?>
									</a>
								</li>
							<?php endwhile; ?>
						</ul>
						<input type="hidden" name="id_ukuran" id="id_ukuran" required>
					</div>
				</div>
			</div>

			<div class="mb-4">
				<div class="row">
					<div class="col-md-6">
						<label for="kualitas" class="form-label">Kualitas</label>
						<select class="form-control" id="kualitas" name="kualitas" required>
							<option value="" disabled selected>-- Pilih Kualitas --</option>
							<option value="AB">AB</option>
							<option value="BC">BC</option>
							<option value="CD">CD</option>
						</select>
					</div>
					<div class="col-md-6">
						<label for="harga" class="form-label">Harga</label>
						<input type="number" class="form-control" id="harga" name="harga"
							placeholder="Harga Per Kilogram (kg)" required>
					</div>
				</div>
			</div>

			<div class="row mb-4">
				<div class="col-md-6">
					<label for="minimal_pembelian" class="form-label">Minimal Pembelian (Kg)</label>
					<input type="number" class="form-control" id="minimal_pembelian" name="minimal_pembelian"
						placeholder="Minimal Pembelian dalam Kilogram" required>
				</div>

				<div class="col-md-6">
					<label for="stok" class="form-label"> Stok (Kg)</label>
					<input type="number" class="form-control" id="stok" name="stok" required>
				</div>
			</div>

			<button type="submit" class="btn btn-primary btn-sm">Simpan</button>
		</form>
	</div>
</div>

<script>
	// Script untuk Dropdown Jenis Rotan
	document.querySelectorAll('.dropdown-item').forEach(item => {
		item.addEventListener('click', function (e) {
			e.preventDefault();
			const value = this.getAttribute('data-value');
			const text = this.textContent;

			// Tentukan dropdown yang dipilih
			if (this.closest('.dropdown').querySelector('#dropdownJenisRotan')) {
				document.getElementById('id_jenis').value = value;
				document.getElementById('dropdownJenisRotan').textContent = text;
			}
			if (this.closest('.dropdown').querySelector('#dropdownUkuranRotan')) {
				document.getElementById('id_ukuran').value = value;
				document.getElementById('dropdownUkuranRotan').textContent = text;
			}
		});
	});
</script>

<?php require_once('template/footer.php'); ?>