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
			<table id="dataTable" class="table table-striped table-bordered" width="100%" cellspacing="0">
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
							<td align="left">
								<?php echo !empty($data['nama']) ? htmlspecialchars($data['nama']) : 'Tidak ada'; ?>
							</td>
							<td align="left">
								<?php echo !empty($data['alamat']) ? htmlspecialchars($data['alamat']) : 'Tidak ada'; ?>
							</td>
							<td>
								<div>
									<a data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"
										href="detail-supplier.php?id=<?php echo $data['id_supplier']; ?>">
										<button class="btn btn-secondary btn-sm">
											<i class="bi bi-eye" style="margin-right:5px;"></i> Detail
										</button>
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

<?php require_once('template/footer.php'); ?>