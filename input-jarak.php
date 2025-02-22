<?php require_once('includes/init.php'); ?>

<?php
$page = "Alternatif";
require_once('template/header.php');

?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Input Nilai Jarak</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Input Nilai Jarak</li>
			</ol>
		</nav>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<h5 class="card-title"></h5>
		<div class="table-responsive">
			<table class="table" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr align="center">
						<th width="5%">No</th>
						<th width="20%">Nama Supplier</th>
						<th>Alamat</th>
						<th width="15%">Nilai Jarak</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 0;
					$query = mysqli_query($koneksi, "SELECT * FROM alternatif");
					$ku = mysqli_query($koneksi, "SELECT * FROM kriteria");

					while ($data = mysqli_fetch_array($query)):
						$no++;
						?>
						<tr align="center">
							<td><?php echo $no; ?></td>
							<td align="left"><?php echo $data['nama']; ?></td>
							<td align="left"><?php echo $data['alamat']; ?></td>

							<?php
							while ($dku = mysqli_fetch_array($ku)):
								?>

								<!-- <td><?php print_r($dku) ?></td> -->
								<td>
									<select name="nilai[]" class="form-control" required>
										<option value="">--Pilih--</option>
										<?php
										// Mengambil id_kriteria dari data alternatif
										$id_kriteria = $dku['id_kriteria'];
										$q3 = mysqli_query($koneksi, "SELECT * FROM sub_kriteria WHERE id_kriteria = '$id_kriteria' ORDER BY nilai ASC");
										while ($d3 = mysqli_fetch_array($q3)) {
											?>
											<option value="<?= $d3['id_sub_kriteria'] ?>"><?= $d3['nama'] ?></option>
										<?php } ?>
									</select>
								</td>
							<?php endwhile; ?>
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