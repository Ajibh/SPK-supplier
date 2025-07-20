<?php
require_once('includes/init.php');

if (isset($_SESSION['id_user']) || isset($_SESSION['username'])) {
    // Jika sudah login, redirect ke dashboard atau halaman lain
    header("Location: dashboard.php"); // Ganti dengan halaman yang sesuai
    exit();
}

$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
$kontak = isset($_POST['kontak']) ? trim($_POST['kontak']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$role = 2; // Default role supplier

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Validasi input
    if (!$username) {
        $errors[] = 'Username tidak boleh kosong';
    }
    if (!$password) {
        $errors[] = 'Password tidak boleh kosong';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Password dan konfirmasi password tidak sesuai';
    }
    if (!$nama) {
        $errors[] = 'Nama perusahaan tidak boleh kosong';
    }
    if (!$alamat) {
        $errors[] = 'Alamat/Lokasi perusahaan tidak boleh kosong';
    }
    if (!$kontak) {
        $errors[] = 'Kontak tidak boleh kosong';
    }

    if (empty($errors)) {
        // Cek apakah username sudah digunakan
        $username = mysqli_real_escape_string($koneksi, $username);
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
        $cek = mysqli_num_rows($query);

        if ($cek > 0) {
            $errors[] = 'Username sudah digunakan!';
        } else {
            $hashed_password = sha1($password);

            // Simpan data ke tabel users
            $queryUser = "INSERT INTO user (username, password, nama, email, role, created_at, updated_at) 
                          VALUES ('$username', '$hashed_password','$nama' ,'$email','$role', NOW(), NOW())";
            $resultUser = mysqli_query($koneksi, $queryUser);

            if ($resultUser) {
                // Ambil id_user yang baru dimasukkan
                $id_user = mysqli_insert_id($koneksi);

                // Simpan data ke tabel supplier
                $querySupplier = "INSERT INTO supplier (id_user, nama, email, kontak, alamat) 
                VALUES ('$id_user', '$nama', '$email', '$kontak', '$alamat')";
                $resultSupplier = mysqli_query($koneksi, $querySupplier);
                if ($resultSupplier) {
                    // Ambil id_supplier dari tabel supplier berdasarkan id_user
                    $getSupplierId = mysqli_query($koneksi, "SELECT id_supplier FROM supplier WHERE id_user = '$id_user'");
                    $supplierData = mysqli_fetch_assoc($getSupplierId);
                    $id_supplier = $supplierData['id_supplier'] ?? null;

                    // Simpan ke session
                    $_SESSION["id_user"] = $id_user;
                    $_SESSION["username"] = $username;
                    $_SESSION["role"] = $role;
                    $_SESSION["id_supplier"] = $id_supplier; // ← ini penting untuk akses halaman data rotan

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $errors[] = 'Registrasi gagal di tabel supplier: ' . mysqli_error($koneksi);
                }
            } else {
                $errors[] = 'Registrasi gagal di tabel user: ' . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SIPEKTRA | Register </title>
    <link href="assets/img/lambang.png" rel="icon" />
    <link href="assets/img/lambang.png" rel="apple-touch-icon" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet" />
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <section class="section register w-100 py-4">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h5 class="card-title text-center fs-4">REGISTER</h5>
                            <p class="text-center">Silakan isi formulir di bawah ini untuk mendapatkan akses ke dalam
                                sistem.</p>
                            <?php if (!empty($errors)): ?>
                                <?php foreach ($errors as $error): ?>
                                    <div class="alert alert-danger text-center p-1"><?php echo $error; ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <form class="user d-flex flex-column align-items-center" action="" method="post">
                                <div class="mb-1 w-100">
                                    <label for="yourUsername" class="form-label">Username</label>
                                    <input required autocomplete="off" type="text" name="username" class="form-control"
                                        id="yourUsername" placeholder="Masukan Username"
                                        value="<?php echo htmlspecialchars($username); ?>" />
                                </div>

                                <div class="row w-100">
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="yourPassword" class="form-label small">Password</label>
                                        <div class="input-group">
                                            <input required autocomplete="off" type="password" name="password"
                                                class="form-control" id="yourPassword" placeholder="Masukan Password" />
                                            <span class="input-group-text"
                                                onclick="togglePassword('yourPassword', 'eyeIcon1')"
                                                style="cursor:pointer;">
                                                <i id="eyeIcon1" class="bi bi-eye"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="confirmPassword" class="form-label small">Konfirmasi
                                            Password</label>
                                        <div class="input-group">
                                            <input required autocomplete="off" type="password" name="confirm_password"
                                                class="form-control" id="confirmPassword"
                                                placeholder="Konfirmasi Password" />
                                            <span class="input-group-text"
                                                onclick="togglePassword('confirmPassword', 'eyeIcon2')"
                                                style="cursor:pointer;">
                                                <i id="eyeIcon2" class="bi bi-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-1 w-100">
                                    <label for="yourCompany" class="form-label">Nama Perusahaan</label>
                                    <input required autocomplete="off" type="text" name="nama" class="form-control"
                                        id="yourCompany" placeholder="Nama Perusahaan"
                                        value="<?php echo htmlspecialchars($nama); ?>" />
                                </div>

                                <div class="mb-1 w-100">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea required autocomplete="off" name="alamat" class="form-control" id="alamat"
                                        rows="3"
                                        placeholder="Alamat/Lokasi"><?php echo htmlspecialchars($alamat); ?></textarea>
                                </div>

                                <div class="mb-1 w-100">
                                    <label for="kontak" class="form-label">Kontak</label>
                                    <input required autocomplete="off" type="text" name="kontak" class="form-control"
                                        id="kontak" placeholder="Masukan No Hp"
                                        value="<?php echo htmlspecialchars($kontak); ?>" />
                                </div>

                                <div class="mb-1 w-100">
                                    <label for="email" class="form-label">Email</label>
                                    <input autocomplete="off" type="email" name="email" class="form-control"
                                        id="email" placeholder="Masukan Email"
                                        value="<?php echo htmlspecialchars($email); ?>" />
                                </div>

                                <button name="submit" type="submit"
                                    class="btn btn-primary btn-sm w-100 mt-3">Daftar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <script>
        function togglePassword(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var eyeIcon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            }
        }
    </script>
</body>

</html>