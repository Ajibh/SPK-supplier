<?php
require_once('includes/init.php');
cek_login($role = array(3)); // Hanya bisa diakses oleh role 3

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
	$harga_ab = $_POST['harga_ab'];
	$harga_bc = $_POST['harga_bc'];
	$harga_cd = $_POST['harga_cd'];
	$minimal_pembelian = $_POST['minimal_pembelian'];
	$stok = $_POST['stok'];

	// Validasi Data
	if (!empty($id_jenis) && !empty($id_ukuran) && !empty($minimal_pembelian) && !empty($stok)) {
		// Input ke database
		$query = "INSERT INTO data_rotan (id_jenis, id_ukuran, harga_ab, harga_bc, harga_cd, minimal_pembelian, stok, id_supplier) 
                  VALUES ('$id_jenis', '$id_ukuran', '$harga_ab', '$harga_bc', '$harga_cd', '$minimal_pembelian', '$stok', '$id_supplier')";
		$result = mysqli_query($koneksi, $query);

		if ($result) {
			echo '<div class="alert alert-success">Data rotan berhasil ditambahkan.</div>';
		} else {
			echo '<div class="alert alert-danger">Gagal menambahkan data rotan.</div>';
		}
	} else {
		echo '<div class="alert alert-warning">Harap isi semua field yang diperlukan.</div>';
	}
}
?>

<?php
// Ambil data kriteria yang memiliki pilihan (ada_pilihan = 1)
$query_kriteria = "SELECT * FROM kriteria WHERE ada_pilihan = 1";
$result_kriteria = mysqli_query($koneksi, $query_kriteria);
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title">Form Tambah Data Rotan</h5>
		<form action="" method="POST">
			<div class="row mb-4">
				<div class="col-6">
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

				<div class="col-6">
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
				<label class="form-label">Harga Berdasarkan Kualitas (Opsional)</label>
				<div class="row g-2">
					<div class="col-md-4">
						<label for="harga_ab" class="form-label">Harga AB</label>
						<input type="number" class="form-control" id="harga_ab" name="harga_ab"
							placeholder="Isi 0 jika tidak tersedia" required>
					</div>
					<div class="col-md-4">
						<label for="harga_bc" class="form-label">Harga BC</label>
						<input type="number" class="form-control" id="harga_bc" name="harga_bc"
							placeholder="Isi 0 jika tidak tersedia" required>
					</div>
					<div class="col-md-4">
						<label for="harga_cd" class="form-label">Harga CD</label>
						<input type="number" class="form-control" id="harga_cd" name="harga_cd"
							placeholder="Isi 0 jika tidak tersedia" required>
					</div>
				</div>
			</div>

			<div class="row mb-4">
				<div class="col-md-6">
					<label for="minimal_pembelian" class="form-label">Minimal Pembelian</label>
					<select class="form-control" id="minimal_pembelian" name="minimal_pembelian" required>
						<option value="">-- Pilih Minimal Pembelian --</option>
						<?php
						// Ambil data distinct dari kolom minimal_pembelian pada tabel data_rotan
						$query_minimal = "SELECT DISTINCT minimal_pembelian FROM data_rotan WHERE minimal_pembelian IS NOT NULL";
						$result_minimal = mysqli_query($koneksi, $query_minimal);

						// Tampilkan sebagai option pada select
						while ($row = mysqli_fetch_assoc($result_minimal)) {
							echo '<option value="' . $row['minimal_pembelian'] . '">' . $row['minimal_pembelian'] . '</option>';
						}
						?>
					</select>
				</div>

				<div class="col-md-6">
					<label for="stok" class="form-label">Ketersediaan Stok</label>
					<select class="form-control" id="stok" name="stok" required>
						<option value="">-- Pilih Ketersediaan Stok --</option>
						<?php
						// Ambil nilai enum dari kolom stok pada tabel data_rotan
						$query_enum = "SHOW COLUMNS FROM data_rotan LIKE 'stok'";
						$result_enum = mysqli_query($koneksi, $query_enum);
						$row_enum = mysqli_fetch_assoc($result_enum);

						// Extract nilai enum
						preg_match("/^enum\('(.*)'\)$/", $row_enum['Type'], $matches);
						$enum_values = explode("','", $matches[1]);

						// Tampilkan sebagai option pada select
						foreach ($enum_values as $value) {
							echo '<option value="' . $value . '">' . $value . '</option>';
						}
						?>
					</select>
				</div>
			</div>

			<button type="submit" class="btn btn-primary btn-sm">Simpan Data Rotan</button>
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