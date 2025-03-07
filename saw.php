<?php
require_once('includes/init.php');
cek_login($role = array(2));

$page = "saw";
require_once('template/header.php');

if (!isset($_POST['lanjut'])) {
    echo "Silahkan Mengisi Halaman Input Data Rotan yang dicari terlebih dahulu!";
    exit;
}

// Ambil ID user yang sedang login
$id_user = $_SESSION['id_user']; // Sesuaikan dengan variabel session user ID yang digunakan

// Ambil bobot kriteria dari database berdasarkan user yang login
$query_bobot = "SELECT id_kriteria, bobot FROM bobot_ahp WHERE id_user = '$id_user'";
$result_bobot = mysqli_query($koneksi, $query_bobot);

$bobot = [];
while ($row_bobot = mysqli_fetch_assoc($result_bobot)) {
    $bobot[$row_bobot['kriteria']] = (float) $row_bobot['bobot']; // Simpan dalam array dengan tipe float
}

// Ambil data dari form filtering
$id_supplier = $_POST['id_supplier'];
$harga = $_POST['harga'];

// Ambil data supplier dari database berdasarkan hasil filter
$data_supplier = [];
foreach ($id_supplier as $index => $nama_supplier) {
    $query = "SELECT supplier.id_supplier, supplier.nama AS nama_supplier, supplier.kontak, 
                     data_rotan.minimal_pembelian, data_rotan.stok 
              FROM data_rotan
              JOIN supplier ON data_rotan.id_supplier = supplier.id_supplier
              WHERE supplier.nama = '$nama_supplier'";

    $result = mysqli_query($koneksi, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $data_supplier[] = [
            'nama_supplier' => $row['nama_supplier'],
            'harga' => $harga[$index],
            'minimal_pembelian' => $row['minimal_pembelian'],
            'stok' => $row['stok'],
            'kontak' => isset($row['kontak']) ? $row['kontak'] : 'Tidak tersedia'
        ];
    }
}

// ** Normalisasi Kriteria ** (AHP)
$max_harga = max(array_column($data_supplier, 'harga'));
$min_harga = min(array_column($data_supplier, 'harga'));

foreach ($data_supplier as &$supplier) {
    // ** Normalisasi Harga (Lebih kecil lebih baik) **
    $supplier['normal_harga'] = $min_harga / $supplier['harga'];

    // ** Normalisasi Minimal Pembelian **
    $supplier['normal_min_pembelian'] = ($supplier['minimal_pembelian'] == '1 kg') ? 3 :
        (($supplier['minimal_pembelian'] == '10 kg') ? 2 : 1);

    // ** Normalisasi Stok **
    $supplier['normal_stok'] = ($supplier['stok'] == 'Tersedia Selalu') ? 3 : 1;
}

// ** Hitung Skor Akhir (SAW) **
foreach ($data_supplier as &$supplier) {
    $supplier['total_skor'] =
        ($supplier['normal_harga'] * $bobot['harga']) +
        ($supplier['normal_min_pembelian'] * $bobot['minimal_pembelian']) +
        ($supplier['normal_stok'] * $bobot['stok']);
}

// ** Urutkan berdasarkan skor tertinggi **
usort($data_supplier, function ($a, $b) {
    return $b['total_skor'] <=> $a['total_skor'];
});
?>


<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Perankingan</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Hasil Perankingan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card -4">
    <h2 class="text-center">Hasil Perhitungan SPK Pemilihan Supplier</h2>
    <!-- Tabel Data Supplier -->
    <h4 class="mt-4">1. Data Awal Supplier</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Supplier</th>
                <th>Harga</th>
                <th>Minimal Pembelian</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_supplier as $supplier): ?>
                <tr>
                    <td><?= $supplier['nama_supplier'] ?></td>
                    <td>Rp <?= number_format($supplier['harga'], 0, ',', '.') ?></td>
                    <td><?= $supplier['minimal_pembelian'] ?></td>
                    <td><?= $supplier['stok'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tabel Normalisasi -->
    <h4 class="mt-4">2. Normalisasi Kriteria</h4>
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Nama Supplier</th>
                <th>Normalisasi Harga</th>
                <th>Normalisasi Minimal Pembelian</th>
                <th>Normalisasi Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_supplier as $supplier): ?>
                <tr>
                    <td><?= $supplier['nama_supplier'] ?></td>
                    <td><?= round($supplier['normal_harga'], 3) ?></td>
                    <td><?= round($supplier['normal_min_pembelian'], 3) ?></td>
                    <td><?= round($supplier['normal_stok'], 3) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tabel Perhitungan SAW -->
    <h4 class="mt-4">3. Perhitungan Skor Akhir SAW</h4>
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>Nama Supplier</th>
                <th>Skor Akhir</th>
                <th>Peringkat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $peringkat = 1;
            foreach ($data_supplier as $supplier):
                ?>
                <tr>
                    <td><?= $supplier['nama_supplier'] ?></td>
                    <td><strong><?= round($supplier['total_skor'], 3) ?></strong></td>
                    <td><strong>#<?= $peringkat++ ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>


<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mt-2">
            <h5 class="card-title">Hasil Perankingan Supplier</h5>
            <a href="export_excel.php" class="btn btn-sm btn-success">
                <i class="bi bi-file-earmark-excel"></i> Export Data
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th width="5%">Ranking</th>
                        <th>Nama Supplier</th>
                        <th>Harga (Rp)</th>
                        <th>Minimal Pembelian</th>
                        <th>Stok</th>
                        <th>Kontak</th> <!-- Tambahkan kolom kontak -->
                        <th>Skor Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rank = 1;
                    foreach ($data_supplier as $supplier):
                        ?>
                        <tr>
                            <td><strong><?= $rank++; ?></strong></td>
                            <td><?= htmlspecialchars($supplier['nama_supplier']); ?></td>
                            <td><?= number_format($supplier['harga'], 0, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($supplier['minimal_pembelian']); ?></td>
                            <td>
                                <span
                                    class="badge <?= ($supplier['stok'] == 'Tersedia Selalu') ? 'bg-success' : 'bg-warning'; ?>">
                                    <?= htmlspecialchars($supplier['stok']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="https://wa.me/<?= ($supplier['kontak']); ?>" target="_blank"
                                    class="btn btn-success btn-sm">
                                    <i class="bi bi-whatsapp"></i> Hubungi
                                </a>
                            </td>
                            <td><strong><?= number_format($supplier['total_skor'], 3, ',', '.'); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
require_once('template/footer.php')
    ?>