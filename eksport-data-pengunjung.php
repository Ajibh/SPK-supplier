<?php
require_once('includes/init.php');
cek_login($role = array(1));

// Atur header supaya download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="data_pengunjung.csv"');

// Buka output stream
$output = fopen('php://output', 'w');

// Header kolom CSV
fputcsv($output, ['No', 'IP Address', 'User Agent', 'Waktu', 'Jenis Rotan', 'Ukuran', 'Kualitas', 'Preset Bobot']);

// Ambil data dari database
$query = "SELECT * FROM log_pengunjung ORDER BY waktu DESC";
$result = mysqli_query($koneksi, $query);

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $no++,
        $row['ip_address'],
        $row['user_agent'],
        $row['waktu'],
        $row['jenis_rotan'],
        $row['ukuran'],
        $row['kualitas'],
        $row['preset_bobot']
    ]);
}

fclose($output);
exit;
