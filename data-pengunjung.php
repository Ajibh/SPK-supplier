<?php
require_once('includes/init.php');
cek_login($role = array(1));

$page = "Data Pengunjung";
require_once('template/header.php');

// Ambil data pengunjung dari database
$query = "SELECT * FROM log_pengunjung ORDER BY waktu DESC";
$result = mysqli_query($koneksi, $query);

// Hitung pengunjung hari ini
$today = date('Y-m-d');
$q_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM log_pengunjung WHERE DATE(waktu) = '$today'");
$today_visitors = mysqli_fetch_assoc($q_today)['total'];

// Hitung pengunjung minggu ini (Senin s.d Minggu)
$start_week = date('Y-m-d', strtotime('monday this week'));
$end_week = date('Y-m-d', strtotime('sunday this week'));
$q_week = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM log_pengunjung WHERE DATE(waktu) BETWEEN '$start_week' AND '$end_week'");
$week_visitors = mysqli_fetch_assoc($q_week)['total'];

// Hitung pengunjung bulan ini
$this_month = date('Y-m');
$q_month = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM log_pengunjung WHERE DATE_FORMAT(waktu, '%Y-%m') = '$this_month'");
$month_visitors = mysqli_fetch_assoc($q_month)['total'];

// Hitung total pengunjung
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM log_pengunjung");
$total_visitors = mysqli_fetch_assoc($q_total)['total'];
?>

<div class="pagetitle d-flex align-items-center">
    <h1 class="me-3">Log Pengunjung</h1>
    <nav>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item active">Log Pengunjung Rekomendasi</li>
        </ol>
    </nav>
</div>

<div class="row mt-3">
    <div class="col-lg-3 col-md-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Pengunjung Hari Ini</h5>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="ps-3">
                        <h5 class="fw-bold mb-0"><?= $today_visitors; ?></h5>
                    </div>
                    <div
                        class="card-icon bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-day fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Pengunjung Minggu Ini</h5>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="ps-3">
                        <h5 class="fw-bold mb-0"><?= $week_visitors; ?></h5>
                    </div>
                    <div
                        class="card-icon bg-warning bg-opacity-10 text-warning rounded d-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-week fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Pengunjung Bulan Ini</h5>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="ps-3">
                        <h5 class="fw-bold mb-0"><?= $month_visitors; ?></h5>
                    </div>
                    <div
                        class="card-icon bg-success bg-opacity-10 text-success rounded d-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-month fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Total Pengunjung</h5>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="ps-3">
                        <h5 class="fw-bold mb-0"><?= $total_visitors; ?></h5>
                    </div>
                    <div
                        class="card-icon bg-danger bg-opacity-10 text-danger rounded d-flex align-items-center justify-content-center">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body text-sm">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title text-center m-0">Catatan Pengunjung</h5>
            <a href="eksport-data-pengunjung.php" onclick="exportData()" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-spreadsheet"></i> Download CSV
            </a>
        </div>
        <table id="dataTable" class="table table-sm table-striped table-bordered align-middle small text-center">
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle">No</th>
                    <th rowspan="2" width="10%" class="align-middle">IP Address</th>
                    <th rowspan="2" class="align-middle">User Agent</th>
                    <th rowspan="2" class="align-middle">Waktu</th>
                    <th colspan="4">Preferensi</th>
                </tr>
                <tr>
                    <th>Jenis Rotan</th>
                    <th>Ukuran</th>
                    <th>Kualitas</th>
                    <th>Preset Bobot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                <td>' . $no++ . '</td>
                <td>' . htmlspecialchars($row['ip_address']) . '</td>
                <td class="text-start">' . htmlspecialchars($row['user_agent']) . '</td>
                <td>' . htmlspecialchars($row['waktu']) . '</td>
                <td>' . htmlspecialchars($row['jenis_rotan']) . '</td>
                <td>' . htmlspecialchars($row['ukuran']) . '</td>
                <td>' . htmlspecialchars($row['kualitas']) . '</td>
                <td>' . htmlspecialchars($row['preset_bobot']) . '</td>
            </tr>';
                }
                ?>
            </tbody>
        </table>

    </div>
</div>

<script>
    function exportData() {
        // Tampilkan loading
        Swal.fire({
            title: 'Mengekspor data...',
            text: 'Mohon tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Download file
        window.location.href = 'export-data-pengunjung.php';

        // Tutup loading setelah 2 detik
        setTimeout(function () {
            Swal.close();
        }, 2000);
    }
</script>

<?php require_once('template/footer.php'); ?>