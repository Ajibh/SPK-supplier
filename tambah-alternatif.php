<?php
require_once('includes/init.php');
cek_login($role = array(1));

$errors = array();
$sukses = false;

$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';

if (isset($_POST['submit'])):
	// Validasi
	if (!$nama) {
		$errors[] = 'Nama tidak boleh kosong';
	}
	if (!$alamat) {
		$errors[] = 'No HP tidak boleh kosong';
	}

	// Jika lolos validasi lakukan hal di bawah ini
	if (empty($errors)):
		$simpan = mysqli_query($koneksi, "INSERT INTO alternatif (nama, alamat) VALUES ('$nama', '$alamat')");
		if ($simpan) {
			redirect_to('list-alternatif.php?status=sukses-baru');
		} else {
			$errors[] = 'Data gagal disimpan';
		}
	endif;

endif;

$page = "Alternatif";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data Supplier</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Tambah Data Supplier</li>
			</ol>
		</nav>
	</div>

	<a href="list-alternatif.php" class="btn btn-secondary btn-icon-split btn-sm">
		<span class="text">Kembali</span>
	</a>
</div>

<?php if (!empty($errors)): ?>
	<div class="alert alert-info">
		<?php foreach ($errors as $error): ?>
			<?php echo $error; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<form action="tambah-alternatif.php" method="post">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Tambah Data Supplier</h5>
			<div class="row">
				<div class="form-group col-md-12">
					<label class="font-weight-bold">Nama Supplier</label>
					<input autocomplete="off" type="text" name="nama" required placeholder="Isi Nama Lengkap"
						value="<?php echo htmlspecialchars($nama); ?>" class="form-control" />
				</div>
				<div class="form-group col-md-12 mt-2">
					<label class="font-weight-bold">Alamat</label>
					<textarea name="alamat" required placeholder="Alamat Lokasi Supplier"
						class="form-control"><?php echo htmlspecialchars($alamat); ?></textarea>
				</div>
			</div>
		</div>
		<div class="card-footer text-right">
			<button name="submit" value="submit" type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>
				Simpan</button>
			<button type="reset" class="btn btn-info btn-sm"><i class="fa fa-sync-alt"></i> Reset</button>
		</div>
	</div>
</form>

<?php
require_once('template/footer.php');
?>