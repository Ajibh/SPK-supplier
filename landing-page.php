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


<body>
    <!-- Header -->
    <header class="header fixed-top text-white shadow-md py-1">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-start">
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/lambang.png" alt="Logo" />
                <span class="d-none d-lg-block">SIPEKTRA</span>
            </a>
        </div>
        <div class="d-flex justify-content-center flex-grow-1">
            <nav class="d-flex">
                <a href="#home" class="text-white mx-4">Home</a>
                <a href="#fitur" class="text-white mx-4">Fitur</a>
                <a href="#metode" class="text-white mx-4">Metode</a>
            </nav>
        </div>
        <div class="d-flex justify-content-end">
            <a href="login.php" class="btn btn-custom me-2">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>
    </div>
</header>

    <!-- Hero Section -->
    <section class="py-5 min-vh-100 d-flex align-items-center" id="home">
        <div class="container">
            <div class="row align-items-center">
                <!-- Gambar -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="image-container">
                        <img src="assets/img/rotan3.jpg" class="img-fluid" alt="Supplier Rotan">
                    </div>
                </div>

                <!-- Teks -->
                <div class="col-md-6">
                    <h1 class="mb-4">Sistem Pendukung Keputusan Pemilihan Supplier Rotan di Kabupaten Cirebon
                    </h1>
                    <p class="lead mb-4">Bantu perusahaan Anda memilih supplier rotan terbaik dengan cepat, akurat, dan
                        efisien.</p>
                    <a href="register.php" class="btn btn-custom btn-lg"><i class="bi bi-person-plus"></i> Registrasi</a>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Fitur Utama</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-chart-line fa-2x me-3 text-primary"></i>
                                <h3 class="card-title mb-0">Analisis Multi-Kriteria</h3>
                            </div>
                            <p class="card-text">Gunakan metode AHP-SAW untuk menganalisis berbagai kriteria dalam
                                memilih supplier terbaik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-tachometer-alt fa-2x me-3 text-success"></i>
                                <h3 class="card-title mb-0">Evaluasi Supplier yang Mudah</h3>
                            </div>
                            <p class="card-text">Dapatkan peringkat supplier secara otomatis berdasarkan data yang Anda
                                masukkan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-clock fa-2x me-3 text-warning"></i>
                                <h3 class="card-title mb-0">Penghematan Waktu dan Biaya</h3>
                            </div>
                            <p class="card-text">Kurangi waktu dan biaya dalam proses evaluasi dengan sistem yang
                                terotomatisasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Metode Section -->
    <section id="metode" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Metode</h2>
            <div class="row">
                <!-- Card for AHP -->
                <div class="col-md-6 mb-4">
                    <div class="card border-primary h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div>
                                <i class="bi bi-diagram-2 fs-1 text-primary"></i>
                            </div>
                            <h5 class="card-title mb-3">Analytical Hierarchy Process</h5>
                            <p class="card-text">
                                Analytic Hierarchy Process (AHP) adalah metode pengambilan keputusan yang membantu dalam
                                menentukan prioritas dan memilih alternatif dengan membandingkan elemen secara
                                berpasangan dan menghasilkan bobot untuk setiap elemen.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card for SAW -->
                <div class="col-md-6 mb-4">
                    <div class="card border-primary h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div>
                                <i class="bi bi-calculator fs-1 text-primary"></i>
                            </div>
                            <h5 class="card-title mb-3">Simple Additive Weighting</h5>
                            <p class="card-text">
                                Simple Additive Weighting (SAW) adalah metode pemilihan yang digunakan untuk menentukan
                                alternatif terbaik dengan memberikan bobot pada setiap kriteria dan menjumlahkan nilai
                                kriteria untuk setiap alternatif.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white text-center py-3" style="background-color:#071952;">
        <div class="container">
            <p class="mb-0">&copy; 2024 SPK Pemilihan Supplier Rotan</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>