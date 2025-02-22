<?php
require_once('includes/init.php');
require_once('vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('vendor/phpmailer/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_role = get_role();
if ($user_role == 'admin' || $user_role == 'user') {

    $page = "Pesan";
    require_once('template/header.php');

    // Link Google Drive sertifikat
    $certificate_link = "https://drive.google.com/file/d/13NAEub2w2t98MRh-LAc_VKvdGzpUnOYU/view?usp=sharing";

    // Contoh tautan sertifikat default jika tidak diunggah
    if (!$certificate_link) {
        $certificate_link = "http://localhost/default_certificate.pdf";
    }

    // Ambil status dari query string
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    ?>

    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div class="pagetitle d-flex align-items-center">
            <h1 class="me-3">Data Hasil</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data Hasil</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if ($status == 'success') { ?>
        <div class="alert alert-success" role="alert">
            Email telah dikirim.
        </div>
    <?php } elseif ($status == 'error') { ?>
        <div class="alert alert-danger" role="alert">
            Email tidak dapat dikirim. Silakan coba lagi nanti.
        </div>
    <?php } ?>


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Data Hasil</h5>
                <a href="cetak.php" class="btn btn-primary btn-sm">Eksport Data</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr align="center">
                            <th>Nama Alternatif</th>
                            <th>Nilai</th>
                            <th width="15%">Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 0;
                        $prev_value = null;
                        $rank = 0;
                        $query = mysqli_query($koneksi, "SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif=alternatif.id_alternatif ORDER BY hasil.nilai DESC");
                        while ($data = mysqli_fetch_array($query)) {
                            if ($prev_value === null || $data['nilai'] != $prev_value) {
                                $rank++;
                            }
                            ?>
                            <tr align="center">
                                <td align="left"><?= $data['nama'] ?></td>
                                <td><?= $data['nilai'] ?></td>
                                <td><?= $rank; ?></td>
                            </tr>
                            <?php
                            $prev_value = $data['nilai'];
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showSuccessMessage() {
            alert('Pesan WhatsApp telah berhasil dikirim.');
        }
    </script>

    <?php
    require_once('template/footer.php');
} else {
    header('Location: login.php');
}
?>