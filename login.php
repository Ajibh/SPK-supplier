<?php
require_once('includes/init.php');

// Cek apakah pengguna sudah login
if (isset($_SESSION["id_user"])) {
    // Jika sudah login, arahkan ke halaman dashboard atau halaman lain yang sesuai
    redirect_to("dashboard.php");
}

$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (isset($_POST['submit'])):

    // Validasi
    if (!$username) {
        $errors[] = 'Username tidak boleh kosong';
    }
    if (!$password) {
        $errors[] = 'Password tidak boleh kosong';
    }

    if (empty($errors)):
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
        $cek = mysqli_num_rows($query);
        $data = mysqli_fetch_array($query);

        if ($cek > 0) {
            $hashed_password = sha1($password);
            if ($data['password'] === $hashed_password) {
                $_SESSION["id_user"] = $data["id_user"];
                $_SESSION["username"] = $data["username"];
                $_SESSION["role"] = $data["role"];
                redirect_to("dashboard.php");
            } else {
                $errors[] = 'Username atau password salah!';
            }
        } else {
            $errors[] = 'Username atau password salah!';
        }

    endif;

endif;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title> SIPEKTRA | Login</title>
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
    <div class="container-fluid">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12">
                    <div class="card shadow-lg border-0">
                        <div class="row g-0">
                            <!-- Bagian Kiri: Gambar -->
                            <div class="col-md-6 d-flex align-items-center justify-content-center">
                                <img src="assets/img/supplier.jpeg" class="img-fluid rounded-start" alt="Supplier">
                            </div>
                            <!-- Bagian Kanan: Form Login -->
                            <div class="col-md-6 mt-5">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <p class="text-center text-muted fs-5 mb-4">Silahkan Login</p>
                                    <?php if (!empty($errors)): ?>
                                        <?php foreach ($errors as $error): ?>
                                            <div class="alert alert-danger text-center mb-3"><?php echo $error; ?></div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <form class="user w-75" action="" method="post">
                                        <div class="mb-3">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <input required autocomplete="off" type="text" name="username"
                                                class="form-control" id="yourUsername" placeholder="Masukan Username">
                                        </div>
                                        <div class="mb-3">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input required autocomplete="off" type="password" name="password"
                                                class="form-control" id="yourPassword" placeholder="Masukan Password">
                                        </div>
                                        <button name="submit" type="submit" class="btn btn-primary w-100">Login</button>
                                        <div class="text-center mt-2">
                                            Belum punya akun?
                                            <a href="register.php" class="text-decoration-underline fw-bold">Daftar di
                                                sini</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
</body>

</html>