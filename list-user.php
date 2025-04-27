<?php require_once('includes/init.php');
cek_login($role = array(1));

$page = "User";
require_once('template/header.php');
?>


<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data User</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Data User</li>
			</ol>
		</nav>
	</div>

	<a href="tambah-user.php" class="btn btn-success btn-sm">Tambah Data </a>
</div>

<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = '';
switch ($status):
	case 'sukses-baru':
		$msg = 'Data berhasil disimpan';
		break;
	case 'sukses-hapus':
		$msg = 'Data berhasil dihapus';
		break;
	case 'sukses-edit':
		$msg = 'Data berhasil diupdate';
		break;
endswitch;

if ($msg):
	echo '<div class="alert alert-info">' . $msg . '</div>';
endif;
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title m-0"></h5>
		<div class="table-responsive">
			<table id="dataTable" class="table table-striped table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr align="center">
						<th width="5%">No</th>
						<th>Username</th>
						<th>Nama</th>
						<th>Role</th>
						<th>Bergabung Pada</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$no = 0;
					$query = mysqli_query($koneksi, "SELECT * FROM user");
					while ($data = mysqli_fetch_array($query)):
						$no++;
						?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td><?php echo $data['username']; ?></td>
							<td><?php echo $data ['nama'] ?> </td>
							<td>
								<?php
								if ($data['role'] == 1) {
									echo 'Administrator';
								} elseif ($data['role'] == 2) {
									echo 'Supplier';
								}
								?>
							</td>
							<td><?php echo $data['created_at']; ?> </td>
							<td>
								<div class="d-flex gap-2 justify-content-center">
									<!-- Tombol Edit -->
									<a href="edit-user.php?id=<?php echo $data['id_user']; ?>"
										class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit Data">
										<i class="bi bi-pencil-square"></i>
									</a>

									<!-- Tombol Hapus -->
									<a href="hapus-user.php?id=<?php echo $data['id_user']; ?>"
										class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
										onclick="return confirm('Apakah Anda yakin ingin menghapus data user ? Semua data terkait dengan user ini akan terhapus')"
										title="Hapus Data">
										<i class="bi bi-trash"></i>
									</a>
								</div>

							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
?>