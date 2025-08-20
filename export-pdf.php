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
    if ($r = mysqli_fetch_assoc($q))
        $nama_jenis = $r['nama_jenis'];
}

// Ambil ukuran rotan
$nama_ukuran = '-';
if ($id_ukuran) {
    $q = mysqli_query($koneksi, "SELECT ukuran FROM ukuran_rotan WHERE id_ukuran='$id_ukuran' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q))
        $nama_ukuran = $r['ukuran'];
}

// Ambil nama preset bobot
$nama_preset = '-';
if ($preset_bobot) {
    $q = mysqli_query($koneksi, "SELECT nama_preset FROM preset_bobot WHERE id_preset='$preset_bobot' LIMIT 1");
    if ($r = mysqli_fetch_assoc($q))
        $nama_preset = $r['nama_preset'];
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

// Set timezone ke WIB (Asia/Jakarta)
date_default_timezone_set('Asia/Jakarta');

// Tanggal cetak
$tanggal_cetak = date('d-m-Y H:i:s');

// Extend FPDF untuk menambah fungsi MultiCell yang dapat border
class PDF extends FPDF
{
    function MultiCellRow($data, $width, $height, $align = 'L')
    {
        // Hitung jumlah baris yang dibutuhkan untuk setiap kolom
        $nb = 0;
        $nb_lines = array();
        foreach ($data as $i => $text) {
            $nb_lines[$i] = $this->NbLines($width[$i], $text);
            $nb = max($nb, $nb_lines[$i]);
        }

        // Simpan posisi Y awal
        $start_y = $this->GetY();

        // Gambar border untuk seluruh row
        $this->SetLineWidth(0.2);
        $total_width = array_sum($width);
        $this->Rect($this->GetX(), $start_y, $total_width, $nb * $height);

        // Gambar garis vertikal untuk kolom
        $x = $this->GetX();
        for ($i = 0; $i < count($width) - 1; $i++) {
            $x += $width[$i];
            $this->Line($x, $start_y, $x, $start_y + ($nb * $height));
        }

        // Cetak text di setiap kolom
        foreach ($data as $i => $text) {
            $this->SetXY($this->GetX() + ($i > 0 ? array_sum(array_slice($width, 0, $i)) : 0), $start_y);
            $this->MultiCell($width[$i], $height, $text, 0, $align[$i] ?? 'L');
        }

        // Set posisi Y ke baris berikutnya
        $this->SetXY($this->GetX(), $start_y + ($nb * $height));
    }

    function NbLines($w, $txt)
    {
        // Hitung jumlah baris yang dibutuhkan untuk text dengan lebar tertentu
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += isset($cw[$c]) ? $cw[$c] : 600;
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

// Tambahkan logo di kiri atas (disamping judul)
$pdf->Image('assets/img/lambang.png', 15, 10, 22); // x=15, y=10, width=22mm

// Tambahkan nama aplikasi "SIPEKTRA" di bawah logo
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(15, 33); // x sama dengan logo, y di bawah logo (10 + 22 + 1)
$pdf->Cell(22, 7, 'SIPEKTRA', 0, 0, 'C');

// Geser posisi judul agar tidak bertabrakan dengan logo
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetXY(40, 10); // Mulai judul setelah logo
$pdf->Cell(0, 12, 'SISTEM PENDUKUNG KEPUTUSAN', 0, 1, 'C');
$pdf->SetX(40);
$pdf->Cell(0, 10, 'PEMILIHAN SUPPLIER BAHAN BAKU ROTAN DI KABUPATEN CIREBON', 0, 1, 'C');

// Tambahkan keterangan metode SAW di bawah judul, kecil dan italic
$pdf->SetFont('Arial', 'I', 14);
$pdf->SetX(40);
$pdf->Cell(0, 7, 'Menggunakan metode Simple Additive Weighting', 0, 1, 'C');

// Beri space agar garis tidak menempel logo
$pdf->Ln(6); // Tambah jarak vertikal sebelum garis

// Garis pemisah judul
$pdf->SetLineWidth(0.7);
$pdf->Line(10, $pdf->GetY(), $pdf->GetPageWidth() - 10, $pdf->GetY());
$pdf->Ln(4);

// Judul laporan
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

$pdf->Ln(2);

if (!$bobot) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Preset bobot tidak ditemukan.', 0, 1, 'C');
} else {
    // Query data supplier
    $query = "SELECT supplier.nama AS nama_supplier, supplier.kontak, supplier.alamat,
                data_rotan.harga, data_rotan.stok, data_rotan.minimal_pembelian, data_rotan.updated_at
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
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        // Hitung nilai SAW
        $ranking = [];
        $max = ['harga' => 0, 'stok' => 0, 'minimal_pembelian' => 0];
        $min = ['harga' => null, 'stok' => null, 'minimal_pembelian' => null];
        foreach ($data as $row) {
            $max['harga'] = max($max['harga'], $row['harga']);
            $max['stok'] = max($max['stok'], $row['stok']);
            $max['minimal_pembelian'] = max($max['minimal_pembelian'], $row['minimal_pembelian']);
            $min['harga'] = is_null($min['harga']) ? $row['harga'] : min($min['harga'], $row['harga']);
            $min['stok'] = is_null($min['stok']) ? $row['stok'] : min($min['stok'], $row['stok']);
            $min['minimal_pembelian'] = is_null($min['minimal_pembelian']) ? $row['minimal_pembelian'] : min($min['minimal_pembelian'], $row['minimal_pembelian']);
        }
        foreach ($data as $row) {
            $normalisasi = [
                'harga' => $min['harga'] / $row['harga'],
                'stok' => $row['stok'] / $max['stok'],
                'minimal_pembelian' => $min['minimal_pembelian'] / $row['minimal_pembelian'],
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
                'updated_at' => $row['updated_at'],
                'nilai' => $nilai_saw,
            ];
        }

        // Urutkan
        usort($ranking, function ($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        // Perbesar ukuran kolom agar tabel lebih proporsional dan nyaman dipandang
        $col_peringkat = 25;  // cukup untuk "99 (Terbaik)" dan lebih lega
        $col_supplier  = 65;  // nama supplier lebih lebar
        $col_alamat    = 120; // alamat lebih lebar agar tidak terpotong
        $col_kontak    = 55;  // kontak lebih lebar

        $column_widths = [$col_peringkat, $col_supplier, $col_alamat, $col_kontak];
        $column_aligns = ['C', 'L', 'L', 'L'];

        // Header tabel
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetLineWidth(0.2);
        $pdf->SetFillColor(227, 242, 253);

        $pdf->Cell($col_peringkat, 10, 'Peringkat', 1, 0, 'C', true);
        $pdf->Cell($col_supplier, 10, 'Supplier', 1, 0, 'C', true);
        $pdf->Cell($col_alamat, 10, 'Alamat', 1, 0, 'C', true);
        $pdf->Cell($col_kontak, 10, 'Kontak', 1, 1, 'C', true);

        // Isi tabel dengan MultiCell
        $pdf->SetFont('Arial', '', 11);
        foreach ($ranking as $i => $row) {
            $peringkat = ($i + 1) . ($i === 0 ? ' (Terbaik)' : '');

            // Cek apakah perlu halaman baru
            if ($pdf->GetY() > ($pdf->GetPageHeight() - 30)) {
                $pdf->AddPage();
            }

            // Data untuk baris ini
            $row_data = [
                $peringkat,
                $row['supplier'],
                $row['alamat'],
                $row['kontak']
            ];

            // Cetak baris dengan MultiCell
            $pdf->MultiCellRow($row_data, $column_widths, 8, $column_aligns); // tinggi baris juga diperbesar
        }
    } else {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Data tidak ditemukan.', 0, 1, 'C');
    }
}

// PERBAIKAN: Tambahkan space sebelum tanggal cetak dan posisikan secara relatif
$pdf->Ln(10); // Tambah jarak dari konten sebelumnya

// Simpan posisi Y saat ini
$current_y = $pdf->GetY();

// Hitung posisi untuk tanggal cetak di kanan bawah halaman
$margin_right = 10;
$margin_bottom = 10;
$page_height = $pdf->GetPageHeight();
$page_width = $pdf->GetPageWidth();

// Set font untuk tanggal
$pdf->SetFont('Arial', '', 10);
$text_width = $pdf->GetStringWidth('Dicetak: ' . $tanggal_cetak);

// Jika konten sudah mendekati bawah halaman, tambah halaman baru
if ($current_y > ($page_height - 30)) {
    $pdf->AddPage();
    $current_y = 10; // Reset ke posisi atas halaman baru
}

// Posisikan tanggal cetak relatif dari konten terakhir, tapi tetap di kanan
$pdf->SetXY($page_width - $margin_right - $text_width, $current_y + 5);
$pdf->Cell($text_width, 10, 'Dicetak: ' . $tanggal_cetak, 0, 0, 'R');

// Output PDF
$pdf->Output('hasil_rekomendasi_supplier.pdf', 'I');
exit;