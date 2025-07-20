<?php
ob_start(); // Memulai output buffering
require_once('includes/init.php');
cek_login($role = array(1));

$page = "Jenis Rotan";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Jenis Rotan</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Jenis Rotan</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJenis">
        <i class="bi bi-plus"></i> Tambah Data
    </button>
</div>

<?php
// Fungsi Tambah Data Jenis Rotan
if (isset($_POST['tambah_jenis'])) {
    $nama_jenis = mysqli_real_escape_string($koneksi, trim($_POST['nama_jenis']));

    // Validasi: Cek apakah nama jenis sudah ada
    $cek_nama = mysqli_query($koneksi, "SELECT id_jenis FROM jenis_rotan WHERE nama_jenis = '$nama_jenis'");
    if (mysqli_num_rows($cek_nama) > 0) {
        header('Location: jenis-rotan.php?status=nama-sama');
        exit;
    } else {
        // Jika validasi lolos, simpan data
        $query = "INSERT INTO jenis_rotan (nama_jenis) VALUES ('$nama_jenis')";
        if (mysqli_query($koneksi, $query)) {
            header('Location: jenis-rotan.php?status=sukses-tambah');
        } else {
            header('Location: jenis-rotan.php?status=gagal-tambah');
        }
        exit;
    }
}

// Fungsi Update Data Jenis Rotan
if (isset($_POST['update_jenis'])) {
    $id_jenis = intval($_POST['id_jenis']);
    $nama_jenis = mysqli_real_escape_string($koneksi, trim($_POST['nama_jenis']));

    // Validasi: Cek apakah nama jenis sudah ada di data lain
    $cek_nama = mysqli_query($koneksi, "SELECT id_jenis FROM jenis_rotan WHERE nama_jenis = '$nama_jenis' AND id_jenis != '$id_jenis'");
    if (mysqli_num_rows($cek_nama) > 0) {
        header('Location: jenis-rotan.php?status=nama-sama');
        exit;
    } else {
        // Jika validasi lolos, update data
        $query = "UPDATE jenis_rotan SET nama_jenis = '$nama_jenis' WHERE id_jenis = '$id_jenis'";
        if (mysqli_query($koneksi, $query)) {
            header('Location: jenis-rotan.php?status=sukses-edit');
        } else {
            header('Location: jenis-rotan.php?status=gagal-edit');
        }
        exit;
    }
}


$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = '';
$type = ''; // success atau danger

switch ($status):
    case 'sukses-tambah':
        $msg = 'Data Jenis Rotan Berhasil Ditambahkan.';
        $type = 'success';
        break;
    case 'sukses-edit':
        $msg = 'Data Jenis Rotan Berhasil Diupdate.';
        $type = 'success';
        break;
    case 'sukses-hapus':
        $msg = 'Data Jenis Rotan Berhasil Dihapus.';
        $type = 'success';
        break;
    case 'nama-sama':
        $msg = 'Nama Jenis Rotan sudah ada! Silakan gunakan nama lain.';
        $type = 'danger';
        break;
    case 'gagal-tambah':
        $msg = 'Gagal Menambah Data Jenis Rotan.';
        $type = 'danger';
        break;
    case 'gagal-edit':
        $msg = 'Gagal Mengupdate Data Jenis Rotan.';
        $type = 'danger';
        break;
    case 'gagal-hapus':
        $msg = 'Gagal Menghapus Data karena masih digunakan di tabel lain.';
        $type = 'danger';
        break;
endswitch;

if (!empty($msg)):
?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show mt-3" role="alert">
    <?= $msg ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Tabel Jenis Rotan -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title text-center">Data Jenis Rotan</h5>
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th width="25%">Nama Jenis</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_jenis = mysqli_query($koneksi, "SELECT * FROM jenis_rotan");
                    while ($row = mysqli_fetch_assoc($query_jenis)):
                        ?>
                        <tr>
                            <td align="center"><?= $no++; ?></td>
                            <td><?= $row['nama_jenis']; ?></td>
                            <td align="center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm d-flex align-items-center" data-bs-toggle="modal"
                                        data-bs-target="#modalEditJenis<?= $row['id_jenis']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <a href="hapus-jenis-rotan.php?id=<?php echo $row['id_jenis']; ?>"
                                        class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data jenis rotan ini?')"
                                        title="Hapus Data">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Edit Jenis Rotan -->
                        <div class="modal fade" id="modalEditJenis<?= $row['id_jenis']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Jenis Rotan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_jenis" value="<?= $row['id_jenis']; ?>">
                                            <div class="mb-3">
                                                <label>Nama Jenis Rotan</label>
                                                <input type="text" class="form-control" name="nama_jenis"
                                                    value="<?= $row['nama_jenis']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update_jenis" class="btn btn-primary btn-sm">Simpan
                                                Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Jenis Rotan -->
<div class="modal fade" id="modalTambahJenis" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Rotan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Jenis Rotan</label>
                        <input type="text" class="form-control" name="nama_jenis" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_jenis" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once('template/footer.php'); ?>
<?php ob_end_flush(); // Mengakhiri output buffering ?>