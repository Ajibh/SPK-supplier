<?php
function prosesSaw($koneksi, $id_jenis, $id_ukuran, $kualitas, $preset_bobot) {
    // Ambil bobot preset
    $query_bobot = "SELECT harga, stok, minimal_pembelian FROM preset_bobot WHERE id_preset = ?";
    $stmt_bobot = mysqli_prepare($koneksi, $query_bobot);
    mysqli_stmt_bind_param($stmt_bobot, 's', $preset_bobot);
    mysqli_stmt_execute($stmt_bobot);
    $result_bobot = mysqli_stmt_get_result($stmt_bobot);
    $bobot = mysqli_fetch_assoc($result_bobot);

    if (!$bobot) return ['error' => 'Preset bobot tidak ditemukan.'];

    // Normalisasi bobot
    $total_bobot = $bobot['harga'] + $bobot['stok'] + $bobot['minimal_pembelian'];
    if ($total_bobot > 0) {
        $bobot['harga'] /= $total_bobot;
        $bobot['stok'] /= $total_bobot;
        $bobot['minimal_pembelian'] /= $total_bobot;
    }

    // Query data supplier
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

    if (mysqli_num_rows($result) == 0) return ['error' => 'Data tidak ditemukan.'];

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
    usort($ranking, function ($a, $b) {
        return $b['nilai'] <=> $a['nilai'];
    });
    return ['ranking' => $ranking];
}
?>