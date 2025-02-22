<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$page = "Penilaian";
require_once('template/header.php');

if (isset($_POST['tambah'])):
	$id_alternatif = $_POST['id_alternatif'];
	$id_kriteria = $_POST['id_kriteria'];
	$nilai = $_POST['nilai'];

	$errors = []; // Initialize errors

	// Validasi input
	if (empty($id_kriteria)) {
		$errors[] = 'ID kriteria tidak boleh kosong';
	}
	if (empty($id_alternatif)) {
		$errors[] = 'ID Alternatif kriteria tidak boleh kosong';
	}
	if (empty($nilai)) {
		$errors[] = 'Nilai kriteria tidak boleh kosong';
	}

	if (empty($errors)):
		$i = 0;
		$simpanBerhasil = true;
		foreach ($nilai as $key) {
			$simpan = mysqli_query($koneksi, "INSERT INTO penilaian (id_penilaian, id_alternatif, id_kriteria, nilai) VALUES (NULL, '$id_alternatif', '$id_kriteria[$i]', '$key')");
			if (!$simpan) {
				$simpanBerhasil = false;
			}
			$i++;
		}
		if ($simpanBerhasil) {
			$sts[] = 'Data berhasil disimpan';
		} else {
			$sts[] = 'Data gagal disimpan';
		}
	endif;
endif;

if (isset($_POST['edit'])):
	$id_alternatif = $_POST['id_alternatif'];
	$id_kriteria = $_POST['id_kriteria'];
	$nilai = $_POST['nilai'];

	$errors = []; // Initialize errors

	// Validasi input
	if (empty($id_kriteria)) {
		$errors[] = 'ID kriteria tidak boleh kosong';
	}
	if (empty($id_alternatif)) {
		$errors[] = 'ID Alternatif kriteria tidak boleh kosong';
	}
	if (empty($nilai)) {
		$errors[] = 'Nilai kriteria tidak boleh kosong';
	}

	if (empty($errors)):
		$i = 0;
		$updateBerhasil = true;
		mysqli_query($koneksi, "DELETE FROM penilaian WHERE id_alternatif = '$id_alternatif';");
		foreach ($nilai as $key) {
			$simpan = mysqli_query($koneksi, "INSERT INTO penilaian (id_penilaian, id_alternatif, id_kriteria, nilai) VALUES (NULL, '$id_alternatif', '$id_kriteria[$i]', '$key')");
			if (!$simpan) {
				$updateBerhasil = false;
			}
			$i++;
		}
		if ($updateBerhasil) {
			$sts[] = 'Data berhasil diupdate';
		} else {
			$sts[] = 'Data gagal diupdate';
		}
	endif;
endif;
?>

<div class="d-sm-flex align-items-center justify-content-between">
	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Data Penilaian</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Data Penilaian</li>
			</ol>
		</nav>
	</div>
</div>

<?php if (!empty($sts)): ?>
	<div class="alert alert-info">
		<?php foreach ($sts as $st): ?>
			<?php echo $st; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title"></h5>
		<div class="table-responsive">
			<table class="table table-striped table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr align="center">
						<th width="5%">No</th>
						<th width="20%">Alternatif</th>
						<th>Alamat</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					$query = mysqli_query($koneksi, "SELECT * FROM alternatif");
					while ($data = mysqli_fetch_array($query)) {
						?>
						<tr align="center">
							<td><?= $no ?></td>
							<td align="left"><?= $data['nama'] ?></td>
							<td align="left"><?= $data['alamat'] ?></td>
							<?php
							$id_alternatif = $data['id_alternatif'];
							$q = mysqli_query($koneksi, "SELECT * FROM penilaian WHERE id_alternatif='$id_alternatif'");
							$cek_tombol = mysqli_num_rows($q);
							?>
							<td>
								<?php if ($cek_tombol == 0) { ?>
									<a data-bs-toggle="modal" href="#set<?= $data['id_alternatif'] ?>"><i
											class="bi bi-plus-square"></i>Input</a>
								<?php } else { ?>
									<a data-bs-toggle="modal" href="#edit<?= $data['id_alternatif'] ?>"><i
											class="bi bi-pencil-square"></i> Edit</a>
								<?php } ?>
							</td>
						</tr>

						<!-- Modal -->
						<div class="modal fade" id="set<?= $data['id_alternatif'] ?>" tabindex="-1" role="dialog"
							aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="myModalLabel">Input Penilaian</h5>
									</div>
									<form action="" method="post">
										<div class="modal-body">
											<?php
											$q2 = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
											while ($d = mysqli_fetch_array($q2)) {
												?>
												<input type="text" name="id_alternatif" value="<?= $data['id_alternatif'] ?>"
													hidden>
												<input type="text" name="id_kriteria[]" value="<?= $d['id_kriteria'] ?>" hidden>
												<div class="form-group">
													<label class="font-weight-bold">(<?= $d['kode_kriteria'] ?>)
														<?= $d['nama'] ?></label>
													<?php
													if ($d['ada_pilihan'] == 1) {
														?>
														<select name="nilai[]" class="form-control" required>
															<option value="">--Pilih--</option>
															<?php
															$id_kriteria = $d['id_kriteria'];
															$q3 = mysqli_query($koneksi, "SELECT * FROM sub_kriteria WHERE id_kriteria = '$id_kriteria' ORDER BY nilai ASC");
															while ($d3 = mysqli_fetch_array($q3)) {
																?>
																<option value="<?= $d3['id_sub_kriteria'] ?>"><?= $d3['nama'] ?>
																</option>
															<?php } ?>
														</select>
														<?php
													} else {
														?>
														<input type="number" name="nilai[]" class="form-control" step="0.001"
															required autocomplete="off">
														<?php
													}
													?>
												</div>
											<?php } ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-warning btn-sm"><a href="list-penilaian.php"><i
														class="fa fa-times"></i> Batal</a></button>
											<button type="submit" name="tambah" class="btn btn-success btn-sm"><i
													class="fa fa-save"></i> Simpan</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<!-- Modal -->
						<div class="modal fade" id="edit<?= $data['id_alternatif'] ?>" tabindex="-1" role="dialog"
							aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="myModalLabel">Edit Penilaian</h5>
									</div>
									<form action="" method="post">
										<div class="modal-body">
											<?php
											$q2 = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
											while ($d = mysqli_fetch_array($q2)) {
												$id_kriteria = isset($d['id_kriteria']) ? $d['id_kriteria'] : '';
												$id_alternatif = isset($data['id_alternatif']) ? $data['id_alternatif'] : '';

												// Pastikan $id_kriteria dan $id_alternatif tidak kosong sebelum melakukan query
												if ($id_kriteria && $id_alternatif) {
													$q4 = mysqli_query($koneksi, "SELECT * FROM penilaian WHERE id_alternatif='$id_alternatif' AND id_kriteria='$id_kriteria'");
													$d4 = mysqli_fetch_array($q4);

													?>
													<input type="text" name="id_alternatif" value="<?= $id_alternatif ?>" hidden>
													<input type="text" name="id_kriteria[]" value="<?= $id_kriteria ?>" hidden>
													<div class="form-group">
														<label
															class="font-weight-bold">(<?= isset($d['kode_kriteria']) ? $d['kode_kriteria'] : '' ?>)
															<?= isset($d['nama']) ? $d['nama'] : '' ?></label>
														<?php
														if (isset($d['ada_pilihan']) && $d['ada_pilihan'] == 1) {
															?>
															<select name="nilai[]" class="form-control" required>
																<option value="">--Pilih--</option>
																<?php
																$q3 = mysqli_query($koneksi, "SELECT * FROM sub_kriteria WHERE id_kriteria = '$id_kriteria' ORDER BY nilai ASC");
																while ($d3 = mysqli_fetch_array($q3)) {
																	?>
																	<option
																		value="<?= isset($d3['id_sub_kriteria']) ? $d3['id_sub_kriteria'] : '' ?>"
																		<?php if (isset($d3['id_sub_kriteria']) && isset($d4['nilai']) && $d3['id_sub_kriteria'] == $d4['nilai']) {
																			echo "selected";
																		} ?>>
																		<?= isset($d3['nama']) ? $d3['nama'] : 'Undefined' ?>
																	</option>
																	<?php
																}
																?>
															</select>
															<?php
														} else {
															?>
															<input type="number" name="nilai[]" class="form-control" step="0.001"
																value="<?= isset($d4['nilai']) ? $d4['nilai'] : '' ?>" required
																autocomplete="off">
															<?php
														}
														?>
													</div>
													<?php
												}
											}
											?>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-warning btn-sm"><a href="list-penilaian.php"><i
														class="fa fa-times"></i> Batal</a></button>
											<button type="submit" name="edit" class="btn btn-success btn-sm"><i
													class="fa fa-save"></i> Update</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<?php
						$no++;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
?>