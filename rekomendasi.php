<?php
require_once('includes/konek-db.php');

$showResults = false;
$searchResults = '';

if (isset($_POST['submit'])) {
    $id_jenis = mysqli_real_escape_string($koneksi, $_POST['id_jenis']);
    $id_ukuran = mysqli_real_escape_string($koneksi, $_POST['id_ukuran']);
    $kualitas = mysqli_real_escape_string($koneksi, $_POST['kualitas']);
    $preset_bobot = mysqli_real_escape_string($koneksi, $_POST['preset_bobot']);
    $showResults = true;

    // Ambil bobot dari preset yang dipilih
    $query_bobot = "SELECT harga, stok, minimal_pembelian FROM preset_bobot WHERE id_preset = ?";
    $stmt_bobot = mysqli_prepare($koneksi, $query_bobot);
    mysqli_stmt_bind_param($stmt_bobot, 's', $preset_bobot);
    mysqli_stmt_execute($stmt_bobot);
    $result_bobot = mysqli_stmt_get_result($stmt_bobot);
    $bobot = mysqli_fetch_assoc($result_bobot);

    if (!$bobot) {
        $searchResults = '<p class="text-danger text-center">❌ Preset bobot tidak ditemukan.</p>';
    } else {
        // Query ambil data supplier beserta alamat
        $query = "SELECT supplier.id_supplier, supplier.nama AS nama_supplier, supplier.kontak, supplier.alamat,
                  jenis_rotan.nama_jenis, ukuran_rotan.ukuran, data_rotan.harga, data_rotan.stok, data_rotan.minimal_pembelian
                  FROM data_rotan
                  JOIN supplier ON data_rotan.id_supplier = supplier.id_supplier
                  JOIN jenis_rotan ON data_rotan.id_jenis = jenis_rotan.id_jenis
                  JOIN ukuran_rotan ON data_rotan.id_ukuran = ukuran_rotan.id_ukuran
                  WHERE data_rotan.id_jenis = ? 
                  AND data_rotan.id_ukuran = ?
                  AND data_rotan.kualitas = ?
                  AND data_rotan.harga > 0";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $id_jenis, $id_ukuran, $kualitas);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $data = [];
            $max = ['harga' => 0, 'stok' => 0, 'minimal_pembelian' => 0];
            $min = ['harga' => null, 'stok' => null, 'minimal_pembelian' => null];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
                $max['harga'] = max($max['harga'], $row['harga']);
                $max['stok'] = max($max['stok'], $row['stok']);
                $max['minimal_pembelian'] = max($max['minimal_pembelian'], $row['minimal_pembelian']);
                $min['harga'] = is_null($min['harga']) ? $row['harga'] : min($min['harga'], $row['harga']);
                $min['stok'] = is_null($min['stok']) ? $row['stok'] : min($min['stok'], $row['stok']);
                $min['minimal_pembelian'] = is_null($min['minimal_pembelian']) ? $row['minimal_pembelian'] : min($min['minimal_pembelian'], $row['minimal_pembelian']);
            }

            // Hitung nilai SAW
            $ranking = [];
            foreach ($data as $row) {
                $normalisasi = [
                    'harga' => $min['harga'] / $row['harga'], // cost
                    'stok' => $row['stok'] / $max['stok'], // benefit
                    'minimal_pembelian' => $min['minimal_pembelian'] / $row['minimal_pembelian'], // cost
                ];
                $nilai_saw = (
                    $normalisasi['harga'] * $bobot['harga'] +
                    $normalisasi['stok'] * $bobot['stok'] +
                    $normalisasi['minimal_pembelian'] * $bobot['minimal_pembelian']
                );
                $ranking[] = [
                    'supplier' => $row['nama_supplier'],
                    'kontak' => $row['kontak'],
                    'alamat' => $row['alamat'],
                    'nilai' => $nilai_saw,
                ];
            }

            // Urutkan berdasarkan nilai SAW
            usort($ranking, function ($a, $b) {
                return $b['nilai'] <=> $a['nilai'];
            });

            // Tampilkan hasil perankingan
            ob_start();
            ?>
            <div class="table-responsive">
                <table class="table align-middle table-hover table-bordered text-center shadow-sm rounded">
                    <thead class="table-primary">
                        <tr>
                            <th>Peringkat</th>
                            <th>Supplier</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ranking as $index => $row): ?>
                            <tr<?= $index === 0 ? ' class="table-success fw-bold"' : '' ?>>
                                <td>
                                    <?= $index + 1; ?>
                                    <?php if ($index === 0): ?>
                                        <span class="badge bg-success ms-2">Terbaik</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['supplier']); ?></td>
                                <td><?= htmlspecialchars($row['alamat']); ?></td>
                                <td>
                                    <?php
                                    $telp = preg_replace('/[^0-9]/', '', $row['kontak']);
                                    if ($telp):
                                        ?>
                                        <a href="https://wa.me/<?= $telp ?>" target="_blank"
                                            class="btn btn-outline-success btn-sm px-2 py-1 d-inline-flex align-items-center"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Chat via WhatsApp">
                                            <i class="bi bi-whatsapp" style="font-size:1.3em;"></i>
                                        </a>
                                    <?php endif; ?>
                                    <span class="badge bg-success ms-2" style="font-size:1em;">
                                        <?= htmlspecialchars($row['kontak']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary" style="font-size:1em;">
                                        <?= number_format($row['nilai'], 4); ?>
                                    </span>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                        new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                });
            </script>
            <?php
            $searchResults = ob_get_clean();
        } else {
            $searchResults = '<p class="text-danger text-center">❌ Mohon maaf, data yang anda cari tidak ditemukan.</p>';
        }
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
                                    if ($result_jenis && mysqli_num_rows($result_jenis) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_jenis)) {
                                            echo '<li><a class="dropdown-item" href="#" data-value="' . htmlspecialchars($row['id_jenis']) . '">' . htmlspecialchars($row['nama_jenis']) . '</a></li>';
                                        }
                                    }
                                    ?>
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

                    <div class="row mt-4 text-center">
                        <div class="col-md-12">
                            <label for="preset_bobot" class="form-label">Preset Bobot</label>
                            <select name="preset_bobot" id="preset_bobot" class="form-control" required>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title text-center mb-0">Hasil Rekomendasi Pencarian</h5>
                <div>
                    <a href="saw.php" class="btn btn-sm btn-info me-2">
                        <i class="bi bi-calculator"></i> Cek Perhitungan
                    </a>
                    <form action="export-pdf.php" method="post" target="_blank" style="display:inline;">
                        <input type="hidden" name="id_jenis" value="<?= htmlspecialchars($_POST['id_jenis'] ?? '') ?>">
                        <input type="hidden" name="id_ukuran"
                            value="<?= htmlspecialchars($_POST['id_ukuran'] ?? '') ?>">
                        <input type="hidden" name="kualitas" value="<?= htmlspecialchars($_POST['kualitas'] ?? '') ?>">
                        <input type="hidden" name="preset_bobot"
                            value="<?= htmlspecialchars($_POST['preset_bobot'] ?? '') ?>">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <!-- Keterangan Pencarian (1 Baris, Teks Kecil) -->
                <div class="row mb-3 justify-content-center text-center">
                    <div class="col-md-12">
                        <div class="px-3 py-2 text-muted small">
                            <strong>Jenis Rotan:</strong>
                            <?= htmlspecialchars(getNamaJenis($koneksi, $_POST['id_jenis'] ?? '')) ?> |
                            <strong>Ukuran:</strong>
                            <?= htmlspecialchars(getNamaUkuran($koneksi, $_POST['id_ukuran'] ?? '')) ?> |
                            <strong>Kualitas:</strong>
                            <?= htmlspecialchars($_POST['kualitas'] ?? '-') ?> |
                            <strong>Preset Bobot:</strong>
                            <?= htmlspecialchars(getNamaPreset($koneksi, $_POST['preset_bobot'] ?? '')) ?>
                        </div>
                    </div>
                </div>


                <div id="searchResults">
                    <?= $searchResults ?>
                </div>
                <div class="text-center mt-3">
                    <button id="backButton" class="btn btn-sm btn-secondary">Kembali ke Pencarian</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle dropdown pilihan Jenis dan Ukuran Rotan
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value'); // Ambil nilai dari data-value
                    const text = this.textContent.trim(); // Ambil teks dari item dropdown

                    // Tentukan dropdown dan input hidden yang sesuai
                    const dropdown = this.closest('.dropdown');
                    if (dropdown) {
                        const jenisDropdown = dropdown.querySelector('#dropdownJenisRotan');
                        const ukuranDropdown = dropdown.querySelector('#dropdownUkuranRotan');
                        const idJenisInput = document.getElementById('id_jenis');
                        const idUkuranInput = document.getElementById('id_ukuran');

                        if (jenisDropdown && idJenisInput) {
                            idJenisInput.value = value; // Set nilai hidden input
                            jenisDropdown.textContent = text; // Set teks dropdown
                        }
                        if (ukuranDropdown && idUkuranInput) {
                            idUkuranInput.value = value; // Set nilai hidden input
                            ukuranDropdown.textContent = text; // Set teks dropdown
                        }
                    }
                });
            });

            // Tombol kembali ke form pencarian
            const backButton = document.getElementById('backButton');
            if (backButton) {
                backButton.addEventListener('click', function () {
                    const resultCard = document.getElementById('resultCard');
                    const formCard = document.getElementById('formCard');
                    if (resultCard && formCard) {
                        resultCard.style.display = 'none';
                        formCard.style.display = 'block';
                    }
                });
            }

            // Cek kondisi PHP apakah ingin langsung scroll ke hasil
            <?php if (isset($showResults) && $showResults): ?>
                const resultCard = document.getElementById('resultCard');
                if (resultCard) {
                    resultCard.scrollIntoView({ behavior: 'smooth' });
                }
            <?php endif; ?>

            // Validasi sebelum submit
            document.getElementById('searchForm').addEventListener('submit', function (e) {
                const idJenis = document.getElementById('id_jenis').value;
                const idUkuran = document.getElementById('id_ukuran').value;
                const kualitas = document.querySelector('[name="kualitas"]').value;
                const preset = document.getElementById('preset_bobot').value;

                if (!idJenis || !idUkuran || !kualitas || !preset) {
                    alert('Semua kolom wajib diisi!');
                    e.preventDefault();
                }
            });
        });
    </script>


    <!-- Bootstrap JS and Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>

<?php
function getNamaJenis($koneksi, $id)
{
    if (!$id)
        return '-';
    $q = mysqli_query($koneksi, "SELECT nama_jenis FROM jenis_rotan WHERE id_jenis='" . mysqli_real_escape_string($koneksi, $id) . "' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q))
        return $r['nama_jenis'];
    return '-';
}
function getNamaUkuran($koneksi, $id)
{
    if (!$id)
        return '-';
    $q = mysqli_query($koneksi, "SELECT ukuran FROM ukuran_rotan WHERE id_ukuran='" . mysqli_real_escape_string($koneksi, $id) . "' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q))
        return $r['ukuran'];
    return '-';
}
function getNamaPreset($koneksi, $id)
{
    if (!$id)
        return '-';
    $q = mysqli_query($koneksi, "SELECT nama_preset FROM preset_bobot WHERE id_preset='" . mysqli_real_escape_string($koneksi, $id) . "' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q))
        return $r['nama_preset'];
    return '-';
}
?>