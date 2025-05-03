<?php
require_once('includes/konek-db.php');

$showResults = false; // Flag untuk menentukan apakah menampilkan hasil
$searchResults = ''; // Variabel untuk menyimpan hasil pencarian

// Proses jika user men-submit form
if (isset($_POST['submit'])) {
    $id_jenis = $_POST['id_jenis'];
    $id_ukuran = $_POST['id_ukuran'];
    $kualitas = $_POST['kualitas'];
    $showResults = true;

    // Query ambil data sesuai filter
    $query = "SELECT supplier.nama AS nama_supplier, supplier.kontak, 
              jenis_rotan.nama_jenis, ukuran_rotan.ukuran, data_rotan.harga
              FROM data_rotan
              JOIN supplier ON data_rotan.id_supplier = supplier.id_supplier
              JOIN jenis_rotan ON data_rotan.id_jenis = jenis_rotan.id_jenis
              JOIN ukuran_rotan ON data_rotan.id_ukuran = ukuran_rotan.id_ukuran
              WHERE data_rotan.id_jenis = '$id_jenis' 
              AND data_rotan.id_ukuran = '$id_ukuran'
              AND data_rotan.kualitas = '$kualitas'
              AND data_rotan.harga > 0";

    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        ob_start(); // Mulai output buffering
        ?>
        <form action="saw.php" method="POST">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Jenis Rotan</th>
                        <th>Ukuran</th>
                        <th>Harga</th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_supplier']); ?></td>
                            <td><?= htmlspecialchars($row['nama_jenis']); ?></td>
                            <td><?= htmlspecialchars($row['ukuran']); ?></td>
                            <td><?= htmlspecialchars($row['harga']); ?></td>
                            <td><?= htmlspecialchars($row['kontak']); ?></td>
                            <input type="hidden" name="id_supplier[]" value="<?= htmlspecialchars($row['nama_supplier']); ?>">
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary" name="lanjut">Lanjut Perankingan</button>
            </div>
        </form>
        <?php
        $searchResults = ob_get_clean(); // Ambil output buffer
    } else {
        $searchResults = '<p class="text-danger text-center">❌ Mohon maaf, data yang anda cari tidak ditemukan.</p>';
    }
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
    <style>
        #resultCard {
            display:
                <?= $showResults ? 'block' : 'none' ?>
            ;
        }

        #formCard {
            display:
                <?= $showResults ? 'none' : 'block' ?>
            ;
        }

        .min-vh-100 {
            min-height: 100vh;
        }

        .dropdown-menu {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
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
            <div class="d-flex justify-content-end">
                <a href="login.php" class="btn btn-custom me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
        </div>
    </header>

    <div class="container d-flex flex-column justify-content-center align-items-center mt-5 pt-4 min-vh-100">
        <!-- card form pencarian -->
        <div id="formCard" class="card w-75">
            <div class="card-header fw-bold text-center">REKOMENDASI SUPPLIER</div>
            <div class="card-body">
                <h5 class="card-title">Tentukan Jenis, Ukuran dan Kualitas Rotan yang diinginkan</h5>
                <form action="" method="POST" id="searchForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="id_jenis" class="form-label">Jenis Rotan</label>
                            <div class="dropdown">
                                <button class="form-control text-start" type="button" id="dropdownJenisRotan"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    -- Pilih Jenis Rotan --
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownJenisRotan">
                                    <?php
                                    $query_jenis = "SELECT id_jenis, nama_jenis FROM jenis_rotan";
                                    $result_jenis = mysqli_query($koneksi, $query_jenis);
                                    while ($row = mysqli_fetch_assoc($result_jenis)):
                                        ?>
                                        <li>
                                            <a class="dropdown-item" href="#" data-value="<?= $row['id_jenis']; ?>">
                                                <?= htmlspecialchars($row['nama_jenis']); ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <input type="hidden" name="id_jenis" id="id_jenis" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="id_ukuran" class="form-label">Ukuran</label>
                            <div class="dropdown">
                                <button class="form-control text-start" type="button" id="dropdownUkuranRotan"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    -- Pilih Ukuran --
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownUkuranRotan">
                                    <?php
                                    $query_ukuran = "SELECT id_ukuran, ukuran FROM ukuran_rotan";
                                    $result_ukuran = mysqli_query($koneksi, $query_ukuran);
                                    while ($row = mysqli_fetch_assoc($result_ukuran)):
                                        ?>
                                        <li>
                                            <a class="dropdown-item" href="#" data-value="<?= $row['id_ukuran']; ?>">
                                                <?= htmlspecialchars($row['ukuran']); ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <input type="hidden" name="id_ukuran" id="id_ukuran" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="kualitas" class="form-label">Kualitas Rotan</label>
                            <select name="kualitas" class="form-control" required>
                                <option value="">--Pilih Kualitas Rotan--</option>
                                <option value="AB" <?= isset($_POST['kualitas']) && $_POST['kualitas'] == 'AB' ? 'selected' : '' ?>>AB</option>
                                <option value="BC" <?= isset($_POST['kualitas']) && $_POST['kualitas'] == 'BC' ? 'selected' : '' ?>>BC</option>
                                <option value="CD" <?= isset($_POST['kualitas']) && $_POST['kualitas'] == 'CD' ? 'selected' : '' ?>>CD</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label for="preset_bobot" class="form-label">Preset Bobot</label>
                            <select name="preset_bobot" id="preset_bobot" class="form-control">
                                <option value="">-- Apa yang anda inginkan? --</option>
                                <?php
                                $query_preset = "SELECT id_preset, nama_preset FROM preset_bobot WHERE status = 'aktif'";
                                $result_preset = mysqli_query($koneksi, $query_preset);
                                while ($row = mysqli_fetch_assoc($result_preset)):
                                    ?>
                                    <option value="<?= $row['id_preset']; ?>" <?= isset($_POST['preset_bobot']) && $_POST['preset_bobot'] == $row['id_preset'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['nama_preset']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-sm mt-3" name="submit">Cari
                            Rekomendasi</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Hasil Pencarian -->
        <div id="resultCard" class="card w-75 mt-4">
            <div class="card-body">
                <h5 class="card-title text-center">Hasil Rekomendasi Pencarian</h5>
                <div id="searchResults">
                    <?= $searchResults ?>
                </div>
                <div class="text-center mt-3">
                    <button id="backButton" class="btn btn-secondary">Kembali ke Pencarian</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white text-center py-3" style="background-color:#071952;">
        <div class="container">
            <p class="mb-0">&copy; 2025 SPK Pemilihan Supplier Bahan Baku Rotan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle dropdown pilihan Jenis dan Ukuran Rotan
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value');
                    const text = this.textContent.trim();

                    // Tentukan dropdown dan input hidden yang sesuai
                    const dropdown = this.closest('.dropdown');
                    if (dropdown.querySelector('#dropdownJenisRotan')) {
                        document.getElementById('id_jenis').value = value;
                        document.getElementById('dropdownJenisRotan').textContent = text;
                    }
                    if (dropdown.querySelector('#dropdownUkuranRotan')) {
                        document.getElementById('id_ukuran').value = value;
                        document.getElementById('dropdownUkuranRotan').textContent = text;
                    }
                });
            });

            // Tombol kembali ke form pencarian
            const backButton = document.getElementById('backButton');
            if (backButton) {
                backButton.addEventListener('click', function () {
                    document.getElementById('resultCard').style.display = 'none';
                    document.getElementById('formCard').style.display = 'block';
                });
            }

            // Cek kondisi PHP apakah ingin langsung scroll ke hasil
            <?php if (isset($showResults) && $showResults): ?>
                document.getElementById('resultCard').scrollIntoView({ behavior: 'smooth' });
            <?php endif; ?>
        });
    </script>


    <!-- Bootstrap JS and Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>