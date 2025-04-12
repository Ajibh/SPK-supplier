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

<div class="card">
	<div class="card-body">
		<h5 class="card-title">Tentukan Jenis, Ukuran dan Kualitas Rotan yang diinginkan</h5>
		<form action="" method="POST">
			<div class="mb-3">
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

			<div class="mb-3">
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

			<div class="mb-3">
				<label for="kualitas_rotan" class="form-label">Kualitas Rotan</label>
				<select name="kualitas_rotan" class="form-control" required>
					<option value="">--Pilih Kualitas Rotan--</option>
					<option value="AB">AB</option>
					<option value="BC">BC</option>
					<option value="CD">CD</option>
				</select>
			</div>
			<button type="submit" class="btn btn-primary btn-sm" name="submit">Cari</button>
		</form>

		<div class="container">
    <h2>Apa yang paling penting bagi Anda?</h2>

    <div class="checkbox-group">
        <label><input type="checkbox" id="harga" onchange="hitungBobot()"> Harga Murah</label>
        <label><input type="checkbox" id="stok" onchange="hitungBobot()"> Stok Banyak</label>
        <label><input type="checkbox" id="minimal" onchange="hitungBobot()"> Minimal Pembelian Rendah</label>
    </div>

    <h3>Bobot yang dihitung:</h3>
    <p>Harga: <span id="bobot_harga">0</span></p>
    <p>Stok: <span id="bobot_stok">0</span></p>
    <p>Minimal Pembelian: <span id="bobot_minimal">0</span></p>

    <p id="hasil">Total Bobot: <span id="total_bobot">0</span></p>
</div>

<script>
function hitungBobot() {
    let harga = document.getElementById("harga").checked;
    let stok = document.getElementById("stok").checked;
    let minimal = document.getElementById("minimal").checked;

    let pilihan = [harga, stok, minimal].filter(p => p).length;

    let bobot = pilihan > 0 ? (1 / pilihan).toFixed(2) : 0;

    document.getElementById("bobot_harga").innerText = harga ? bobot : "0";
    document.getElementById("bobot_stok").innerText = stok ? bobot : "0";
    document.getElementById("bobot_minimal").innerText = minimal ? bobot : "0";

    let totalBobot = harga * bobot + stok * bobot + minimal * bobot;
    document.getElementById("total_bobot").innerText = totalBobot.toFixed(2);
    
    // Ubah warna total jika tidak 1
    document.getElementById("hasil").style.color = (totalBobot.toFixed(2) == 1.00) ? "green" : "red";
}
</script>
	</div>
</div>

<?php
// Pastikan data hasil filtering hanya muncul jika ada input dari form
if (isset($_POST['submit'])) {
	$id_jenis = $_POST['id_jenis'];
	$id_ukuran = $_POST['id_ukuran'];
	$kualitas_rotan = $_POST['kualitas_rotan'];

	// Menentukan kolom harga berdasarkan kualitas yang dipilih
	$harga_column = '';
	if ($kualitas_rotan == 'AB') {
		$harga_column = 'harga_ab';
	} elseif ($kualitas_rotan == 'BC') {
		$harga_column = 'harga_bc';
	} elseif ($kualitas_rotan == 'CD') {
		$harga_column = 'harga_cd';
	}

	// Query untuk mengambil data supplier dengan kriteria yang dipilih
	$query = "SELECT supplier.nama AS nama_supplier, supplier.kontak, 
			jenis_rotan.nama_jenis, ukuran_rotan.ukuran, data_rotan.$harga_column 
			FROM data_rotan
			JOIN supplier ON data_rotan.id_supplier = supplier.id_supplier
			JOIN jenis_rotan ON data_rotan.id_jenis = jenis_rotan.id_jenis
			JOIN ukuran_rotan ON data_rotan.id_ukuran = ukuran_rotan.id_ukuran
			WHERE data_rotan.id_jenis = '$id_jenis' 
			AND data_rotan.id_ukuran = '$id_ukuran' 
			AND data_rotan.$harga_column > 0"; // Hanya mengambil yang harganya tidak nol

	$result = mysqli_query($koneksi, $query);
}
?>

<div class="card mt-4">
	<div class="card-body">
		<h5 class="card-title">Hasil Filtering</h5>
		<?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
			<form action="saw.php" method="POST">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Supplier</th>
							<th>Jenis Rotan</th>
							<th>Ukuran</th>
							<th>Harga (<?= $kualitas_rotan ?>)</th>
							<th>Kontak</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_assoc($result)): ?>
							<tr>
								<td><?= $row['nama_supplier']; ?></td>
								<td><?= $row['nama_jenis']; ?></td>
								<td><?= $row['ukuran']; ?></td>
								<td><?= number_format($row[$harga_column], 0, ',', '.'); ?></td>
								<td><?= $row['kontak']; ?></td>
								<input type="hidden" name="id_supplier[]" value="<?= $row['nama_supplier']; ?>">
								<input type="hidden" name="harga[]" value="<?= $row[$harga_column]; ?>">
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				<button type="submit" class="btn btn-primary" name="lanjut">Lanjut Perankingan</button>
			</form>
		<?php else: ?>
			<p class="text-danger">Tidak ada data yang sesuai dengan filter yang dipilih.</p>
		<?php endif; ?>
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

<?php
require_once('template/footer.php');
?>