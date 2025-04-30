<?php
require_once('includes/init.php');
cek_login($role = array(1));
?>

<!-- Modal Tambah Preset -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Preset Bobot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="preset-bobot-function.php" id="formPreset">
                    <div class="mb-3">
                        <label for="nama_preset" class="form-label">Nama Preset</label>
                        <input type="text" class="form-control" id="nama_preset" name="nama_preset" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga: <span id="harga_val">0.0</span></label>
                        <input type="range" min="0" max="0.8" step="0.1" class="form-range" id="harga" name="harga">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok: <span id="stok_val">0.0</span></label>
                        <input type="range" min="0" max="0.8" step="0.1" class="form-range" id="stok" name="stok">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minimal Pembelian: <span id="minimal_val">0.0</span></label>
                        <input type="range" min="0" max="0.8" step="0.1" class="form-range" id="minimal_pembelian" name="minimal_pembelian">
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
                        <button type="submit" name="submit_tambah" class="btn btn-sm btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Tambah Preset -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="taambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Preset Bobot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="preset-bobot-function.php" id="formPreset">
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
                        <button type="submit" name="submit_tambah" class="btn btn-sm btn-success">Simpan</button>
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
                <form method="POST" action="preset-bobot-function.php" id="formEditPreset">
                    <input type="hidden" id="edit_id_preset" name="edit_id_preset">

                    <div class="mb-3">
                        <label for="edit_nama_preset" class="form-label">Nama Preset</label>
                        <input type="text" class="form-control" id="edit_nama_preset" name="edit_nama_preset" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga: <span id="edit_harga_val">0.0</span></label>
                        <input type="range" min="0" max="0.8" step="0.1" class="form-range" id="edit_harga"
                            name="edit_harga">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok: <span id="edit_stok_val">0.0</span></label>
                        <input type="range" min="0" max="0.8" step="0.1" class="form-range" id="edit_stok"
                            name="edit_stok">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minimal Pembelian: <span id="edit_min_val">0.0</span></label>
                        <input type="range" min="0" max="0.8" step="0.1" class="form-range" id="edit_minimal_pembelian"
                            name="edit_minimal_pembelian">
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select name="edit_status" id="edit_status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div id="bobotAlert" class="text-danger small mt-2" style="display: none;">
                        Total bobot saat ini <span id="currentTotal">0</span>. Total harus tepat <strong>1.0</strong>.
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" name="submit_edit" class="btn btn-sm btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>