<?php 
$koneksi = mysqli_connect("localhost", "root", "", "spk_ahp_saw_native");

// Check connection
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}

// Query untuk mendapatkan data kriteria
$query_kriteria = "SELECT id_kriteria, nama AS nama_kriteria FROM kriteria";
$result_kriteria = mysqli_query($koneksi, $query_kriteria);

// Cek apakah query berhasil
if (!$result_kriteria) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam array
$kriteria_data = [];
while ($row = mysqli_fetch_assoc($result_kriteria)) {
    $kriteria_data[] = $row;
}

// Query untuk mendapatkan data subkriteria
$query_subkriteria = "SELECT id_sub_kriteria, nama AS nama_sub_kriteria, id_kriteria 
                      FROM sub_kriteria";
$result_subkriteria = mysqli_query($koneksi, $query_subkriteria);

// Cek apakah query berhasil
if (!$result_subkriteria) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam array
$subkriteria_data = [];
while ($row = mysqli_fetch_assoc($result_subkriteria)) {
    $subkriteria_data[] = $row;
}

// Mengelompokkan sub-kriteria berdasarkan id_kriteria
$subkriteria_by_kriteria = [];
foreach ($subkriteria_data as $sub) {
    $subkriteria_by_kriteria[$sub['id_kriteria']][] = $sub;
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

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
                    <a href="rekomendasi.php" class="text-white mx-4">Pemilihan</a>
                </nav>
            </div>
            <div class="d-flex justify-content-end">
                <a href="login.php" class="btn btn-custom me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
        </div>
    </header>

    <!-- Form Section -->
    <section class="py-5 min-vh-100 d-flex align-items-center mt-5" id="home">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form action="proses_pemilihan.php" method="post">
                        <?php foreach ($kriteria_data as $kriteria) : ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $kriteria['nama_kriteria']; ?></h5>
                                    <div class="mb-3">
                                        <label class="form-label" for="sub_kriteria_<?php echo $kriteria['id_kriteria']; ?>"> </label>
                                        <select class="form-control" id="sub_kriteria_<?php echo $kriteria['id_kriteria']; ?>" name="sub_kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
                                            <option value="">Pilih</option>
                                            <?php if (isset($subkriteria_by_kriteria[$kriteria['id_kriteria']])) : ?>
                                                <?php foreach ($subkriteria_by_kriteria[$kriteria['id_kriteria']] as $sub) : ?>
                                                    <option value="<?php echo $sub['id_sub_kriteria']; ?>">
                                                        <?php echo $sub['nama_sub_kriteria']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </form>
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
