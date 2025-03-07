<?php
require_once('includes/init.php');
cek_login($role = array(1));

$page = "Jenis_Ukuran_Rotan";
require_once('template/header.php');
?>


<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Data Jenis dan Ukuran Rotan</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Jenis dan Ukuran Rotan</li>
            </ol>
        </nav>
    </div>
</div>

<?php
// Fungsi Tambah Data Jenis Rotan
if (isset($_POST['tambah_jenis'])) {
    $nama_jenis = $_POST['nama_jenis'];
    $deskripsi = $_POST['deskripsi'];
    $query = "INSERT INTO jenis_rotan (nama_jenis, deskripsi) VALUES ('$nama_jenis', '$deskripsi')";
    if (mysqli_query($koneksi, $query)) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Data Jenis Rotan Berhasil Ditambahkan.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Gagal Menambah Data Jenis Rotan.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

// Fungsi Update Data Jenis Rotan
if (isset($_POST['update_jenis'])) {
    $id_jenis = $_POST['id_jenis'];
    $nama_jenis = $_POST['nama_jenis'];
    $deskripsi = $_POST['deskripsi'];
    $query = "UPDATE jenis_rotan SET nama_jenis='$nama_jenis', deskripsi='$deskripsi' WHERE id_jenis='$id_jenis'";
    if (mysqli_query($koneksi, $query)) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Data Jenis Rotan Berhasil Diupdate.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Gagal Mengupdate Data Jenis Rotan.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}
?>

<!-- Tabel Jenis Rotan -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title m-0">Jenis Rotan</h5>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJenis">
                <i class="bi bi-plus"></i> Tambah Data Jenis Rotan
            </button>
        </div>
        <div class="table-responsive">
            <table id="jenisRotanTable" class="table table-striped table-bordered">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th width="25%">Nama Jenis</th>
                        <th>Deskripsi</th>
                        <th width="10%">Aksi</th>
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
                            <td><?= $row['deskripsi']; ?></td>
                            <td align="center">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEditJenis<?= $row['id_jenis']; ?>">Edit</button>
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
                                            <div class="mb-3">
                                                <label>Deskripsi</label>
                                                <textarea class="form-control" name="deskripsi" rows="3"
                                                    ><?= $row['deskripsi']; ?></textarea>
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
?>

<!-- Tabel Ukuran Rotan -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title m-0">Ukuran Rotan</h5>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUkuran">
                <i class="bi bi-plus"></i> Tambah Data Ukuran Rotan
            </button>
        </div>
        <div class="table-responsive mt-3">
            <table id="ukuranRotanTable" class="table table-striped table-bordered">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Ukuran Rotan (mm)</th>
                        <th width="10%">Aksi</th>
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
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEditUkuran<?= $row['id_ukuran']; ?>">Edit</button>
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
        $('#jenisRotanTable').DataTable();
        $('#ukuranRotanTable').DataTable();
    });
</script>

<?php require_once('template/footer.php'); ?>