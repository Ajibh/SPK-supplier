<?php
date_default_timezone_set('Asia/Jakarta');

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // Jika localhost IPv6, ubah ke IPv4
    if ($ip === '::1') {
        $ip = '127.0.0.1';
    }
    return $ip;
}

function logPengunjung($koneksi, $jenis, $ukuran, $kualitas, $preset)
{
    $ip = getRealIpAddr();
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $waktu = date('Y-m-d H:i:s');
    $halaman = $_SERVER['REQUEST_URI'];

    $query = "INSERT INTO log_pengunjung 
        (ip_address, user_agent, waktu, jenis_rotan, ukuran, kualitas, preset_bobot) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'sssssss', $ip, $user_agent, $waktu, $jenis, $ukuran, $kualitas, $preset);
    mysqli_stmt_execute($stmt);
}
?>