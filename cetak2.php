<?php
require_once ('includes/init.php');

// Mendapatkan user dengan ranking tertinggi
$query = mysqli_query($koneksi, "SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif=alternatif.id_alternatif ORDER BY hasil.nilai DESC LIMIT 1");
$data = mysqli_fetch_array($query);
?>

<html>

<head>
    <title>Sertifikat Penghargaan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');

        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .certificate-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f7f7f7;
        }

        .certificate {
            border: 20px solid #1e3799;
            padding: 20px;
            width: 1000px;
            height: 600px;
            background: #fff;
            color: #000;
            position: relative;
        }

        .certificate h1 {
            font-size: 70px;
            margin-bottom: 10px;
            color: #1e3799;
            font-family: 'Pacifico', cursive;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate h1 img {
            width: 80px;
            height: auto;
            margin-right: 10px;
        }

        .certificate h2 {
            font-size: 30px;
            margin: 10px 0;
        }

        .certificate p {
            font-size: 16px;
            margin: 10px 0;
        }

        .signature {
            position: absolute;
            right: 10px;
            bottom: 10px;
            text-align: right;
        }

        .signature img {
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="certificate-container">
        <div class="certificate">
            <h1>
                <img src="assets/img/logo1.png" alt="Logo">
                Sertifikat Penghargaan
            </h1>
            <p>Diberikan kepada</p>
            <br>
            <h2><?= $data['nama']; ?></h2>
            <br>
            <p>Atas prestasinya sebagai peringkat pertama dalam pemilihan <b>GURU TERBAIK</b> di SMK Farmasi YPIB
                Cirebon. Terima Kasih Atas Dedikasi nya selama menjadi guru sehingga terpilih menjadi Guru Terbaik.</p>
            <p>Dengan nilai: <?= $data['nilai']; ?></p>
            <br>
            <p><i>Tahun: <?= date('Y'); ?></i></p>
            <div class="signature">
                <br>
                <p>Cirebon, Agustus 2024</p>
                <p>Kepala Sekolah</p>
                <img src="assets/img/ttd.png" alt="Tanda Tangan Kepala Sekolah" width="700" height="200">
                <p><b>Dewi Pranita Motik, S.Pd., ME.</b></p>
            </div>
        </div>
    </div>
</body>

</html>