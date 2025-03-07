<?php
require_once('includes/init.php');
cek_login($role = array(2));

$page = "Panduan";
require_once('template/header.php');

?>
<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Panduan</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Panduan Penggunaan Sistem</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Section Utama -->
<section class="section">
    <div class="card shadow">
        <div class="card-header">
            <h2 class="text-center mb-0">Panduan Penggunaan Sistem Pendukung Keputusan (SPK)</h2>
        </div>
        <div class="card-body">
            <!-- AHP Section -->
            <div class="mb-5">
                <h2 class="text-primary"><i class="bi bi-bar-chart-line me-2"></i>1. Menentukan Nilai Perbandingan
                    AHP</h2>
                <div class="mt-3">
                    <!-- Penjelasan AHP -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-info-circle-fill text-primary me-2"></i>Apa itu
                                AHP?</h5>
                            <p class="card-text">
                                <strong>Analytical Hierarchy Process (AHP)</strong> adalah metode pengambilan
                                keputusan yang digunakan untuk menentukan bobot kepentingan berdasarkan perbandingan
                                kriteria secara berpasangan. Metode ini membantu dalam memecahkan masalah kompleks
                                dengan memecahnya menjadi hierarki yang lebih sederhana.
                            </p>
                        </div>
                    </div>
                    <!-- Ketentuan AHP -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-list-task text-primary me-2"></i>Ketentuan AHP
                            </h5>
                            <p class="card-text">
                                Berikut adalah ketentuan dalam menentukan nilai perbandingan AHP:
                            </p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <i class="bi bi-arrow-right-circle-fill text-primary me-2"></i>
                                    Tentukan skala perbandingan dari 1 (sama penting) hingga 9 (sangat lebih
                                    penting) antar kriteria.
                                </li>
                                <li class="list-group-item">
                                    <img src="assets/img/input-ahp.png" alt="Input AHP"
                                        class="img-fluid rounded shadow-sm" style="max-width: 70%;">
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-arrow-right-circle-fill text-primary me-2"></i>
                                    klik "simpan" untuk menyimpan nilai perbandingan.
                                </li>
                                <li class="list-group-item">
                                    <img src="assets/img/simpan-ahp.png" alt="Simpan AHP"
                                        class="img-fluid rounded shadow-sm" style="max-width: 70%;">
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-arrow-right-circle-fill text-primary me-2"></i>
                                    Klik tombol "Cek Konsistensi" untuk mengecek konsistensi perhitungan. Jika hasilnya
                                    menunjukkan "Konsisten", Anda bisa melanjutkan ke tahap pencarian rotan. Namun,
                                    jika hasilnya "Tidak Konsisten", silakan perbaiki perbandingan hingga
                                    mendapatkan hasil yang konsisten.
                                </li>
                                <li class="list-group-item">
                                    <img src="assets/img/check-ahp.png" alt="Cek AHP"
                                        class="img-fluid rounded shadow-sm" style="max-width: 70%;">
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-arrow-right-circle-fill text-primary me-2"></i>
                                    Jika hasilnya menunjukkan "Konsisten", Anda bisa melanjutkan ke tahap pencarian rotan. 
                                    Namun, jika hasilnya "Tidak Konsisten", silakan perbaiki perbandingan hingga
                                    mendapatkan hasil yang konsisten.
                                </li>
                                <li class="list-group-item">
                                    <img src="assets/img/TK-ahp.png" alt="AHP"
                                        class="img-fluid rounded shadow-sm" style="max-width: 70%;">
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Pencarian Rotan Section -->
            <div class="mb-5">
                <h2 class="text-primary"><i class="bi bi-search me-2"></i>2. Mencari Rotan Berdasarkan Jenis,
                    Ukuran, dan Kualitas</h2>
                <div class="mt-3">
                    <!-- Penjelasan Pencarian Rotan -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-info-circle-fill text-primary me-2"></i>Pencarian
                                Rotan</h5>
                            <p class="card-text">
                                Untuk mencari rotan yang sesuai dengan kebutuhan, Anda bisa melakukan pencarian
                                berdasarkan:
                            </p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i
                                        class="bi bi-arrow-right-circle-fill text-primary me-2"></i><strong>Jenis
                                        Rotan:</strong> Pilih dari berbagai jenis rotan yang tersedia.</li>
                                <li class="list-group-item"><i
                                        class="bi bi-arrow-right-circle-fill text-primary me-2"></i><strong>Ukuran:</strong>
                                    Tentukan panjang dan diameter rotan yang diinginkan.</li>
                                <li class="list-group-item"><i
                                        class="bi bi-arrow-right-circle-fill text-primary me-2"></i><strong>Kualitas:</strong>
                                    Sistem akan menampilkan pilihan supplier dengan kualitas terbaik.</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Hasil Pencarian -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-lightbulb-fill text-primary me-2"></i>Hasil
                                Pencarian</h5>
                            <p class="card-text">
                                Hasil pencarian akan ditampilkan berdasarkan peringkat supplier yang dihitung
                                menggunakan metode <strong>SAW (Simple Additive Weighting)</strong>. Metode ini
                                membantu dalam menentukan supplier terbaik berdasarkan kriteria yang telah
                                ditentukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali ke Beranda -->
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-primary"><i class="bi bi-house-door me-2"></i>Kembali ke
                    Beranda</a>
            </div>
        </div>
    </div>
</section>

<?php
require_once('template/footer.php');
?>