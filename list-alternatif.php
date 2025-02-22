<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$page = "Alternatif";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data Supplier</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Data Supplier</li>
			</ol>
		</nav>
	</div>
</div>

<div class="mb-2">
	<a href="tambah-alternatif.php" class="btn btn-success btn-sm"><i class="bi bi-plus"></i>Tambah </a>
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
	echo '<div class="alert alert-warning">' . $msg . '</div>';
endif;
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title"></h5>
		<div class="table-responsive">
			<table class="table table-striped table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr align="center">
						<th width="5%">No</th>
						<th width="20%">Nama Supplier</th>
						<th>Alamat</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 0;
					$query = mysqli_query($koneksi, "SELECT * FROM supplier "); // Hanya supplier
					while ($data = mysqli_fetch_array($query)):
						$no++;
						?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td align="left"><?php echo htmlspecialchars($data['nama']); ?></td>
							<td align="left"><?php echo htmlspecialchars($data['alamat']); ?></td>
							<td>
								<div>
									<a data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"
										href="detail-supplier.php?id=<?php echo $data['id_supplier']; ?>">
										<i class="bi bi-eye" style="margin-right:10px;"></i></a>
									<a data-toggle="tooltip" data-placement="bottom" title="Edit Data"
										href="edit-alternatif.php?id=<?php echo $data['id_supplier']; ?>"><i
											class="bi bi-pencil-square" style="margin-right:10px;"></i></a>
									<a data-toggle="tooltip" data-placement="bottom" title="Hapus Data"
										href="hapus-alternatif.php?id=<?php echo $data['id_supplier']; ?>"
										onclick="return confirm ('Apakah anda yakin untuk menghapus data ini?')"><i
											class="bi bi-trash"></i></a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php require_once('template/footer.php'); ?>