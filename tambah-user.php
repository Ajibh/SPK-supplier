<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

if (isset($_POST['submit'])):
	$username = $_POST['username'];
	$nama = $_POST['nama'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$email = $_POST['email'];
	$role = $_POST['role'];
	$errors = [];

	if ($password != $password2) {
		$errors[] = 'Password harus sama keduanya';
	}

	if (!$role) {
		$errors[] = 'Role tidak boleh kosong';
	}

	// Cek Username
	if ($username) {
		$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
		$cek = mysqli_fetch_array($query);
		if (!empty($cek)) {
			$errors[] = 'Username sudah digunakan';
		}
	}

	if (empty($errors)):
		$pass = sha1($password); // Ganti ini ke password_hash() kalau mau lebih aman
		$simpan = mysqli_query($koneksi, "INSERT INTO user (username, password, nama, email, role) VALUES ('$username', '$pass', '$nama', '$email', '$role')");

		if ($simpan) {
			$id_user = mysqli_insert_id($koneksi); // Ambil ID user terakhir

			// Jika role == 2 (Supplier), masukkan ke tabel supplier juga
			if ($role == 2) {
				mysqli_query($koneksi, "INSERT INTO supplier (id_user, nama) VALUES ('$id_user', '$nama')");
			}

			redirect_to('list-user.php?status=sukses-baru');
		} else {
			$errors[] = 'Data gagal disimpan';
		}
	endif;
endif;
?>

<?php
$page = "User";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data User</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Tambah Data User</li>
			</ol>
		</nav>
	</div>
	<a href="list-user.php" class="btn btn-secondary btn-icon-split btn-sm">
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

<form action="tambah-user.php" method="post">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Tambah Data User</h5>
			<div class="row">
				<div class="form-group col-md-6 mb-3">
					<label class="font-weight-bold">Username</label>
					<input autocomplete="off" type="text" name="username" required class="form-control" />
				</div>

				<div class="form-group col-md-6 mb-3">
					<label class="font-weight-bold">Nama</label>
					<input autocomplete="off" type="text" name="nama" required class="form-control" />
				</div>

				<div class="form-group col-md-6 mb-3">
					<label class="font-weight-bold">Password</label>
					<input autocomplete="off" type="password" name="password" required class="form-control" />
				</div>

				<div class="form-group col-md-6 mb-3">
					<label class="font-weight-bold">Ulangi Password</label>
					<input autocomplete="off" type="password" name="password2" required class="form-control" />
				</div>

				<div class="form-group col-md-6 mb-3">
					<label class="font-weight-bold">Email</label>
					<input autocomplete="off" type="text" name="email" required class="form-control" />
				</div>

				<div class="form-group col-md-6 mb-3">
					<label class="font-weight-bold">Role</label>
					<select name="role" required class="form-control">
						<option value="">--Pilih--</option>
						<option value="1">Administrator</option>
						<option value="2">Supplier</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card-footer text-right">
			<button name="submit" value="submit" type="submit" class="btn btn-success btn-sm">
				<i class="fa fa-save"></i> Simpan
			</button>
			<button type="reset" class="btn btn-info btn-sm">
				<i class="fa fa-sync-alt"></i> Reset
			</button>
		</div>
	</div>
</form>

<?php
require_once('template/footer.php');
?>