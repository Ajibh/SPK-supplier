<?php
$koneksi = mysqli_connect("localhost", "root", "", "spk_ahp_saw_native");

// Check connection
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
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
            <div class="d-flex justify-content-end">
                <a href="login.php" class="btn btn-custom me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
        </div>
    </header>

    <div class="container d-flex flex-column justify-content-center align-items-center mt-5 pt-4 min-vh-100">
        <div class="card w-75">
            <div class="card-header fw-bold text-center">REKOMENDASI SUPPLIER</div>
            <div class="card-body">
                <h5 class="card-title">Tentukan Jenis, Ukuran dan Kualitas Rotan yang diinginkan</h5>
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="id_jenis" class="form-label">Jenis Rotan</label>
                            <div class="dropdown">
                                <button class="form-control text-start" type="button" id="dropdownJenisRotan"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    -- Pilih Jenis Rotan --
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownJenisRotan"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <?php
                                    $query_jenis = "SELECT id_jenis, nama_jenis FROM jenis_rotan";
                                    $result_jenis = mysqli_query($koneksi, $query_jenis);
                                    while ($row = mysqli_fetch_assoc($result_jenis)):
                                        ?>
                                        <li>
                                            <a class="dropdown-item" href="#" data-value="<?= $row['id_jenis']; ?>">
                                                <?= $row['nama_jenis']; ?>
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
                                <ul class="dropdown-menu" aria-labelledby="dropdownUkuranRotan"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <?php
                                    $query_ukuran = "SELECT id_ukuran, ukuran FROM ukuran_rotan";
                                    $result_ukuran = mysqli_query($koneksi, $query_ukuran);
                                    while ($row = mysqli_fetch_assoc($result_ukuran)):
                                        ?>
                                        <li>
                                            <a class="dropdown-item" href="#" data-value="<?= $row['id_ukuran']; ?>">
                                                <?= $row['ukuran']; ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <input type="hidden" name="id_ukuran" id="id_ukuran" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="kualitas_rotan" class="form-label">Kualitas Rotan</label>
                            <select name="kualitas_rotan" class="form-control" required>
                                <option value="">--Pilih Kualitas Rotan--</option>
                                <option value="AB">AB</option>
                                <option value="BC">BC</option>
                                <option value="CD">CD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bagian Penentuan Bobot -->
                    <div class="mt-4 pt-3 bg-light rounded">
                        <h5 class="text-center">Apa yang paling penting bagi Anda?</h5>
                        <div class="d-flex justify-content-center gap-3">
                            <label><input type="checkbox" id="harga" onchange="hitungBobot()"> Harga Murah</label>
                            <label><input type="checkbox" id="stok" onchange="hitungBobot()"> Stok Banyak</label>
                            <label><input type="checkbox" id="minimal" onchange="hitungBobot()"> Minimal Pembelian
                                Rendah</label>
                        </div>

                        <div class="mt-3 text-center">
                            <h6>Bobot yang dihitung:</h6>
                            <p>Harga: <span id="bobot_harga">0</span></p>
                            <p>Stok: <span id="bobot_stok">0</span></p>
                            <p>Minimal Pembelian: <span id="bobot_minimal">0</span></p>
                            <p id="hasil" class="fw-bold">Total Bobot: <span id="total_bobot">0</span></p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-sm mt-3" name="submit">Cari
                            Rekomendasi</button>
                    </div>

                </form>
            </div>
        </div>

        <?php
        // Pastikan data hasil filtering hanya muncul jika ada input dari form
        if (isset($_POST['submit'])) {
            $id_jenis = $_POST['id_jenis'];
            $id_ukuran = $_POST['id_ukuran'];
            $kualitas_rotan = $_POST['kualitas_rotan'];

            // Menentukan kolom harga berdasarkan kualitas yang dipilih
            $harga_column = '';
            if ($kualitas_rotan == 'AB') {
                $harga_column = 'harga_ab';
            } elseif ($kualitas_rotan == 'BC') {
                $harga_column = 'harga_bc';
            } elseif ($kualitas_rotan == 'CD') {
                $harga_column = 'harga_cd';
            }

            // Query untuk mengambil data supplier dengan kriteria yang dipilih
            $query = "SELECT supplier.nama AS nama_supplier, supplier.kontak, 
			jenis_rotan.nama_jenis, ukuran_rotan.ukuran, data_rotan.$harga_column 
			FROM data_rotan
			JOIN supplier ON data_rotan.id_supplier = supplier.id_supplier
			JOIN jenis_rotan ON data_rotan.id_jenis = jenis_rotan.id_jenis
			JOIN ukuran_rotan ON data_rotan.id_ukuran = ukuran_rotan.id_ukuran
			WHERE data_rotan.id_jenis = '$id_jenis' 
			AND data_rotan.id_ukuran = '$id_ukuran' 
			AND data_rotan.$harga_column > 0"; // Hanya mengambil yang harganya tidak nol
        
            $result = mysqli_query($koneksi, $query);
        }
        ?>

        <div class="container mt-4 d-flex flex-column justify-content-center align-items-center">
            <div class="card w-75">
                <div class="card-body">
                    <h5 class="card-title text-center">Hasil Rekomendasi Pencarian</h5>

                    <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
                        <?php $jumlah_data = mysqli_num_rows($result); ?>

                        <!-- Jika data hanya 1 atau 2, beri peringatan -->
                        <?php if ($jumlah_data < 3): ?>
                            <p class="text-warning text-center">⚠️ Hanya ditemukan <?= $jumlah_data ?> data supplier.
                                Pertimbangkan menyesuaikan filter.</p>
                        <?php endif; ?>

                        <form action="saw.php" method="POST">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Jenis Rotan</th>
                                        <th>Ukuran</th>
                                        <th>Harga (<?= $kualitas_rotan ?>)</th>
                                        <th>Kontak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $row['nama_supplier']; ?></td>
                                            <td><?= $row['nama_jenis']; ?></td>
                                            <td><?= $row['ukuran']; ?></td>
                                            <td><?= number_format($row[$harga_column], 0, ',', '.'); ?></td>
                                            <td><?= $row['kontak']; ?></td>
                                            <input type="hidden" name="id_supplier[]" value="<?= $row['nama_supplier']; ?>">
                                            <input type="hidden" name="harga[]" value="<?= $row[$harga_column]; ?>">
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary" name="lanjut" id="btnLanjut" disabled>
                                    Lanjut Perankingan
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="text-danger text-center">❌ Mohon maaf, data yang anda cari tidak ditemukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            function hitungBobot() {
                let harga = document.getElementById("harga").checked;
                let stok = document.getElementById("stok").checked;
                let minimal = document.getElementById("minimal").checked;

                let pilihan = [harga, stok, minimal].filter(p => p).length;
                let bobot = pilihan > 0 ? (1 / pilihan).toFixed(2) : 0;

                document.getElementById("bobot_harga").innerText = harga ? bobot : "0";
                document.getElementById("bobot_stok").innerText = stok ? bobot : "0";
                document.getElementById("bobot_minimal").innerText = minimal ? bobot : "0";

                let totalBobot = harga * bobot + stok * bobot + minimal * bobot;
                document.getElementById("total_bobot").innerText = totalBobot.toFixed(2);

                // Ubah warna total jika tidak 1
                let hasil = document.getElementById("hasil");
                let btnLanjut = document.getElementById("btnLanjut");

                if (totalBobot.toFixed(2) == 1.00) {
                    hasil.style.color = "green";
                    btnLanjut.disabled = false; // Aktifkan tombol
                } else {
                    hasil.style.color = "red";
                    btnLanjut.disabled = true; // Nonaktifkan tombol
                }
            }
        </script>


        <script>
            // Script untuk Dropdown Jenis Rotan
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value');
                    const text = this.textContent;

                    // Tentukan dropdown yang dipilih
                    if (this.closest('.dropdown').querySelector('#dropdownJenisRotan')) {
                        document.getElementById('id_jenis').value = value;
                        document.getElementById('dropdownJenisRotan').textContent = text;
                    }
                    if (this.closest('.dropdown').querySelector('#dropdownUkuranRotan')) {
                        document.getElementById('id_ukuran').value = value;
                        document.getElementById('dropdownUkuranRotan').textContent = text;
                    }
                });
            });
        </script>
    </div>

    <!-- Footer -->
    <footer class="text-white text-center py-3" style="background-color:#071952;">
        <div class="container">
            <p class="mb-0">&copy; 2025 SPK Pemilihan Supplier Bahan Baku Rotan</p>
        </div>
    </footer>

    <!-- Bootstrap JS and Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>