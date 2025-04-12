<?php
require_once('includes/init.php');

$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
$kontak = isset($_POST['kontak']) ? trim($_POST['kontak']) : '';
$role = isset($_POST['role']) ? $_POST['role'] : ''; // Tidak ada default, wajib memilih

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Konversi role menjadi angka
if ($role === 'customer') {
    $role = 2;
} elseif ($role === 'supplier') {
    $role = 3;
} else {
    $role = 0; // Jika role tidak valid
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
            // Simpan data ke tabel user
            $queryUser = "INSERT INTO user (username, password, nama, kontak, alamat, role) 
                          VALUES ('$username', '$hashed_password', '$nama', '$kontak', '$alamat', '$role')";
            $resultUser = mysqli_query($koneksi, $queryUser);

            if ($resultUser) {
                $id_user = mysqli_insert_id($koneksi);
                $id_supplier = null;

                // Jika user adalah supplier, tambahkan ke tabel supplier
                if ($role == 3) {
                    $querySupplier = "INSERT INTO supplier (id_user, nama, kontak, alamat) 
                                      VALUES ('$id_user', '$nama', '$kontak', '$alamat')";
                    $resultSupplier = mysqli_query($koneksi, $querySupplier);

                    if ($resultSupplier) {
                        $id_supplier = mysqli_insert_id($koneksi);
                    } else {
                        $errors[] = 'Gagal menambahkan supplier: ' . mysqli_error($koneksi);
                    }
                }

                if (empty($errors)) {
                    // Simpan data ke session
                    $_SESSION["id_user"] = $id_user;
                    $_SESSION["username"] = $username;
                    $_SESSION["role"] = $role;

                    // Simpan id_supplier jika user adalah supplier
                    if ($role == 3 && $id_supplier) {
                        $_SESSION["id_supplier"] = $id_supplier;
                    }

                    header("Location: dashboard.php");
                    exit();
                }

            } else {
                $errors[] = 'Registrasi gagal: ' . mysqli_error($koneksi);
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
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="assets/img/lambang.png" rel="icon" />
    <link href="assets/img/lambang.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet" />
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet" />
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet" />
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
                                <div class="mb-1 w-100">
                                    <label for="yourPassword" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input required autocomplete="off" type="password" name="password"
                                            class="form-control" id="yourPassword" placeholder="Masukan Password" />
                                        <span class="input-group-text"
                                            onclick="togglePassword('yourPassword', 'eyeIcon1')">
                                            <i id="eyeIcon1" class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-1 w-100">
                                    <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <input required autocomplete="off" type="password" name="confirm_password"
                                            class="form-control" id="confirmPassword"
                                            placeholder="Konfirmasi Password" />
                                        <span class="input-group-text"
                                            onclick="togglePassword('confirmPassword', 'eyeIcon2')">
                                            <i id="eyeIcon2" class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-1 w-100">
                                    <label for="yourCompany" class="form-label">Nama Perusahaan</label>
                                    <input required autocomplete="off" type="text" name="nama" class="form-control"
                                        id="yourCompany" placeholder="Nama Perusahaan"
                                        value="<?php echo htmlspecialchars($nama); ?>" />
                                </div>
                                <div class="mb-1 w-100">
                                    <label for="yourCompany" class="form-label">Alamat</label>
                                    <textarea required autocomplete="off" name="alamat" class="form-control"
                                        id="yourCompany" rows="3"
                                        placeholder="Alamat/Lokasi"><?php echo htmlspecialchars($alamat); ?></textarea>
                                </div>
                                <div class="mb-1 w-100">
                                    <label for="yourEmail" class="form-label">Kontak</label>
                                    <input required autocomplete="off" type="kontak" name="kontak" class="form-control"
                                        id="yourEmail" placeholder="Masukan No Hp"
                                        value="<?php echo htmlspecialchars($kontak); ?>" />
                                </div>
                                <button name="submit" type="submit" class="btn btn-primary btn-sm w-100 mt-3">Daftar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    </div <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

    <!-- cek pasword -->
    <script>
        function togglePassword(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var icon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-kontak-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
</body>

</html>3