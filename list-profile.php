<?php
require_once('includes/init.php');
cek_login($role = array(1, 2));
$id_user = $_SESSION["id_user"];

$page = "Profile";
require_once('template/header.php');

$errors = [];

// menangani pengisian form ubah password
if (isset($_POST['submit_password'])):
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	if (!$password || !$password2):
		$errors[] = 'Password tidak boleh kosong';
	elseif ($password !== $password2):
		$errors[] = 'Password tidak cocok';
	else:
		$pass_hash = sha1($password); // Hati-hati: sha1 tidak disarankan untuk keamanan tinggi
		$update = mysqli_query($koneksi, "UPDATE user SET password = '$pass_hash' WHERE id_user = '$id_user'");

		if ($update):
			$errors[] = 'Password berhasil diubah';
		else:
			$errors[] = 'Gagal mengubah password';
		endif;
	endif;
endif;


// menangani pengisian form edit profile
if (isset($_POST['submit_profile'])):
	$id_user = $_POST['id_user'];
	$nama = $_POST['nama'];
	$kontak = $_POST['kontak'] ?? null;
	$email = $_POST['email'] ?? null;
	$alamat = $_POST['alamat'] ?? null;

	$errors = [];

	// Ambil role user
	$result = mysqli_query($koneksi, "SELECT role FROM user WHERE id_user = '$id_user'");
	$user = mysqli_fetch_assoc($result);
	$role = $user['role'] ?? null;

	if ($role != 1): // Supplier
		if (!$kontak || !$alamat):
			$errors[] = 'Kontak dan alamat wajib diisi';
		endif;
	endif;

	if (empty($errors)):
		// Update nama dan email di tabel user
		$updateUser = mysqli_query($koneksi, "UPDATE user SET nama = '$nama', email = '$email' WHERE id_user = '$id_user'");

		if ($role != 1):
			// Update atau insert ke tabel supplier
			$cekSupplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_user = '$id_user'");
			if (mysqli_num_rows($cekSupplier) > 0):
				$updateSupplier = mysqli_query($koneksi, "UPDATE supplier SET nama = '$nama', kontak = '$kontak', email = '$email', alamat = '$alamat' WHERE id_user = '$id_user'");
			else:
				$updateSupplier = mysqli_query($koneksi, "INSERT INTO supplier (id_user, nama, kontak, email, alamat) VALUES ('$id_user', '$nama', '$kontak', '$email', '$alamat')");
			endif;
		endif;

		if ($updateUser):
			$errors[] = 'Profil berhasil diperbarui';
		else:
			$errors[] = 'Gagal memperbarui profil';
		endif;
	endif;
endif;

?>

<div class="pagetitle d-flex align-items-center">
	<h1 class="me-3">Profile</h1>
	<nav>
		<ol class="breadcrumb mb-0">
			<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">Setting Profile</li>
		</ol>
	</nav>
</div>

<?php
// Tampilkan alert-nya
if (!empty($errors)):
	foreach ($errors as $msg):
		echo '<div class="alert alert-success">' . htmlspecialchars($msg) . '</div>';
	endforeach;
endif;
?>

<div class="card shadow-xl">
	<div class="card-body p-4">
		<ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#editProfile"
					type="button" role="tab">Edit Profile</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#changePassword"
					type="button" role="tab">Ubah Password</button>
			</li>
		</ul>

		<div class="tab-content" id="profileTabContent">
			<!-- Tab Edit Profile -->
			<div class="tab-pane fade show active" id="editProfile" role="tabpanel">
				<form action="" method="post">
					<input type="hidden" name="id_user" value="<?= $id_user; ?>">
					<?php
					$userData = mysqli_query($koneksi, "
			SELECT 
				u.username, 
				u.role, 
				u.nama AS user_nama, 
				u.email AS user_email, 
				s.nama AS supplier_nama, 
				s.kontak, 
				s.email AS supplier_email, 
				s.alamat
			FROM user u
			LEFT JOIN supplier s ON u.id_user = s.id_user
			WHERE u.id_user = '$id_user'
		");
					$data = mysqli_fetch_array($userData);
					$role = $data['role'];
					$nama = $data['user_nama'] ?? $data['supplier_nama'] ?? '';
					?>

					<div class="row align-items-start gy-4">
						<!-- Foto Profil -->
						<div class="col-md-4 text-center">
							<img src="assets/img/<?= !empty($data['foto']) ? $data['foto'] : 'user.png'; ?>"
								alt="Profile" class="rounded-circle shadow"
								style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;">
							<h4 class="mt-3 mb-1"><?= htmlspecialchars($nama); ?></h4>
							<small class="text-muted">Edit Informasi Profil-mu</small>
						</div>

						<!-- Form Input -->
						<div class="col-md-8">
							<div class="row gy-3">
								<div class="col-md-6">
									<label class="form-label">Username</label>
									<input type="text" class="form-control"
										value="<?= htmlspecialchars($data['username']); ?>" readonly>
								</div>

								<div class="col-md-6">
									<label class="form-label">Nama</label>
									<input type="text" name="nama" class="form-control"
										value="<?= htmlspecialchars($nama); ?>" required>
								</div>

								<div class="col-md-6">
									<label class="form-label">Email</label>
									<input type="email" name="email" class="form-control"
										value="<?= htmlspecialchars($data['user_email'] ?? $data['supplier_email'] ?? ''); ?>">
								</div>

								<?php if ($role != 1): ?>
									<div class="col-md-6">
										<label class="form-label">Kontak</label>
										<input type="text" name="kontak" class="form-control"
											value="<?= htmlspecialchars($data['kontak'] ?? ''); ?>" required>
									</div>
									<div class="col-12">
										<label class="form-label">Alamat</label>
										<textarea name="alamat" class="form-control" rows="3"
											required><?= htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
									</div>
								<?php endif; ?>

								<div class="col-12 text-end mt-3">
									<button type="submit" name="submit_profile" class="btn btn-primary btn-sm px-4">
										<i class="fa fa-save"></i> Update
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>


			<!-- Tab Ubah Password -->
			<div class="tab-pane fade" id="changePassword" role="tabpanel">
				<form action="" method="post" class="row gy-2">
					<input type="hidden" name="id_user" value="<?= $id_user; ?>">
					<div class="col-md-6">
						<label class="form-label">Password Baru</label>
						<input type="password" name="password" class="form-control" required>
					</div>
					<div class="col-md-6">
						<label class="form-label">Ulangi Password</label>
						<input type="password" name="password2" class="form-control" required>
					</div>
					<div class="text-center mt-3">
						<button type="submit" name="submit_password" class="btn btn-warning btn-sm px-4"><i
								class="fa fa-lock"></i> Ubah Password</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<?php
require_once('template/footer.php');
?>