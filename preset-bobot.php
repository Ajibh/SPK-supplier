<?php
require_once('includes/init.php');
cek_login($role = array(1));

$page = "Preset Bobot";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between">
    <div class="pagetitle d-flex align-items-center">
        <h1 class="me-3">Data Preset Bobot</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Preset Bobot</li>
            </ol>
        </nav>
    </div>
    <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">Buat Preset</a>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_preset = trim($_POST['nama_preset']);
    $harga = floatval($_POST['harga']);
    $stok = floatval($_POST['stok']);
    $minimal_pembelian = floatval($_POST['minimal_pembelian']);
    $status = $_POST['status'];

    // Validasi nama tidak boleh sama
    $cek = mysqli_query($koneksi, "SELECT id_preset FROM preset_bobot WHERE nama_preset = '$nama_preset'");
    if (mysqli_num_rows($cek) > 0) {
        echo '<div class="alert alert-danger">Nama preset sudah digunakan.</div>';
    } else {
        // Insert data
        $insert = mysqli_query($koneksi, "INSERT INTO preset_bobot (nama_preset, harga, stok, minimal_pembelian, status)
            VALUES ('$nama_preset', '$harga', '$stok', '$minimal_pembelian', '$status')");

        if ($insert) {
            echo '<div class="alert alert-success">Preset berhasil ditambahkan.</div>';
        } else {
            echo '<div class="alert alert-danger">Gagal menyimpan data.</div>';
        }
    }
}
?>

<!-- Alert untuk Hapus Data -->
<?php
if (isset($_GET['status']) && $_GET['status'] == 'sukses-hapus') {
    echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Data preset berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<!-- Tabel Preset Bobot -->
<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title text-center">Daftar Preset Bobot</h5>
        <table id="dataTable" class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Preset</th>
                    <th>Harga (%)</th>
                    <th>Stok (%)</th>
                    <th>Minimal Pembelian (%)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = "SELECT * FROM preset_bobot ORDER BY id_preset ASC";
                $result = mysqli_query($koneksi, $query);
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_preset']) ?></td>
                        <td><?= $row['harga'] ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td><?= $row['minimal_pembelian'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <!-- Tombol Edit -->
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                    data-bs-target="#editModal" data-id="<?= $row['id_preset'] ?>"
                    data-nama="<?= htmlspecialchars($row['nama_preset']) ?>"
                    data-harga="<?= number_format($row['harga'], 1) ?>" 
                    data-stok="<?= number_format($row['stok'], 1) ?>"
                    data-minimal="<?= number_format($row['minimal_pembelian'], 1) ?>" 
                    data-status="<?= $row['status'] ?>"
                    title="Edit Data">
                    <i class="bi bi-pencil-square"></i>
                </button>

                                <!-- Tombol Hapus -->
                                <a href="hapus-preset.php?id=<?= $row['id_preset'] ?>" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="Hapus Data"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus preset bobot ini?')">
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

<!-- Modal Tambah Preset -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Preset Bobot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="formPreset">
                    <div class="mb-3">
                        <label for="nama_preset" class="form-label">Nama Preset</label>
                        <input type="text" class="form-control" id="nama_preset" name="nama_preset" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php for ($i = 0.1; $i <= 0.8; $i += 0.1): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="harga" id="harga<?= $i ?>"
                                        value="<?= number_format($i, 1) ?>" required>
                                    <label class="form-check-label" for="harga<?= $i ?>"><?= number_format($i, 1) ?></label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php for ($i = 0.1; $i <= 0.8; $i += 0.1): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="stok" id="stok<?= $i ?>"
                                        value="<?= number_format($i, 1) ?>" required>
                                    <label class="form-check-label" for="stok<?= $i ?>"><?= number_format($i, 1) ?></label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minimal Pembelian</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php for ($i = 0.1; $i <= 0.8; $i += 0.1): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="minimal_pembelian" id="min<?= $i ?>"
                                        value="<?= number_format($i, 1) ?>" required>
                                    <label class="form-check-label" for="min<?= $i ?>"><?= number_format($i, 1) ?></label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div id="bobotAlert" class="text-danger small mt-2" style="display: none;">
                        Total bobot saat ini <span id="currentTotal">0</span>. Total harus tepat <strong>1.0</strong>.
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Preset -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Preset Bobot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="edit_preset.php" id="formEditPreset">
                    <input type="hidden" id="edit_id_preset" name="id_preset">
                    <div class="mb-3">
                        <label for="edit_nama_preset" class="form-label">Nama Preset</label>
                        <input type="text" class="form-control" id="edit_nama_preset" name="nama_preset" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php for ($i = 0.1; $i <= 0.8; $i += 0.1): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="harga" id="edit_harga<?= $i ?>"
                                        value="<?= number_format($i, 1) ?>" required>
                                    <label class="form-check-label" for="edit_harga<?= $i ?>"><?= number_format($i, 1) ?></label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php for ($i = 0.1; $i <= 0.8; $i += 0.1): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="stok" id="edit_stok<?= $i ?>"
                                        value="<?= number_format($i, 1) ?>" required>
                                    <label class="form-check-label" for="edit_stok<?= $i ?>"><?= number_format($i, 1) ?></label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minimal Pembelian</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php for ($i = 0.1; $i <= 0.8; $i += 0.1): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="minimal_pembelian" id="edit_min<?= $i ?>"
                                        value="<?= number_format($i, 1) ?>" required>
                                    <label class="form-check-label" for="edit_min<?= $i ?>"><?= number_format($i, 1) ?></label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div id="bobotAlert" class="text-danger small mt-2" style="display: none;">
                        Total bobot saat ini <span id="currentTotal">0</span>. Total harus tepat <strong>1.0</strong>.
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Mengisi Data ke Modal -->
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang diklik
        var id = button.data('id');
        var nama = button.data('nama');
        var harga = button.data('harga');
        var stok = button.data('stok');
        var minimal = button.data('minimal');
        var status = button.data('status');

        // Set nilai-nilai ke dalam modal
        $('#edit_id_preset').val(id);
        $('#edit_nama_preset').val(nama);

        // Set radio button sesuai dengan nilai yang ada di database
        $('input[name="harga"][value="'+ harga +'"]').prop('checked', true);
        $('input[name="stok"][value="'+ stok +'"]').prop('checked', true);
        $('input[name="minimal_pembelian"][value="'+ minimal +'"]').prop('checked', true);
        $('#edit_status').val(status);
    });
</script>

<!-- script untuk form tmbah preset -->
<script>
    function updateBobotAlert() {
        const harga = parseFloat(document.querySelector('input[name="harga"]:checked')?.value || 0);
        const stok = parseFloat(document.querySelector('input[name="stok"]:checked')?.value || 0);
        const minimal = parseFloat(document.querySelector('input[name="minimal_pembelian"]:checked')?.value || 0);
        const total = harga + stok + minimal;

        const alertDiv = document.getElementById("bobotAlert");
        const totalSpan = document.getElementById("currentTotal");

        totalSpan.textContent = total.toFixed(1);

        if (Math.abs(total - 1.0) > 0.0001) {
            alertDiv.style.display = "block";
        } else {
            alertDiv.style.display = "none";
        }
    }

    document.querySelectorAll('input[name="harga"], input[name="stok"], input[name="minimal_pembelian"]').forEach(input => {
        input.addEventListener("change", updateBobotAlert);
    });

    document.getElementById("formPreset").addEventListener("submit", function (e) {
        const harga = parseFloat(document.querySelector('input[name="harga"]:checked')?.value || 0);
        const stok = parseFloat(document.querySelector('input[name="stok"]:checked')?.value || 0);
        const minimal = parseFloat(document.querySelector('input[name="minimal_pembelian"]:checked')?.value || 0);
        const total = harga + stok + minimal;

        if (Math.abs(total - 1.0) > 0.0001) {
            e.preventDefault();
            updateBobotAlert();
        }
    });
</script>

<?php
require_once('template/footer.php');
?>