<?php
require_once('includes/init.php');
cek_login($role = array(1));
$page = "Kriteria";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data Kriteria</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Data Kriteria</li>
			</ol>
		</nav>
	</div>
</div>
<!-- <div class="mb-2">
	<a href="tambah-kriteria.php" class="btn btn-success btn-sm"> + Tambah</a>
</div> -->

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
	echo '<div class="alert alert-warning p-2">' . $msg . '</div>';
endif;
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title"></h5>
		<div class="table-responsive">
			<table class="table table-striped table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr align="center">
						<th widht="5%" >No</th>
						<th>Kode Kriteria</th>
						<th>Nama Kriteria</th>
						<th>Type</th>
						<th>Bobot</th>
						<th>Cara Penilaian</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					$query = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
					while ($data = mysqli_fetch_array($query)):
						?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td><?php echo $data['kode_kriteria']; ?></td>
							<td align="left"><?php echo $data['nama']; ?></td>
							<td><?php echo $data['type']; ?></td>
							<td>
								<?php
								if (empty($data['bobot'])) {
									echo "-";
								} else {
									echo $data['bobot'];
								}
								?>
							</td>
							<td><?php echo ($data['ada_pilihan']) ? 'Pilihan Sub Kriteria' : 'Input Langsung'; ?></td>
						</tr>
						<?php
						$no++;
					endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
?>