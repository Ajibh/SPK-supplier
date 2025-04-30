<?php
require_once('includes/init.php');
cek_login($role = array(1));

// fungsi tambah preset bobot
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_tambah'])) {
    $nama_preset = mysqli_real_escape_string($koneksi, trim($_POST['nama_preset']));
    $harga = floatval($_POST['harga']);
    $stok = floatval($_POST['stok']);
    $minimal_pembelian = floatval($_POST['minimal_pembelian']);
    $status = $_POST['status'];

    // Cek apakah nama preset sudah ada
    $cek = mysqli_query($koneksi, "SELECT id_preset FROM preset_bobot WHERE nama_preset = '$nama_preset'");
    if (mysqli_num_rows($cek) > 0) {
        header('Location: preset-bobot.php?status=nama-sama');
        exit;
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO preset_bobot (nama_preset, harga, stok, minimal_pembelian, status)
            VALUES ('$nama_preset', '$harga', '$stok', '$minimal_pembelian', '$status')");

        if ($insert) {
            header('Location: preset-bobot.php?status=sukses-baru');
        } else {
            header('Location: preset-bobot.php?status=gagal-tambah');
        }
        exit;
    }
}

// Fungsi update preset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_edit']) && isset($_POST['edit_id_preset'])) {
    $id_preset = intval($_POST['edit_id_preset']);
    $nama_preset = mysqli_real_escape_string($koneksi, trim($_POST['edit_nama_preset']));
    $harga = floatval($_POST['edit_harga']);
    $stok = floatval($_POST['edit_stok']);
    $minimal_pembelian = floatval($_POST['edit_minimal_pembelian']);
    $status = $_POST['edit_status'];

    // Validasi nama preset tidak boleh duplikat ke preset lain
    $cek_nama = mysqli_query($koneksi, "SELECT id_preset FROM preset_bobot WHERE nama_preset = '$nama_preset' AND id_preset != '$id_preset'");
    if (mysqli_num_rows($cek_nama) > 0) {
        header('Location: preset-bobot.php?status=nama-sama');
        exit;
    }

    $update = mysqli_query($koneksi, "UPDATE preset_bobot SET 
        nama_preset = '$nama_preset',
        harga = '$harga',
        stok = '$stok',
        minimal_pembelian = '$minimal_pembelian',
        status = '$status'
        WHERE id_preset = '$id_preset'");

    if ($update) {
        header('Location: preset-bobot.php?status=sukses-edit');
    } else {
        header('Location: preset-bobot.php?status=gagal-edit');
    }
    exit;
}
?>