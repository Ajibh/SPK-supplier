<div class="mt-4 pt-3 bg-light rounded">
    <h5 class="text-center">Apa yang paling penting bagi Anda?</h5>
    <div class="d-flex justify-content-center gap-3">
        <label><input type="checkbox" id="harga" onchange="hitungBobot()"> Harga Murah</label>
        <label><input type="checkbox" id="stok" onchange="hitungBobot()"> Stok Banyak</label>
        <label><input type="checkbox" id="minimal" onchange="hitungBobot()"> Minimal Pembelian
            Rendah</label>
    </div>

    <div class="mt-3 text-center">
        <h6>Bobot yang dihitung:</h6>
        <p>Harga: <span id="bobot_harga">0</span></p>
        <p>Stok: <span id="bobot_stok">0</span></p>
        <p>Minimal Pembelian: <span id="bobot_minimal">0</span></p>
        <p id="hasil" class="fw-bold">Total Bobot: <span id="total_bobot">0</span></p>
    </div>
</div>

<script>
    function hitungBobot() {
        let harga = document.getElementById("harga").checked;
        let stok = document.getElementById("stok").checked;
        let minimal = document.getElementById("minimal").checked;

        let pilihan = [harga, stok, minimal].filter(p => p).length;
        let bobot = pilihan > 0 ? (1 / pilihan).toFixed(2) : 0;

        document.getElementById("bobot_harga").innerText = harga ? bobot : "0";
        document.getElementById("bobot_stok").innerText = stok ? bobot : "0";
        document.getElementById("bobot_minimal").innerText = minimal ? bobot : "0";

        let totalBobot = harga * bobot + stok * bobot + minimal * bobot;
        document.getElementById("total_bobot").innerText = totalBobot.toFixed(2);

        // Ubah warna total jika tidak 1
        let hasil = document.getElementById("hasil");
        let btnLanjut = document.getElementById("btnLanjut");

        if (totalBobot.toFixed(2) == 1.00) {
            hasil.style.color = "green";
            btnLanjut.disabled = false; // Aktifkan tombol
        } else {
            hasil.style.color = "red";
            btnLanjut.disabled = true; // Nonaktifkan tombol
        }
    }
</script>


<?php require_once('includes/init.php');
cek_login($role = array(1, 2));
$id_user = $_SESSION["id_user"];

$page = "Profile";
require_once('template/header.php');

if (isset($_POST['submit_password'])) {
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	if ($password !== $password2) {
		$_SESSION['pesan_error'] = "Password tidak cocok!";
	} else {
		$pass_hash = sha1($password); // Menggunakan SHA1
		$updatePass = mysqli_query($koneksi, "UPDATE user SET password='$pass_hash' WHERE id_user='$id_user'");

		if ($updatePass) {
			redirect_to('list-profile.php?status=update-password');
		} else {
			$_SESSION['pesan_error'] = "Gagal mengubah password.";
		}
	}
}

// Menampilkan pesan status
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = '';
switch ($status) {
	case 'update-password':
		$msg = 'Password berhasil diubah.';
		break;
	case 'update-profile':
		$msg = 'Profil berhasil diperbarui.';
		break;
}

if (!empty($msg)) {
	echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'
		. htmlspecialchars($msg) .
		'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>';
}

// Menampilkan pesan error dari session
if (isset($_SESSION['pesan_error'])) {
	echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
		. htmlspecialchars($_SESSION['pesan_error']) .
		'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>';
	unset($_SESSION['pesan_error']);
}

?>