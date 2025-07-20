<?php
ob_start();
require_once('includes/init.php');
cek_login($role = array(1));

$page = "Ukuran Rotan";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Ukuran Rotan</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Ukuran Rotan</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUkuran">
        <i class="bi bi-plus"></i> Tambah Data
    </button>
</div>

<?php
// Fungsi untuk memformat ukuran
function formatUkuran($min, $max = null, $type = 'range') {
    if ($type === 'up') {
        return $min . " - up";
    }
    return $min . "-" . $max . " mm";
}

// Fungsi untuk parsing ukuran existing
function parseUkuran($ukuran) {
    $result = ['min' => 0, 'max' => 0, 'type' => 'range'];
    
    if (strpos($ukuran, ' - up') !== false) {
        $result['min'] = floatval(str_replace(' - up', '', $ukuran));
        $result['type'] = 'up';
    } else {
        $parts = explode('-', str_replace(' mm', '', $ukuran));
        $result['min'] = isset($parts[0]) ? floatval($parts[0]) : 0;
        $result['max'] = isset($parts[1]) ? floatval($parts[1]) : 0;
    }
    
    return $result;
}

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah_ukuran']) || isset($_POST['update_ukuran'])) {
        $ukuran_min = floatval($_POST['ukuran_min']);
        $ukuran_max = !empty($_POST['ukuran_max']) ? floatval($_POST['ukuran_max']) : null;
        $type = !empty($_POST['ukuran_max']) ? 'range' : 'up';
        
        $ukuran = formatUkuran($ukuran_min, $ukuran_max, $type);
        
        if (isset($_POST['update_ukuran'])) {
            $id_ukuran = intval($_POST['id_ukuran']);
            $check_query = "SELECT id_ukuran FROM ukuran_rotan WHERE ukuran = ? AND id_ukuran != ?";
            $stmt = mysqli_prepare($koneksi, $check_query);
            mysqli_stmt_bind_param($stmt, "si", $ukuran, $id_ukuran);
            mysqli_stmt_execute($stmt);
            $cek_ukuran = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($cek_ukuran) == 0) {
                $update_query = "UPDATE ukuran_rotan SET ukuran = ? WHERE id_ukuran = ?";
                $stmt = mysqli_prepare($koneksi, $update_query);
                mysqli_stmt_bind_param($stmt, "si", $ukuran, $id_ukuran);
                $success = mysqli_stmt_execute($stmt);
                $message = $success ? "Data berhasil diupdate." : "Gagal mengupdate data.";
                $alert_type = $success ? "success" : "danger";
            } else {
                $message = "Ukuran rotan sudah ada!";
                $alert_type = "danger";
            }
        } else {
            $check_query = "SELECT id_ukuran FROM ukuran_rotan WHERE ukuran = ?";
            $stmt = mysqli_prepare($koneksi, $check_query);
            mysqli_stmt_bind_param($stmt, "s", $ukuran);
            mysqli_stmt_execute($stmt);
            $cek_ukuran = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($cek_ukuran) == 0) {
                $insert_query = "INSERT INTO ukuran_rotan (ukuran) VALUES (?)";
                $stmt = mysqli_prepare($koneksi, $insert_query);
                mysqli_stmt_bind_param($stmt, "s", $ukuran);
                $success = mysqli_stmt_execute($stmt);
                $message = $success ? "Data berhasil ditambahkan." : "Gagal menambahkan data.";
                $alert_type = $success ? "success" : "danger";
            } else {
                $message = "Ukuran rotan sudah ada!";
                $alert_type = "danger";
            }
        }
        
        echo "<div class='alert alert-{$alert_type} alert-dismissible fade show mt-3' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    }
}

// Pesan status hapus
if (isset($_GET['status']) && $_GET['status'] == 'sukses-hapus') {
    echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Data berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}
?>

<!-- Tabel Data -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title text-center m-0">Data Ukuran Rotan</h5>
        <div class="table-responsive mt-3">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Ukuran Rotan</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_ukuran = mysqli_query($koneksi, "SELECT * FROM ukuran_rotan ORDER BY id_ukuran");
                    while ($row = mysqli_fetch_assoc($query_ukuran)):
                        $parsed = parseUkuran($row['ukuran']);
                    ?>
                        <tr>
                            <td align="center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['ukuran']); ?></td>
                            <td align="center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#modalEdit" 
                                            onclick="setEditData(<?= $row['id_ukuran']; ?>, <?= $parsed['min']; ?>, <?= $parsed['max']; ?>, '<?= $parsed['type']; ?>')">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="hapus-ukuran-rotan.php?id=<?= $row['id_ukuran']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahUkuran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Ukuran Rotan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Ukuran Minimum</label>
                        <input type="number" class="form-control" name="ukuran_min" required min="0" step="0.1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ukuran Maximum <small class="text-muted">(kosongkan untuk format "up")</small></label>
                        <input type="number" class="form-control" name="ukuran_max" min="0" step="0.1" 
                               placeholder="Kosongkan untuk format '16 - up'">
                    </div>
                    <div class="alert alert-info p-2">
                        <small><strong>Format:</strong><br>
                        • Dengan max: 12.5-14.5 mm<br>
                        • Tanpa max: 16 - up</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_ukuran" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit (Single Modal) -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Ukuran Rotan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_ukuran" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Ukuran Minimum</label>
                        <input type="number" class="form-control" name="ukuran_min" id="edit_min" required min="0" step="0.1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ukuran Maximum <small class="text-muted">(kosongkan untuk format "up")</small></label>
                        <input type="number" class="form-control" name="ukuran_max" id="edit_max" min="0" step="0.1">
                    </div>
                    <div class="alert alert-info p-2">
                        <small><strong>Format:</strong><br>
                        • Dengan max: 12.5-14.5 mm<br>
                        • Tanpa max: 16 - up</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update_ukuran" class="btn btn-primary btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setEditData(id, min, max, type) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_min').value = min;
    document.getElementById('edit_max').value = type === 'up' ? '' : max;
}
</script>

<?php require_once('template/footer.php'); ?>
<?php ob_end_flush(); ?>