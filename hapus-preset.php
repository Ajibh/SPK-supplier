<?php
require_once('includes/init.php');
cek_login($role = array(1));

$ada_error = false;

// Ambil id_preset dari parameter URL
$id_preset = (isset($_GET['id'])) ? trim($_GET['id']) : '';

// Cek apakah id_preset ada
if (!$id_preset) {
    $ada_error = 'Maaf, data tidak dapat diproses.';
} else {
    // Cek apakah id_preset ada di database
    $query = mysqli_query($koneksi, "SELECT * FROM preset_bobot WHERE id_preset = '$id_preset'");
    $cek = mysqli_num_rows($query);

    if ($cek <= 0) {
        $ada_error = 'Preset tidak ditemukan.';
    } else {
        // Hapus data preset
        mysqli_query($koneksi, "DELETE FROM preset_bobot WHERE id_preset = '$id_preset';");

        // Redirect ke halaman preset dengan status sukses
        header("Location: preset-bobot.php?status=sukses-hapus");
        exit();
    }
}

$page = "Preset Bobot";
require_once('template/header.php');
?>
<?php if ($ada_error): ?>
    <div class="alert alert-danger"><?php echo $ada_error; ?></div>
<?php endif;
require_once('template/footer.php');
?>
