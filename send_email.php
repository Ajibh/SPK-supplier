<?php
// Include autoloader Composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['email']) && isset($_GET['nama']) && isset($_GET['nilai']) && isset($_GET['certificate'])) {
    $email = $_GET['email'];
    $nama = $_GET['nama'];
    $nilai = $_GET['nilai'];
    $certificate_link = $_GET['certificate'];

    $mail = new PHPMailer(true);

    try {
        // Pengaturan Server
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Sesuaikan dengan server SMTP Anda
        $mail->SMTPAuth = true;
        $mail->Username = 'mutiaraputripratiwi123@gmail.com'; // Ganti dengan email Anda
        $mail->Password = 'becqreoseuaiqduw'; // Ganti dengan password email Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Gunakan 'tls' atau 'ssl'
        $mail->Port = 587; // Gunakan 587 untuk TLS atau 465 untuk SSL

        // Penerima dan Pengirim
        $mail->setFrom('mutiaraputripratiwi123@gmail.com', 'Selamat Anda Terpilih'); // Ganti dengan email Anda
        $mail->addAddress($email, $nama); // Tambahkan penerima email dan nama

        // Konten Email
        $mail->isHTML(true);
        $mail->Subject = 'Penghargaan Guru Terbaik';
        $mail->Body = "Selamat, $nama! Anda terpilih menjadi guru terbaik dengan skor $nilai. Berikut adalah sertifikat penghargaan Anda: <a href='$certificate_link'>Lihat Sertifikat</a>";
        $mail->AltBody = "Selamat, $nama! Anda terpilih menjadi guru terbaik dengan skor $nilai. Berikut adalah sertifikat penghargaan Anda: $certificate_link";

        $mail->send();

        // Arahkan ke pesan.php dengan status sukses
        header('Location: pesan.php?status=success');
        exit();
    } catch (Exception $e) {
        // Arahkan ke pesan.php dengan status gagal
        header('Location: pesan.php?status=error');
        exit();
    }
}
?>