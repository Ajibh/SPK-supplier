<?php
require_once('includes/init.php');

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'customer' || $user_role == 'supplier') {
	$page = "Dashboard";
	require_once('template/header.php');
	?>

	<div class="pagetitle d-flex align-items-center">
		<h1 class="me-3">Dashboard</h1>
		<nav>
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
			</ol>
		</nav>
	</div>

	<?php
	if ($user_role == 'admin') {
		?>

		<div class="alert alert-info alert-dismissible fade show" role="alert">
			Selamat datang <span class="text-uppercase"><b><?php echo $_SESSION['username']; ?>!</b></span> Anda bisa
			mengoperasikan sistem dengan wewenang tertentu melalui pilihan menu di bawah.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>

		<section class="section dashboard">
			<div class="mb-4">
				<div class="row g-4"> <!-- Menambahkan gap (g-4) antara card -->
					<div class="col-lg-3 col-md-6">
						<div class="card info-card sales-card">
							<div class="card-body">
								<h5 class="card-title">Data Jenis Rotan</h5>
								<div class="d-flex align-items-center justify-content-between">
									<div class="ps-3">
										<a href="jenis-rotan.php" class="text-muted small pt-2 ps-1">Lihat Data</a>
									</div>
									<div class="card-icon rounded d-flex align-items-center justify-content-center">
										<i class="bi bi-tags"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-6">
						<div class="card info-card sales-card">
							<div class="card-body">
								<h5 class="card-title">Data Ukuran Rotan</h5>
								<div class="d-flex align-items-center justify-content-between">
									<div class="ps-3">
										<a href="ukuran-rotan.php" class="text-muted small pt-2 ps-1">Lihat Data</a>
									</div>
									<div class="card-icon rounded d-flex align-items-center justify-content-center">
										<i class="bi bi-aspect-ratio"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-6">
						<div class="card info-card sales-card">
							<div class="card-body">
								<h5 class="card-title">Preset Bobot SAW</h5>
								<div class="d-flex align-items-center justify-content-between">
									<div class="ps-3">
										<a href="preset-bobot.php" class="text-muted small pt-2 ps-1">Lihat Data</a>
									</div>
									<div class="card-icon rounded d-flex align-items-center justify-content-center">
										<i class="bi bi-sliders"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-6">
						<div class="card info-card sales-card">
							<div class="card-body">
								<h5 class="card-title">Data Supplier</h5>
								<div class="d-flex align-items-center justify-content-between">
									<div class="ps-3">
										<a href="list-alternatif.php" class="text-muted small pt-2 ps-1">Lihat Data</a>
									</div>
									<div class="card-icon rounded d-flex align-items-center justify-content-center">
										<i class="bi bi-people-fill"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php

	} elseif ($user_role == 'supplier') {
		?>
		<div class="alert alert-info alert-dismissible fade show" role="alert">
			Selamat Datang <span class="text-uppercase"><b><?php echo $_SESSION['username']; ?>!</b></span>.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		<section class="section dashboard">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-6 mb-4">
						<div class="card info-card sales-card">
							<div class="card-body">
								<h5 class="card-title">Data Rotan</h5>
								<div class="d-flex align-items-center">
									<div class="card-icon rounded d-flex align-items-center justify-content-center">
										<i class="bi bi-box-seam"></i>
									</div>
									<div class="ps-3">
										<a href="data-rotan.php" class="text-muted small pt-2 ps-1">Lihat Data</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 mb-4">
						<div class="card info-card sales-card">
							<div class="card-body">
								<h5 class="card-title">Profile</h5>
								<div class="d-flex align-items-center">
									<div class="card-icon rounded d-flex align-items-center justify-content-center">
										<i class="bi bi-person-circle"></i>
									</div>
									<div class="ps-3">
										<a href="list-profile.php" class="text-muted small pt-2 ps-1">Lihat Data</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
	?>

	<?php
	require_once('template/footer.php');
} else {
	header('Location: landing-page.php');
}
?>