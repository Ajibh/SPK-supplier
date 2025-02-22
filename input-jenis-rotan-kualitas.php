<?php
require_once('includes/init.php');
cek_login($role = array(2));

$page = "input_jenis_rotan_kualitas";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Input Jenis Rotan & Kualitas</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Input Jenis Rotan & Kualitas</li>
			</ol>
		</nav>
	</div>
</div>

<?php
// Proses Simpan Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$jenis_rotan = $_POST['jenis_rotan'];
	$kualitas_rotan = $_POST['kualitas_rotan'];

	// Validasi
	$errors = [];
	if (empty($jenis_rotan)) {
		$errors[] = 'Jenis rotan harus dipilih.';
	}
	if (empty($kualitas_rotan)) {
		$errors[] = 'Kualitas rotan harus dipilih.';
	}

	// Jika tidak ada error, simpan ke database
	if (empty($errors)) {
		$query = "INSERT INTO pilihan_rotan (jenis_rotan) VALUES ('$jenis_rotan')";
		$result = mysqli_query($koneksi, $query);

		if ($result) {
			echo '<div class="alert alert-success">Data berhasil disimpan.</div>';
		} else {
			echo '<div class="alert alert-danger">Gagal menyimpan data.</div>';
		}
	} else {
		foreach ($errors as $error) {
			echo '<div class="alert alert-danger">' . $error . '</div>';
		}
	}
}
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title">Tentukan Jenis dan Kualitas yang diinginkan</h5>
		<form action="" method="POST">
			<div class="mb-3">
				<label for="jenis_rotan" class="form-label">Jenis Rotan</label>
				<select name="jenis_rotan" class="form-control" required>
					<option value="">--Pilih Jenis Rotan--</option>
					<?php
					// Ambil data Jenis Rotan dari database
					$query_jenis = mysqli_query($koneksi, "SELECT * FROM jenis_rotan ORDER BY nama_jenis ASC");
					while ($jenis = mysqli_fetch_array($query_jenis)) {
						echo '<option value="' . $jenis['id_jenis_rotan'] . '">' . $jenis['nama_jenis'] . '</option>';
					}
					?>
				</select>
			</div>
			<div class="mb-3">
				<label for="kualitas_rotan" class="form-label">Kualitas Rotan</label>
				<select name="kualitas_rotan" class="form-control" required>
					<option value="">--Pilih Kualitas Rotan--</option>
					<option value="AB">AB</option>
					<option value="BC">BC</option>
					<option value="CD">CD</option>
				</select>
			</div>
			<button type="submit" class="btn btn-primary" name="submit">Simpan</button>
		</form>
	</div>
</div>

<?php
if (isset($_POST['submit'])) {
	$jenis_rotan = $_POST['jenis_rotan'];
	$kualitas_rotan = $_POST['kualitas_rotan'];

	// Step 1: Ambil data supplier yang memiliki jenis rotan yang dipilih
	$query_supplier = mysqli_query($koneksi, "
		SELECT s.id_supplier, s.nama_supplier, s.alamat_supplier, k.harga, k.jarak, k.stok
		FROM supplier s
		JOIN kriteria_supplier k ON s.id_supplier = k.id_supplier
		WHERE k.id_jenis_rotan = '$jenis_rotan'
	");

	// Step 2: Normalisasi Nilai Kriteria
	$data_supplier = [];
	while ($supplier = mysqli_fetch_array($query_supplier)) {
		$data_supplier[] = $supplier;
	}

	// Normalisasi Harga (Cost)
	$min_harga = min(array_column($data_supplier, 'harga'));
	foreach ($data_supplier as &$data) {
		$data['normalisasi_harga'] = $min_harga / $data['harga'];
	}

	// Normalisasi Jarak (Cost)
	$min_jarak = min(array_column($data_supplier, 'jarak'));
	foreach ($data_supplier as &$data) {
		$data['normalisasi_jarak'] = $min_jarak / $data['jarak'];
	}

	// Normalisasi Stok (Benefit)
	$max_stok = max(array_column($data_supplier, 'stok'));
	foreach ($data_supplier as &$data) {
		$data['normalisasi_stok'] = $data['stok'] / $max_stok;
	}

	// Step 3: Hitung Nilai SAW
	$hasil_ranking = [];
	$bobot = [
		'harga' => 0.4,
		'jarak' => 0.3,
		'stok' => 0.3
	];
	foreach ($data_supplier as $data) {
		$nilai_total =
			$bobot['harga'] * $data['normalisasi_harga'] +
			$bobot['jarak'] * $data['normalisasi_jarak'] +
			$bobot['stok'] * $data['normalisasi_stok'];
		$hasil_ranking[] = [
			'nama_supplier' => $data['nama_supplier'],
			'alamat_supplier' => $data['alamat_supplier'],
			'nilai_total' => $nilai_total
		];
	}

	// Step 4: Urutkan Berdasarkan Nilai Total
	usort($hasil_ranking, function ($a, $b) {
		return $b['nilai_total'] <=> $a['nilai_total'];
	});
	?>

	<!-- Card Rekomendasi -->
	<div class="card mt-4">
		<div class="card-body">
			<h5 class="card-title">Rekomendasi Supplier</h5>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr align="center">
							<th>No</th>
							<th>Nama Supplier</th>
							<th>Alamat</th>
							<th>Nilai Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($hasil_ranking as $ranking) {
							?>
							<tr align="center">
								<td><?php echo $no++; ?></td>
								<td align="left"><?php echo $ranking['nama_supplier']; ?></td>
								<td align="left"><?php echo $ranking['alamat_supplier']; ?></td>
								<td><?php echo round($ranking['nilai_total'], 4); ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php } ?>

<?php
require_once('template/footer.php');
?>