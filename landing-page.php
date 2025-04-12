<?php
require_once('includes/init.php');

// Cek apakah pengguna sudah login
if (isset($_SESSION["id_user"])) {
    // Jika sudah login, arahkan ke halaman dashboard atau halaman lain yang sesuai
    redirect_to("dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>SIPEKTRA | Landing Page</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="assets/img/lambang.png" rel="icon" />
    <link href="assets/img/lambang.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
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


<body class="d-flex flex-column min-vh-100">
    <!-- Header -->
    <header class="header fixed-top text-white shadow-md py-1">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-start">
                <a href="index.php" class="logo d-flex align-items-center">
                    <img src="assets/img/lambang.png" alt="Logo" />
                    <span class="d-none d-lg-block">SIPEKTRA</span>
                </a>
            </div>
            <div class="d-flex justify-content-end">
                <a href="login.php" class="btn btn-md btn-custom me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="flex-grow-1 d-flex align-items-center py-5">
        <div class="container">
            <div class="row align-items-center">
                <!-- Gambar -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="image-container text-center">
                        <img src="assets/img/rotan3.jpg" class="img-fluid mx-auto d-block rounded-3 shadow-lg"
                            alt="Supplier Rotan">
                    </div>
                </div>

                <!-- Teks -->
                <div class="col-md-6">
                    <h2 class="mb-4">Sistem Pendukung Keputusan Pemilihan Supplier Bahan Baku Rotan di Kabupaten Cirebon
                    </h2>
                    <p class="lead mb-4">Bantu perusahaan Anda memilih supplier rotan terbaik dengan cepat, akurat, dan
                        efisien.</p>
                    <a href="rekomendasi.php" class="btn btn-custom btn-lg"><i class="bi bi-play"></i> Mulai
                        Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white text-center py-3 mt-auto" style="background-color:#071952;">
        <div class="container">
            <p class="mb-0">&copy; 2024 SPK Pemilihan Supplier Rotan</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>


</html>