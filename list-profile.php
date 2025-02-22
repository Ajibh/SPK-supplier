<?php require_once('includes/init.php');
$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user' || $user_role == 'supplier') {
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
		$email = $_POST['email'];

		if (!$nama) {
			$errors[] = 'Nama tidak boleh kosong';
		}

		if (!$email) {
			$errors[] = 'Email tidak boleh kosong';
		}

		if (!$id_user) {
			$errors[] = 'Id User salah';
		}

		if ($password && ($password != $password2)) {
			$errors[] = 'Password harus sama keduanya';
		}


		if (empty($errors)):
			$update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama', email = '$email' WHERE id_user = '$id_user'");

			if ($password) {
				$pass = sha1($password);
				$update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama',  password = '$pass', email = '$email' WHERE id_user = '$id_user'");
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
			<div class="card">
				<div class="card-body">
					<div class="profile-card pt-4 d-flex flex-column align-items-center">
						<img src="assets/img/user.png" alt="Profile" class="rounded" />
						<h3><?php echo $_SESSION['username']; ?></h3>
					</div>

					<?php
					if (!$id_user) {
						?>
						<div class="alert alert-danger">Data tidak ada</div>
						<?php
					} else {
						$data = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id_user'");
						$cek = mysqli_num_rows($data);
						if ($cek <= 0) {
							?>
							<div class="alert alert-danger">Data tidak ada</div>
							<?php
						} else {
							while ($d = mysqli_fetch_array($data)) {
								?>
								<h5 class="card-title">Edit Data Profile</h5>
								<form action="" method="post">
									<div class="row">
										<div class="form-group col-md-6">
											<label class="font-weight-bold">Username</label>
											<input autocomplete="off" type="text" readonly required
												value="<?php echo $d['username']; ?>" class="form-control" />
										</div>

										<div class="form-group col-md-6">
											<label class="font-weight-bold">Password</label>
											<input autocomplete="off" type="password" name="password" class="form-control" />
										</div>

										<div class="form-group col-md-6">
											<label class="font-weight-bold">Ulangi Password</label>
											<input autocomplete="off" type="password" name="password2" class="form-control" />
										</div>

										<div class="form-group col-md-6">
											<label class="font-weight-bold">Nama</label>
											<input autocomplete="off" type="text" name="nama" required value="<?php echo $d['nama']; ?>"
												class="form-control" />
										</div>

										<div class="form-group col-md-8">
											<label class="font-weight-bold">E-Mail</label>
											<input autocomplete="off" type="email" name="email" required
												value="<?php echo $d['email']; ?>" class="form-control" />
										</div>

										<div class="form-group col-md-4">
											<label class="font-weight-bold">Role</label>
											<input type="text" class="form-control" value="<?php
											if ($d['role'] == 1) {
												echo 'Admin';
											} elseif ($d['role'] == 2) {
												echo 'User';
											} elseif ($d['role'] == 3) {
												echo 'Supplier';
											} else {
												echo 'Unknown';
											}
											?>" readonly />
										</div>

									</div>
									<div class="card-footer text-right">
										<button name="submit" value="submit" type="submit" class="btn btn-success btn-sm"><i
												class="fa fa-save"></i> Update</button>
										<button type="reset" class="btn btn-info btn-sm"><i class="fa fa-sync-alt"></i> Reset</button>
									</div>
								</form>
								<?php
							}
						}
					}
					?>
				</div>
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