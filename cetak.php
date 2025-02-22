<?php
require_once ('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user') {
	?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Data Perankingan</title>
		<style>
			body {
				font-family: Arial, sans-serif;
				margin: 0;
				padding: 0;
			}

			.container {
				width: 80%;
				margin: 0 auto;
				padding: 20px;
			}

			.header-wrapper {
				text-align: center;
				margin-bottom: 20px;
				border-bottom: 2px solid black;
				padding-bottom: 10px;
			}

			.header {
				display: flex;
				align-items: center;
				justify-content: center;
				margin-bottom: 10px;
			}

			.header img {
				width: 100px;
				height: auto;
				margin-right: 20px;
			}

			.header div {
				text-align: center;
			}

			.header h3 {
				margin: 0;
				font-size: 18px;
			}

			.header p {
				margin: 5px 0;
				font-size: 16px;
			}

			.header h5 {
				margin: 0;
				font-size: 14px;
			}

			.card {
				margin-top: 20px;
			}

			table {
				width: 100%;
				border-collapse: collapse;
				margin-top: 20px;
			}

			table,
			th,
			td {
				border: 1px solid black;
			}

			th,
			td {
				padding: 10px;
				text-align: center;
			}

			th {
				background-color: #f2f2f2;
				font-weight: bold;
			}

			.header h4 {
				margin: 20px 0;
				font-size: 20px;
				font-weight: bold;
			}
		</style>
	</head>

	<body onload="window.print();">
		<div class="container">
			<div class="header-wrapper">
				<div class="header">
					<div>
						<h3><b>SISTEM PENDUKUNG KEPUTUSAN</b></h3>
						<p>PEMILIHAN SUPPLIER BAHAN BAKU ROTAN</p>
						<p>DI KABUPATEN CIREBON</p>
						<hr>
					</div>
				</div>
				<h4>Hasil Akhir Perankingan</h4>
			</div>
			<div class="card">
				<table>
					<thead>
						<tr>
							<th>Nama Supplier</th>
							<th>Nilai</th>
							<th width="15%">Ranking</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($koneksi, "SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif=alternatif.id_alternatif ORDER BY hasil.nilai DESC");
						$no = 0;
						$prev_nilai = null;
						$rank = 0;
						while ($data = mysqli_fetch_array($query)) {
							if ($prev_nilai === null || $data['nilai'] != $prev_nilai) {
								$rank++;
							}
							?>
							<tr>
								<td align="left"><?= htmlspecialchars($data['nama']) ?></td>
								<td><?= htmlspecialchars($data['nilai']) ?></td>
								<td><?= $rank ?></td>
							</tr>
							<?php
							$prev_nilai = $data['nilai'];
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</body>

	</html>

	<?php
} else {
	header('Location: login.php');
}
?>