<?php require_once ('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$ada_error = false;
$result = '';

$id_ukuran = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if (!$id_ukuran) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = mysqli_query($koneksi, "SELECT * FROM ukuran_rotan WHERE id_ukuran = '$id_ukuran'");
	$cek = mysqli_num_rows($query);

	if ($cek <= 0) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		mysqli_query($koneksi, "DELETE FROM ukuran_rotan WHERE id_ukuran = '$id_ukuran';");
		redirect_to('ukuran-rotan.php?status=sukses-hapus');
	}
}
?>
<?php
$page = "Ukuran Rotan";
require_once ('template/header.php');
?>
<?php if ($ada_error): ?>
	<?php echo '<div class="alert alert-danger">' . $ada_error . '</div>'; ?>
<?php endif; ?>
<?php
require_once ('template/footer.php');