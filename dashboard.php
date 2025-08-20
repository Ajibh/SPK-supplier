<?php
require_once('includes/init.php');

$user_role = get_role();
if (!in_array($user_role, ['admin', 'supplier'])) {
	header('Location: landing-page.php');
	exit;
}

$page = "Dashboard";
require_once('template/header.php');
?>

<div class="pagetitle d-flex align-items-center">
	<h1 class="me-3">Dashboard</h1>
	<nav>
		<ol class="breadcrumb mb-0">
			<li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
			<li class="breadcrumb-item active">Dashboard</li>
		</ol>
	</nav>
</div>

<div class="alert alert-info alert-dismissible fade show" role="alert">
	Selamat datang <span class="text-uppercase"><b><?= $_SESSION['username']; ?>!</b></span>
	<?php if ($user_role == 'admin'): ?>
		Anda bisa mengoperasikan sistem dengan wewenang tertentu melalui pilihan menu di bawah.
	<?php endif; ?>
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<section class="section dashboard">
	<div class="container">
		<div class="row ">
			<?php if ($user_role == 'admin'): ?>
				<?php
				$admin_cards = [
					['title' => 'Data Jenis Rotan', 'href' => 'jenis-rotan.php', 'icon' => 'bi-tags'],
					['title' => 'Data Ukuran Rotan', 'href' => 'ukuran-rotan.php', 'icon' => 'bi-aspect-ratio'],
					['title' => 'Preset Bobot SAW', 'href' => 'preset-bobot.php', 'icon' => 'bi-sliders'],
					['title' => 'Data Supplier', 'href' => 'list-alternatif.php', 'icon' => 'bi-people-fill'],
					['title' => 'Data Pengunjung', 'href' => 'data-pengunjung.php', 'icon' => 'bi-bar-chart'],
				];
				?>
			<?php elseif ($user_role == 'supplier'): ?>
				<?php
				$admin_cards = [
					['title' => 'Data Rotan', 'href' => 'data-rotan.php', 'icon' => 'bi-box-seam'],
					['title' => 'Profile', 'href' => 'list-profile.php', 'icon' => 'bi-person-circle'],
				];
				?>
			<?php endif; ?>

			<?php foreach ($admin_cards as $card): ?>
				<div class="col-lg-3 col-md-6">
					<div class="card info-card sales-card">
						<div class="card-body">
							<h5 class="card-title"><?= $card['title'] ?></h5>
							<div class="d-flex align-items-center justify-content-between">
								<div class="ps-3">
									<a href="<?= $card['href'] ?>" class="text-muted small pt-2 ps-1">Lihat Data</a>
								</div>
								<div class="card-icon rounded d-flex align-items-center justify-content-center">
									<i class="bi <?= $card['icon'] ?>"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php require_once('template/footer.php'); ?>
