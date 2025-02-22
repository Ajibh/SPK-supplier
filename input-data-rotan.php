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
				<label for="id_jenis" class="form-label">Jenis Rotan</label>
				<select class="form-control" id="id_jenis" name="id_jenis" required>
					<option value="">Pilih Jenis Rotan</option>
					<?php
					$query_jenis = "SELECT id_jenis, nama_jenis FROM jenis_rotan";
					$result_jenis = mysqli_query($koneksi, $query_jenis);
					while ($row = mysqli_fetch_assoc($result_jenis)) {
						echo '<option value="' . $row['id_jenis'] . '">' . $row['nama_jenis'] . '</option>';
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
						echo '<option value="' . $row['id_ukuran'] . '">' . $row['ukuran'] . '</option>';
					}
					?>
				</select>
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
</div><!-- Modal Hasil Input -->
<div class="modal fade" id="modalHasil" tabindex="-1" aria-labelledby="modalHasilLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHasilLabel">Hasil Input Data Rotan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Isi pesan hasil input akan ditampilkan di sini -->
                <p id="pesanHasil"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
// Cek apakah ada pesan hasil input
<?php if (isset($result)) : ?>
    var pesan = "";
    <?php if ($result) : ?>
        pesan = "Data rotan berhasil ditambahkan.";
    <?php else : ?>
        pesan = "Gagal menambahkan data rotan.";
    <?php endif; ?>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pesanHasil').innerText = pesan;
        var modalHasil = new bootstrap.Modal(document.getElementById('modalHasil'));
        modalHasil.show();
    });
<?php endif; ?>
</script>



<?php require_once('template/footer.php'); ?>