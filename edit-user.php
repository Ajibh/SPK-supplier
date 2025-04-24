<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

$ada_error = false;
$result = '';

$id_user = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if (isset($_POST['submit'])):
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$nama = $_POST['nama'];
	$email = $_POST['email'];


	if ($password && ($password != $password2)) {
		$errors[] = 'Password harus sama keduanya';
	}

	if (empty($errors)):
		$update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama', email = '$email' WHERE id_user = '$id_user' ");

		if ($password) {
			$pass = sha1($password);
			$update = mysqli_query($koneksi, "UPDATE user SET nama = '$nama',  password = '$pass', email = '$email' WHERE id_user = '$id_user'");
		}
		if ($update) {
			redirect_to('list-user.php?status=sukses-edit');
		} else {
			$errors[] = 'Data gagal diupdate';
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
				<li class="breadcrumb-item active" aria-current="page">Edit Data User</li>
			</ol>
		</nav>
	</div>

	<a href="list-user.php" class="btn btn-secondary btn-sm btn-icon-split">
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

<form action="edit-user.php?id=<?= $id_user; ?>" method="post">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Edit Data User</h5>

			<?php if (!$id_user): ?>
				<div class="alert alert-danger">Data tidak ada</div>
			<?php else: ?>
				<?php
				$data = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id_user'");
				$cek = mysqli_num_rows($data);

				if ($cek <= 0): ?>
					<div class="alert alert-danger">Data tidak ada</div>
				<?php else:
					while ($d = mysqli_fetch_array($data)): ?>

						<div class="row">
							<div class="form-group col-md-6 mb-3">
								<label class="font-weight-bold">Username</label>
								<input autocomplete="off" type="text" readonly required value="<?= $d['username']; ?>"
									class="form-control" />
							</div>

							<div class="form-group col-md-6 mb-3">
								<label class="font-weight-bold">Password</label>
								<input autocomplete="off" type="password" name="password" class="form-control" />
							</div>

							<div class="form-group col-md-6 mb-3">
								<label class="font-weight-bold">Ulangi Password</label>
								<input autocomplete="off" type="password" name="password2" class="form-control" />
							</div>

							<div class="form-group col-md-6 mb-3">
								<label class="font-weight-bold">Nama</label>
								<input autocomplete="off" type="text" name="nama" required value="<?= $d['nama']; ?>"
									class="form-control" />
							</div>

							<div class="form-group col-md-6 mb-3">
								<label class="font-weight-bold">E-Mail</label>
								<input autocomplete="off" type="email" name="email" required value="<?= $d['email']; ?>"
									class="form-control" />
							</div>

							<div class="form-group col-md-6">
								<label class="font-weight-bold">Level</label>
								<input type="text" class="form-control" value="<?php
								if ($d['role'] == 1) {
									echo 'Administrator';
								} elseif ($d['role'] == 2) {
									echo 'Supplier';
								}
								?>" readonly />
							</div>
						</div>
					</div>
					<div class="card-footer text-right">
						<button name="submit" value="submit" type="submit" class="btn btn-success btn-sm">
							<i class="fa fa-save"></i> Update
						</button>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	</div>
</form>

<?php
require_once('template/footer.php');
?>