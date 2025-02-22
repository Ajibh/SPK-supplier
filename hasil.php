<?php
require_once ('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user') {

	$page = "Hasil";
	require_once ('template/header.php');
	?>

	<div class="d-sm-flex align-items-center justify-content-between">
		<div class="pagetitle m-0">
			<h1>Data Perankingan</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.php">Home</a></li>
					<li class="breadcrumb-item active">Data Ranking</li>
				</ol>
			</nav>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<h5 class="card-title"></h5>
			<div class="table-responsive">
				<table class="table table-striped" width="100%" cellspacing="0">
					<thead>
						<tr align="center">
							<th>Nama Guru</th>
							<th>Nilai</th>
							<th width="15%">Rank</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 0;
						$prev_value = null;
						$rank = 0;
						$query = mysqli_query($koneksi, "SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif=alternatif.id_alternatif ORDER BY hasil.nilai DESC");
						while ($data = mysqli_fetch_array($query)) {
							if ($prev_value === null || $data['nilai'] != $prev_value) {
								$rank++;
							}
							?>
							<tr align="center">
								<td align="left"><?= $data['nama'] ?></td>
								<td><?= $data['nilai'] ?></td>
								<td><?= $rank; ?></td>
							</tr>
							<?php
							$prev_value = $data['nilai'];
							$no++;
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<?php
	require_once ('template/footer.php');
} else {
	header('Location: login.php');
}
?>