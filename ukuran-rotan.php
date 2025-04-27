<?php
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
// Fungsi Tambah Data Ukuran Rotan
if (isset($_POST['tambah_ukuran'])) {
    $ukuran = $_POST['ukuran'];
    $query = "INSERT INTO ukuran_rotan (ukuran) VALUES ('$ukuran')";
    if (mysqli_query($koneksi, $query)) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Data Ukuran Rotan Berhasil Ditambahkan.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Terjadi Kesalahan. Data Gagal Ditambahkan.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

// Fungsi Update Data Ukuran Rotan
if (isset($_POST['update_ukuran'])) {
    $id_ukuran = $_POST['id_ukuran'];
    $ukuran = $_POST['ukuran'];
    $query = "UPDATE ukuran_rotan SET ukuran='$ukuran' WHERE id_ukuran='$id_ukuran'";
    if (mysqli_query($koneksi, $query)) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Data Ukuran Rotan Berhasil Diupdate.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Terjadi Kesalahan. Data Gagal Diupdate.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

if (isset($_GET['status']) && $_GET['status'] == 'sukses-hapus') {
    echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Data Ukuran rotan berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<!-- Tabel Ukuran Rotan -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title text-center m-0">Data Ukuran Rotan</h5>
        <div class="table-responsive mt-3">
            <table id="ukuranRotanTable" class="table table-striped table-bordered">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Ukuran Rotan (mm)</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_ukuran = mysqli_query($koneksi, "SELECT * FROM ukuran_rotan");
                    while ($row = mysqli_fetch_assoc($query_ukuran)):
                        ?>
                        <tr>
                            <td align="center"><?= $no++; ?></td>
                            <td><?= $row['ukuran']; ?></td>
                            <td align="center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm d-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#modalEditUkuran<?= $row['id_ukuran']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <a href="hapus-ukuran-rotan.php?id=<?php echo $row['id_ukuran']; ?>"
                                        class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ukuran rotan ini?')"
                                        title="Hapus Data">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>

                        </tr>
                        <!-- Modal Edit Ukuran Rotan -->
                        <div class="modal fade" id="modalEditUkuran<?= $row['id_ukuran']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Ukuran Rotan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_ukuran" value="<?= $row['id_ukuran']; ?>">
                                            <div class="mb-3">
                                                <label>Ukuran Rotan</label>
                                                <input type="text" class="form-control" name="ukuran"
                                                    value="<?= $row['ukuran']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update_ukuran" class="btn btn-primary btn-sm">Simpan
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

<!-- Modal Tambah Ukuran Rotan -->
<div class="modal fade" id="modalTambahUkuran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Ukuran Rotan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Ukuran Rotan</label>
                        <input type="text" class="form-control" name="ukuran" required>
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

<script>
    $(document).ready(function () {
        $('#ukuranRotanTable').DataTable();
    });
</script>

<?php require_once('template/footer.php'); ?>