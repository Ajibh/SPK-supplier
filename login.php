<?php
require_once('includes/init.php');

// Cek apakah pengguna sudah login
if (isset($_SESSION["id_user"])) {
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
        $data = mysqli_fetch_assoc($query);

        if ($cek > 0) {
            $hashed_password = sha1($password);
            if ($data['password'] === $hashed_password) {
                // Set Session
                $_SESSION["id_user"] = $data["id_user"];
                $_SESSION["username"] = $data["username"];
                $_SESSION["role"] = $data["role"];

                // Ambil id_supplier jika role adalah 2
                if ($data["role"] == '2') {
                    $query_supplier = mysqli_query($koneksi, "SELECT id_supplier FROM supplier WHERE id_user = '" . $data["id_user"] . "'");
                    $data_supplier = mysqli_fetch_assoc($query_supplier);

                    if ($data_supplier) {
                        $_SESSION["id_supplier"] = $data_supplier["id_supplier"];
                    } else {
                        $errors[] = 'ID Supplier tidak ditemukan!';
                    }
                }

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

    <style>
        .password-toggle-wrapper {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #6c757d;
            font-size: 18px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .password-toggle-btn:hover {
            color: #495057;
        }

        .password-field {
            padding-right: 45px !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12">
                    <div class="card shadow-lg border-0">
                        <div class="row g-0">
                            <!-- Bagian Kiri: Gambar + Overlay -->
                            <div class="col-md-6 position-relative d-flex align-items-center justify-content-center">
                                <!-- Gambar -->
                                <img src="assets/img/login.png" class="img-fluid rounded-start" alt="Supplier"
                                    style="width:100%; height:100%; object-fit:cover;">

                                <!-- Overlay Hitam Tipis Full -->
                                <div style="position:absolute; top:0; left:0; width:100%; height:100%;
                                background-color: rgba(0,0,0,0.25); border-radius:12px;"></div>

                                <!-- Overlay Tengah -->
                                <div class="position-absolute top-50 start-50 translate-middle text-center text-white p-3"
                                    style="backdrop-filter: blur(5px); background: rgba(0,0,0,0); border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); max-width: 90%;">

                                    <!-- Logo -->
                                    <img src="assets/img/lambang.png" alt="Logo SIPEKTRA"
                                        style="width:70px; height:70px; object-fit:contain; margin-bottom:8px;">

                                    <!-- Nama Singkat -->
                                    <div
                                        style="font-family:'Poppins',sans-serif; font-weight:700; letter-spacing:3px; font-size:1.6rem; margin-bottom:8px;">
                                        SIPEKTRA
                                    </div>

                                    <!-- Garis Pembatas -->
                                    <div
                                        style="width:50px; height:2px; background-color:#fff; margin:0 auto 14px auto; border-radius:1px;">
                                    </div>

                                    <!-- Judul -->
                                    <div
                                        style="font-family:'Poppins',sans-serif; font-weight:500; font-size:1.0rem; text-transform:uppercase; margin-bottom:4px;">
                                        Sistem Pendukung Keputusan
                                    </div>
                                    <div
                                        style="font-family:'Poppins',sans-serif; font-weight:500; font-size:0.9rem; margin-bottom:4px;">
                                        Pemilihan Supplier Bahan Baku Rotan
                                    </div>
                                    <div style="font-family:'Nunito',sans-serif; font-weight:500; font-size:0.8rem;">
                                        di Kabupaten Cirebon
                                    </div>
                                </div>
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
                                            <input autocomplete="off" type="text" name="username" class="form-control"
                                                id="yourUsername" placeholder="Masukan Username" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <div class="password-toggle-wrapper">
                                                <input autocomplete="off" type="password" name="password"
                                                    class="form-control password-field" id="yourPassword"
                                                    placeholder="Masukan Password" required>
                                                <button type="button" class="password-toggle-btn"
                                                    onclick="togglePassword()">
                                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <script>
                                            function togglePassword() {
                                                const pwd = document.getElementById('yourPassword');
                                                const icon = document.getElementById('toggleIcon');
                                                if (pwd.type === 'password') {
                                                    pwd.type = 'text';
                                                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                                                } else {
                                                    pwd.type = 'password';
                                                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                                                }
                                            }
                                        </script>

                                        <div class="text-center">
                                            <button name="submit" type="submit"
                                                class="btn btn-primary btn-sm mb-2">Login</button>
                                        </div>
                                        <div>
                                            Bergabung sebagai supplier?
                                            <a href="register.php" class="text-decoration-underline fw-bold">Klik di
                                                sini
                                                untuk daftar!</a>
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