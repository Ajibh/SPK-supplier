<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>SIPEKTRA | Sistem Pendukung Keputusan Pemilihan Supplier Rotan Menggunakan Metode AHP-SAW</title>
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
    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <i class="bi bi-list toggle-sidebar-btn"></i>
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/lambang.png" alt="" />
                <span class="d-none d-lg-block">SIPEKTRA</span>
            </a>
        </div>
        <!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/user.png" alt="Profile" class="rounded-circle" />
                        <span
                            class="d-none d-md-block dropdown-toggle ps-2 text-white"><?php echo $_SESSION['username']; ?></span>
                    </a>
                    <!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="list-profile.php">
                                <i class="bi bi-person-circle"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>

                        <li>
                            <a onclick="return confirm ('Apakah anda yakin ingin logout?')"
                                class="dropdown-item d-flex align-items-center" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                    <!-- End Profile Dropdown Items -->
                </li>
                <!-- End Profile Nav -->
            </ul>
        </nav>
        <!-- End Icons Navigation -->
    </header>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="<?php if ($page == "Dashboard") {
                    echo 'nav-link';
                } else {
                    echo 'nav-link collapsed';
                } ?>" href="index.php">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Menu</li>


            <?php
            $user_role = get_role();
            if ($user_role == 'admin') {
                ?>

                <li class="nav-item">
                    <a class="<?php if ($page == 'bobot') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="tambah-bobot-ahp.php">
                        <i class="bi bi-box"></i>
                        <span>Hitung Bobot AHP</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="<?php if ($page == 'Kriteria') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="list-kriteria.php">
                        <i class="bi bi-file-bar-graph"></i>
                        <span>Data Kriteria</span>
                    </a>
                </li>

                <?php
                $q = mysqli_query($koneksi, "SELECT COUNT(ada_pilihan) as banyak FROM kriteria WHERE ada_pilihan='1'");
                $krit = mysqli_fetch_array($q);
                if ($krit['banyak'] > 0) {
                    ?>
                    <li class="nav-item">
                        <a class="<?php if ($page == 'Sub Kriteria') {
                            echo 'nav-link';
                        } else {
                            echo 'nav-link collapsed';
                        } ?>" href="list-sub-kriteria.php">
                            <i class="bi bi-file-bar-graph-fill"></i>
                            <span>Data Sub Kriteria</span>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a class="<?php if ($page == 'Jenis_Ukuran_Rotan') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="jenis-ukuran-rotan.php">
                        <i class="bi bi-file-bar-graph"></i>
                        <span>Jenis & Ukuran Rotan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="<?php if ($page == 'Alternatif') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="list-alternatif.php">
                        <i class="bi bi-people-fill"></i>
                        <span>Data Supplier</span>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a class="<?php if ($page == 'Penilaian') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="list-penilaian.php">
                        <i class="bi bi-person-fill-check"></i>
                        <span>Penilaian</span>
                    </a>
                </li> -->

                <li class="nav-item">
                    <a class="<?php if ($page == 'Perhitungan') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="perhitungan.php">
                        <i class="bi bi-calculator-fill"></i>
                        <span>Perhitungan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="<?php if ($page == 'Pesan') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="pesan.php">
                        <i class="bi bi-bar-chart-steps"></i>
                        <span>Data Hasil Akhir</span>
                    </a>
                </li>

                <?php
            } elseif ($user_role == 'customer') {
                ?>

                <li class="nav-item">
                    <a class="<?php if ($page == 'bobot') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="tambah-bobot-ahp.php">
                        <i class="bi bi-box"></i>
                        <span>Hitung Bobot AHP</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="<?= ($page == 'input_jenis_rotan_kualitas') ? 'nav-link' : 'nav-link collapsed'; ?>"
                        href="input-jenis-rotan-kualitas.php">
                        <i class="bi bi-pin"></i>
                        <span>Cari Rotan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="<?php if ($page == 'saw') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="saw.php">
                        <i class="bi bi-box"></i>
                        <span>saw</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="<?= ($page == 'Perankingan') ? 'nav-link' : 'nav-link collapsed'; ?>" href="perankingan.php">
                        <i class="bi bi-calculator"></i>
                        <span>Perankingan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="<?= ($page == 'Rekomendasi') ? 'nav-link' : 'nav-link collapsed'; ?>" href="pesan.php">
                        <i class="bi bi-bar-chart"></i>
                        <span>Rekomendasi</span>
                    </a>
                </li>

                <?php
            } elseif ($user_role == 'supplier') {
                ?>
                <li class="nav-item">
                    <a class="<?= ($page == 'data_rotan') ? 'nav-link' : 'nav-link collapsed'; ?>" href="data-rotan.php">
                        <i class="bi bi-pin"></i>
                        <span>Data Rotan</span>
                    </a>
                </li>
                <?php
            }
            ?>

            <li class="nav-heading">Manajemen User</li>

            <?php if ($user_role == 'customer') { ?>
                <li class="nav-item">
                    <a class="<?php if ($page == 'panduan') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="panduan.php">
                        <i class="bi bi-person-square"></i>
                        <span>Panduan</span>
                    </a>
                </li>
            <?php } ?>

            <?php if ($user_role == 'admin') { ?>
                <li class="nav-item">
                    <a class="<?php if ($page == 'User' || $page == 'supplier') {
                        echo 'nav-link';
                    } else {
                        echo 'nav-link collapsed';
                    } ?>" href="list-user.php">
                        <i class="bi bi-person-square"></i>
                        <span>Data User</span>
                    </a>
                </li>
            <?php } ?>

            <li class="nav-item">
                <a class="<?php if ($page == 'Profile') {
                    echo 'nav-link';
                } else {
                    echo 'nav-link collapsed';
                } ?>" href="list-profile.php">
                    <i class="bi bi-person-circle"></i>
                    <span>Data Profile</span>
                </a>
            </li>
        </ul>
    </aside>
    <!-- End Sidebar-->

    <main id="main" class="main">