<?php
require_once('includes/konek-db.php');

// Query untuk mengambil data supplier dan perhitungan SAW
$query = "SELECT supplier.nama AS nama_supplier, supplier.kontak, 
          jenis_rotan.nama_jenis, ukuran_rotan.ukuran, data_rotan.harga, data_rotan.stok, data_rotan.minimal_pembelian
          FROM data_rotan
          JOIN supplier ON data_rotan.id_supplier = supplier.id_supplier
          JOIN jenis_rotan ON data_rotan.id_jenis = jenis_rotan.id_jenis
          JOIN ukuran_rotan ON data_rotan.id_ukuran = ukuran_rotan.id_ukuran";
$result = mysqli_query($koneksi, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $data = [];
    $max = ['harga' => 0, 'stok' => 0, 'minimal_pembelian' => 0];

    // Ambil data dan hitung nilai maksimum untuk normalisasi
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $max['harga'] = max($max['harga'], $row['harga']);
        $max['stok'] = max($max['stok'], $row['stok']);
        $max['minimal_pembelian'] = max($max['minimal_pembelian'], $row['minimal_pembelian']);
    }

    // Bobot kriteria (contoh: bisa diambil dari preset atau ditentukan langsung)
    $bobot = [
        'harga' => 0.4, // Bobot untuk harga
        'stok' => 0.3,  // Bobot untuk stok
        'minimal_pembelian' => 0.3 // Bobot untuk minimal pembelian
    ];

    // Hitung nilai SAW
    $ranking = [];
    foreach ($data as $row) {
        $normalisasi = [
            'harga' => $row['harga'] / $max['harga'],
            'stok' => $row['stok'] / $max['stok'],
            'minimal_pembelian' => $row['minimal_pembelian'] / $max['minimal_pembelian'],
        ];

        $nilai_saw = (
            $normalisasi['harga'] * $bobot['harga'] +
            $normalisasi['stok'] * $bobot['stok'] +
            $normalisasi['minimal_pembelian'] * $bobot['minimal_pembelian']
        );

        $ranking[] = [
            'supplier' => $row['nama_supplier'],
            'kontak' => $row['kontak'],
            'nilai_asli' => [
                'harga' => $row['harga'],
                'stok' => $row['stok'],
                'minimal_pembelian' => $row['minimal_pembelian'],
            ],
            'normalisasi' => $normalisasi,
            'nilai' => $nilai_saw,
        ];
    }

    // Urutkan berdasarkan nilai SAW
    usort($ranking, function ($a, $b) {
        return $b['nilai'] <=> $a['nilai'];
    });
} else {
    $ranking = [];
}
?>

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

    <!-- CDN DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Header -->
    <header class="header fixed-top text-white shadow-md py-1" style="background-color: #071952;">
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
    <!-- End Header -->

    <div class="container mt-5 pt-5">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h5 class="mb-0">Perhitungan SAW</h5>
            </div>

            <div class="card-body">
                <p><strong>Bobot Kriteria:</strong></p>
                <ul>
                    <li>Harga: <?= $bobot['harga'] * 100; ?>%</li>
                    <li>Stok: <?= $bobot['stok'] * 100; ?>%</li>
                    <li>Minimal Pembelian: <?= $bobot['minimal_pembelian'] * 100; ?>%</li>
                </ul>

                <?php if (!empty($ranking)): ?>
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Supplier</th>
                                    <th>Kontak</th>
                                    <th>Harga<br><small>(Asli / Normalisasi)</small></th>
                                    <th>Stok<br><small>(Asli / Normalisasi)</small></th>
                                    <th>Minimal Pembelian<br><small>(Asli / Normalisasi)</small></th>
                                    <th><strong>Nilai Akhir</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ranking as $index => $row): ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($row['supplier']); ?></td>
                                        <td><?= htmlspecialchars($row['kontak']); ?></td>
                                        <td>
                                            <?= number_format($row['nilai_asli']['harga'], 2); ?> /
                                            <?= number_format($row['normalisasi']['harga'], 4); ?>
                                        </td>
                                        <td>
                                            <?= number_format($row['nilai_asli']['stok'], 2); ?> /
                                            <?= number_format($row['normalisasi']['stok'], 4); ?>
                                        </td>
                                        <td>
                                            <?= number_format($row['nilai_asli']['minimal_pembelian'], 2); ?> /
                                            <?= number_format($row['normalisasi']['minimal_pembelian'], 4); ?>
                                        </td>
                                        <td class="fw-bold text-success"><?= number_format($row['nilai'], 4); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center mb-0" role="alert">
                        ❌ Data tidak ditemukan.
                    </div>
                <?php endif; ?>
            </div>

            <div class="card-footer text-center">
                <a href="rekomendasi.php" class="btn btn-secondary">
                    Kembali ke Pencarian
                </a>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="text-white text-center py-3" style="background-color: #071952;">
        <div class="container">
            <p class="mb-0">&copy; 2025 SPK Pemilihan Supplier Bahan Baku Rotan</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>