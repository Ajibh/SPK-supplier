<?php require_once('includes/init.php');
$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'customer' || $user_role == 'supplier') {
	?>

	<?php
	$errors = array();
	$sukses = false;

	$ada_error = false;
	$result = '';

	$id_user = $_SESSION["id_user"];

	if (isset($_POST['submit'])):
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$nama = $_POST['nama'];
		$kontak = $_POST['kontak'];

		if (!$nama) {
			$errors[] = 'Nama tidak boleh kosong';
		}

		if (!$kontak) {
			$errors[] = 'kontak tidak boleh kosong';
		}

		if (!$id_user) {
			$errors[] = 'Id User salah';
		}

		if ($password && ($password != $password2)) {
			$errors[] = 'Password harus sama keduanya';
		}

		if (empty($errors)):
			$update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama', kontak = '$kontak' WHERE id_user = '$id_user'");

			if ($password) {
				$pass = sha1($password);
				$update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama',  password = '$pass', kontak = '$kontak' WHERE id_user = '$id_user'");
			}
			if ($update) {
				$errors[] = 'Data berhasil diupdate';
			} else {
				$errors[] = 'Data gagal diupdate';
			}
		endif;

	endif;
	?>

	<?php
	$page = "Profile";
	require_once('template/header.php');
	?>

	<div class="d-sm-flex align-items-center justify-content-between">
		<div class="pagetitle d-flex align-items-center">
			<h1 class="me-3">Data Profile</h1>
			<nav>
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
					<li class="breadcrumb-item active" aria-current="page">Data Profile</li>
				</ol>
			</nav>
		</div>
	</div>

	<?php if (!empty($errors)): ?>
		<div class="alert alert-info">
			<?php foreach ($errors as $error): ?>
				<?php echo $error; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="form-group col-xl-12">
			<div class="card shadow-xl">
				<div class="card-body p-5">
					<div class="text-center mb-4">
						<img src="assets/img/user.png" alt="Profile" class="rounded-circle"
							style="width: 100px; height: 100px; object-fit: cover;">
						<h3 class="mt-3 mb-0"><?php echo $_SESSION['username']; ?></h3>
						<small class="text-muted">Edit your profile information</small>
					</div>

					<?php if (!$id_user): ?>
						<div class="alert alert-warning text-center">Data tidak ditemukan.</div>
					<?php else: ?>
						<?php
						$data = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id_user'");
						$cek = mysqli_num_rows($data);
						if ($cek <= 0):
							?>
							<div class="alert alert-warning text-center">Data tidak ditemukan.</div>
						<?php else: ?>
							<?php while ($d = mysqli_fetch_array($data)): ?>
								<form action="" method="post" class="row gy-2">
									<div class="col-md-6">
										<label class="form-label">Username</label>
										<input type="text" readonly class="form-control" value="<?php echo $d['username']; ?>"
											autocomplete="off">
									</div>

									<div class="col-md-6">
										<label class="form-label">Password</label>
										<input type="password" name="password" class="form-control" autocomplete="off">
									</div>

									<div class="col-md-6">
										<label class="form-label">Ulangi Password</label>
										<input type="password" name="password2" class="form-control" autocomplete="off">
									</div>

									<div class="col-md-6">
										<label class="form-label">Nama</label>
										<input type="text" name="nama" required class="form-control" value="<?php echo $d['nama']; ?>"
											autocomplete="off">
									</div>

									<div class="col-md-8">
										<label class="form-label">Kontak</label>
										<input type="text" name="kontak" required class="form-control"
											value="<?php echo $d['kontak']; ?>" autocomplete="off">
									</div>

									<div class="col-md-4">
										<label class="form-label">Role</label>
										<input type="text" readonly class="form-control" value="<?php
										if ($d['role'] == 1)
											echo 'Admin';
										elseif ($d['role'] == 2)
											echo 'User';
										elseif ($d['role'] == 3)
											echo 'Supplier';
										else
											echo 'Unknown';
										?>">
									</div>

									<div class="text-center mt-4">
										<button type="submit" name="submit" class="btn btn-sm btn-primary px-4 me-2"><i class="fa fa-save"></i>
											Simpan</button>
										<button type="reset" class="btn btn-sm btn-secondary px-4"><i class="fa fa-sync-alt"></i>
											Reset</button>
									</div>
								</form>
							<?php endwhile; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php
	require_once('template/footer.php');
} else {
	header('Location: login.php');
}
?>