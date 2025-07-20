<?php
// filepath: c:\laragon\www\anu\export-pdf.php
require_once('includes/init.php');
require __DIR__ . '/vendor/autoload.php';

// Ambil parameter pencarian dari POST
$id_jenis = $_POST['id_jenis'] ?? '';
$id_ukuran = $_POST['id_ukuran'] ?? '';
$kualitas = $_POST['kualitas'] ?? '';
$preset_bobot = $_POST['preset_bobot'] ?? '';

// Ambil nama jenis rotan
$nama_jenis = '-';
if ($id_jenis) {
    $q = mysqli_query($koneksi, "SELECT nama_jenis FROM jenis_rotan WHERE id_jenis='$id_jenis' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q)) $nama_jenis = $r['nama_jenis'];
}

// Ambil ukuran rotan
$nama_ukuran = '-';
if ($id_ukuran) {
    $q = mysqli_query($koneksi, "SELECT ukuran FROM ukuran_rotan WHERE id_ukuran='$id_ukuran' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q)) $nama_ukuran = $r['ukuran'];
}

// Ambil nama preset bobot
$nama_preset = '-';
if ($preset_bobot) {
    $q = mysqli_query($koneksi, "SELECT nama_preset FROM preset_bobot WHERE id_preset='$preset_bobot' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q)) $nama_preset = $r['nama_preset'];
}

// Ambil bobot preset
$query_bobot = "SELECT harga, stok, minimal_pembelian FROM preset_bobot WHERE id_preset = ?";
$stmt_bobot = mysqli_prepare($koneksi, $query_bobot);
mysqli_stmt_bind_param($stmt_bobot, 's', $preset_bobot);
mysqli_stmt_execute($stmt_bobot);
$result_bobot = mysqli_stmt_get_result($stmt_bobot);
$bobot = mysqli_fetch_assoc($result_bobot);

// Format bobot
$bobot_str = $bobot
    ? "Harga: " . number_format($bobot['harga'], 2) . " | Stok: " . number_format($bobot['stok'], 2) . " | Minimal Pembelian: " . number_format($bobot['minimal_pembelian'], 2)
    : "-";

// Tanggal cetak
$tanggal_cetak = date('d-m-Y H:i:s');

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// Tanggal cetak di pojok kanan atas
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, 'Dicetak: ' . $tanggal_cetak, 0, 1, 'R');

// Judul
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Hasil Rekomendasi Supplier', 0, 1, 'C');
$pdf->Ln(2);

// Keterangan pencarian
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(50, 7, 'Jenis Rotan', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(60, 7, $nama_jenis, 0, 0);

$pdf->Cell(30, 7, 'Ukuran', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(60, 7, $nama_ukuran, 0, 1);

$pdf->Cell(50, 7, 'Kualitas', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(60, 7, $kualitas ?: '-', 0, 0);

$pdf->Cell(30, 7, 'Preset Bobot', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(60, 7, $nama_preset, 0, 1);

$pdf->Cell(50, 7, 'Bobot Digunakan', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(120, 7, $bobot_str, 0, 1);

$pdf->Ln(2);

if (!$bobot) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Preset bobot tidak ditemukan.', 0, 1, 'C');
} else {
    // Query data supplier
    $query = "SELECT supplier.nama AS nama_supplier, supplier.kontak, supplier.alamat,
                data_rotan.harga, data_rotan.stok, data_rotan.minimal_pembelian
                FROM data_rotan
                JOIN supplier ON data_rotan.id_supplier = supplier.id_supplier
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
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
            $max['harga'] = max($max['harga'], $row['harga']);
            $max['stok'] = max($max['stok'], $row['stok']);
            $max['minimal_pembelian'] = max($max['minimal_pembelian'], $row['minimal_pembelian']);
        }

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
                'kontak'   => $row['kontak'],
                'alamat'   => $row['alamat'],
                'nilai'    => $nilai_saw,
            ];
        }

        // Urutkan berdasarkan nilai SAW
        usort($ranking, function ($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        // Header tabel
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(227, 242, 253);
        $pdf->Cell(20, 10, 'Peringkat', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Supplier', 1, 0, 'C', true);
        $pdf->Cell(80, 10, 'Alamat', 1, 0, 'C', true);
        $pdf->Cell(45, 10, 'Kontak', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Nilai', 1, 1, 'C', true);

        // Isi tabel
        $pdf->SetFont('Arial', '', 11);
        foreach ($ranking as $i => $row) {
            $pdf->Cell(20, 10, ($i + 1) . ($i === 0 ? ' (Terbaik)' : ''), 1, 0, 'C');
            $pdf->Cell(60, 10, $row['supplier'], 1, 0);
            $pdf->Cell(80, 10, $row['alamat'], 1, 0);
            $pdf->Cell(45, 10, $row['kontak'], 1, 0);
            $pdf->Cell(30, 10, number_format($row['nilai'], 4), 1, 1, 'C');
        }
    } else {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Data tidak ditemukan.', 0, 1, 'C');
    }
}

$html_table = $_POST['html_table'] ?? '';

if (!$html_table) {
    die('Tidak ada data untuk diekspor.');
}

// Tambahkan judul, tanggal, dsb jika perlu
$html = '
<style>
body { font-family: Arial; }
.table { border-collapse: collapse; width: 100%; }
.table th, .table td { border:1px solid #333; padding:6px; }
.table th { background:#e3f2fd; }
.badge { display:inline-block; padding:2px 8px; border-radius:4px; font-size:90%; }
.bg-success { background:#198754; color:#fff; }
.bg-primary { background:#0d6efd; color:#fff; }
</style>
<h3 style="text-align:center;">Hasil Rekomendasi Supplier</h3>
<p style="text-align:right;font-size:11px;">Dicetak: '.date('d-m-Y H:i:s').'</p>
' . $html_table;

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf->WriteHTML($html);
$mpdf->Output('hasil_rekomendasi_supplier.pdf', 'I');
exit;