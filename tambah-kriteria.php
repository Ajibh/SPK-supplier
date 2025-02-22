<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

if (isset($_POST['submit'])):
	$kode_kriteria = $_POST['kode_kriteria'];
	$nama = $_POST['nama'];
	$type = $_POST['type'];
	$ada_pilihan = $_POST['ada_pilihan'];

	// Validasi Kode Kriteria
	if (empty($kode_kriteria)) {
		$errors[] = 'Kode kriteria tidak boleh kosong';
	} else {
		// Cek apakah kode kriteria sudah ada di database
		$query_check = mysqli_query($koneksi, "SELECT * FROM kriteria WHERE kode_kriteria = '$kode_kriteria'");
		if (mysqli_num_rows($query_check) > 0) {
			$errors[] = 'Kode kriteria sudah ada, silakan gunakan kode yang lain.';
		}
	}

	// Validasi Nama Kriteria
	if (empty($nama)) {
		$errors[] = 'Nama kriteria tidak boleh kosong';
	}

	// Validasi Tipe
	if (empty($type)) {
		$errors[] = 'Type kriteria tidak boleh kosong';
	}

	if (empty($errors)):

		$simpan = mysqli_query($koneksi, "INSERT INTO kriteria (id_kriteria, kode_kriteria, nama, type, ada_pilihan) VALUES (NULL, '$kode_kriteria', '$nama', '$type', '$ada_pilihan')");
		if ($simpan) {
			header('Location: list-kriteria.php?status=sukses-baru');
		} else {
			$errors[] = 'Data gagal disimpan';
		}
	endif;

endif;
?>

<?php
$page = "Kriteria";
require_once('template/header.php');
?>


<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data Kriteria</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Tambah Data Kriteria</li>
			</ol>
		</nav>
	</div>

	<a href="list-kriteria.php" class="btn btn-secondary btn-icon-split btn-sm">
		<span class="text">Kembali</span>
	</a>
</div>

<?php if (!empty($errors)): ?>
	<div class="alert alert-info">
		<?php foreach ($errors as $error): ?>
			<?php echo $error; ?><br>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="card">
	<form action="tambah-kriteria.php" method="post">
		<div class="card-body">
			<h5 class="card-title">Tambah Data Kriteria</h5>
			<div class="row">
				<div class="form-group col-md-6">
					<label class="font-weight-bold">Kode Kriteria</label>
					<input autocomplete="off" type="text" name="kode_kriteria" required class="form-control" />
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Nama Kriteria</label>
					<input autocomplete="off" type="text" name="nama" required class="form-control" />
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Type Kriteria</label>
					<select name="type" class="form-control" required>
						<option value="">--Pilih--</option>
						<option value="Benefit">Benefit</option>
						<option value="Cost">Cost</option>
					</select>
				</div>

				<div class="form-group col-md-6">
					<label class="font-weight-bold">Cara Penilaian</label>
					<select name="ada_pilihan" class="form-control" required>
						<option value="">--Pilih--</option>
						<option value="0">Input Langsung</option>
						<option value="1">Pilihan Sub Kriteria</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card-footer text-right">
			<button name="submit" value="submit" type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>
				Simpan</button>
			<button type="reset" class="btn btn-info btn-sm"><i class="fa fa-sync-alt"></i> Reset</button>
		</div>
	</form>
</div>


<?php
require_once('template/footer.php');
?>