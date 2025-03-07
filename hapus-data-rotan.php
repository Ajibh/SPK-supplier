<?php
require_once('includes/init.php');
cek_login($role = array(3));

$ada_error = false;
$result = '';

// Ambil id_rotan dari parameter URL
$id_rotan = (isset($_GET['id'])) ? trim($_GET['id']) : '';

// Cek apakah id_rotan ada
if (!$id_rotan) {
    $ada_error = 'Maaf, data tidak dapat diproses.';
} else {
    // Cek apakah id_rotan ada di database
    $query = mysqli_query($koneksi, "SELECT * FROM data_rotan WHERE id_rotan = '$id_rotan'");
    $cek = mysqli_num_rows($query);

    if ($cek <= 0) {
        $ada_error = 'Maaf, data tidak dapat diproses.';
    } else {
        // Hapus data rotan
        mysqli_query($koneksi, "DELETE FROM data_rotan WHERE id_rotan = '$id_rotan';");

        // Redirect ke halaman data rotan dengan status sukses
        redirect_to('data-rotan.php?status=sukses-hapus');
    }
}
?>

<?php
$page = "Data Rotan";
require_once('template/header.php');
?>
<?php if ($ada_error): ?>
    <div class="alert alert-danger"><?php echo $ada_error; ?></div>
<?php endif; ?>
<?php require_once('template/footer.php'); ?>