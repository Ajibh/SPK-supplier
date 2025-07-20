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
                        <label class="form-label">Harga: <span id="harga_val">0.00</span></label>
                        <input type="range" min="0.00" max="1.00" step="0.05" class="form-range" id="harga"
                            name="harga">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok: <span id="stok_val">0.00</span></label>
                        <input type="range" min="0.00" max="1.00" step="0.05" class="form-range" id="stok" name="stok">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minimal Pembelian: <span id="minimal_val">0.00</span></label>
                        <input type="range" min="0.00" max="1.00" step="0.05" class="form-range" id="minimal_pembelian"
                            name="minimal_pembelian">
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
                        <input type="range" min="0.00" max="1.00" step="0.05" class="form-range" id="edit_harga"
                            name="edit_harga">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok: <span id="edit_stok_val">0.0</span></label>
                        <input type="range" min="0.00" max="1.00" step="0.05" class="form-range" id="edit_stok"
                            name="edit_stok">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minimal Pembelian: <span id="edit_min_val">0.0</span></label>
                        <input type="range" min="0.00" max="1.00" step="0.05" class="form-range"
                            id="edit_minimal_pembelian" name="edit_minimal_pembelian">
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select name="edit_status" id="edit_status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div id="editBobotAlert" class="text-danger small mt-2" style="display: none;">
                        Total bobot saat ini <span id="editCurrentTotal">0</span>. Total harus tepat
                        <strong>1.0</strong>.
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" name="submit_edit" class="btn btn-sm btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tambahModalElements = {
            harga: document.getElementById('harga'),
            stok: document.getElementById('stok'),
            minimal: document.getElementById('minimal_pembelian'),
            hargaVal: document.getElementById('harga_val'),
            stokVal: document.getElementById('stok_val'),
            minimalVal: document.getElementById('minimal_val'),
            alertDiv: document.getElementById('bobotAlert'),
            totalSpan: document.getElementById('currentTotal'),
            submitButton: document.querySelector('button[name="submit_tambah"]') // ✅ Tambahan penting
        };

        const editModalElements = {
            harga: document.getElementById('edit_harga'),
            stok: document.getElementById('edit_stok'),
            minimal: document.getElementById('edit_minimal_pembelian'),
            hargaVal: document.getElementById('edit_harga_val'),
            stokVal: document.getElementById('edit_stok_val'),
            minimalVal: document.getElementById('edit_min_val'),
            alertDiv: document.getElementById('editBobotAlert'),
            totalSpan: document.getElementById('editCurrentTotal'),
            submitButton: document.querySelector('button[name="submit_edit"]')
        };

        const updateSliderValues = (elements) => {
            const { harga, stok, minimal, hargaVal, stokVal, minimalVal, alertDiv, totalSpan, submitButton } = elements;
            if (harga && stok && minimal) {
                const total = parseFloat(harga.value || 0) + parseFloat(stok.value || 0) + parseFloat(minimal.value || 0);
                if (hargaVal) hargaVal.textContent = parseFloat(harga.value || 0).toFixed(2);
                if (stokVal) stokVal.textContent = parseFloat(stok.value || 0).toFixed(2);
                if (minimalVal) minimalVal.textContent = parseFloat(minimal.value || 0).toFixed(2);
                if (totalSpan) totalSpan.textContent = total.toFixed(2);

                // Tampilkan alert jika total tidak tepat 1.00
                if (alertDiv) {
                    alertDiv.style.display = (Math.abs(total - 1.00) > 0.001) ? "block" : "none";
                }

                // Tombol submit nonaktif jika total ≠ 1.00
                if (submitButton) {
                    submitButton.disabled = (Math.abs(total - 1.00) > 0.001);
                }
            }
        };

        const setModalValues = (button, elements) => {
            const { harga, stok, minimal, hargaVal, stokVal, minimalVal } = elements;
            if (button) {
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const hargaValue = button.getAttribute('data-harga');
                const stokValue = button.getAttribute('data-stok');
                const minimalValue = button.getAttribute('data-minimal');
                const status = button.getAttribute('data-status');

                document.getElementById('edit_id_preset').value = id;
                document.getElementById('edit_nama_preset').value = nama;
                document.getElementById('edit_status').value = status;

                if (harga) harga.value = hargaValue;
                if (stok) stok.value = stokValue;
                if (minimal) minimal.value = minimalValue;

                if (hargaVal) hargaVal.textContent = parseFloat(hargaValue).toFixed(2);
                if (stokVal) stokVal.textContent = parseFloat(stokValue).toFixed(2);
                if (minimalVal) minimalVal.textContent = parseFloat(minimalValue).toFixed(2);

                updateSliderValues(elements);
            }
        };

        document.getElementById('editModal')?.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            setModalValues(button, editModalElements);
        });

        // Reset slider dan label ke 0.00 saat modal tambah preset dibuka
        document.getElementById('tambahModal')?.addEventListener('show.bs.modal', function () {
            if (tambahModalElements.harga) tambahModalElements.harga.value = 0.00;
            if (tambahModalElements.stok) tambahModalElements.stok.value = 0.00;
            if (tambahModalElements.minimal) tambahModalElements.minimal.value = 0.00;
            if (tambahModalElements.hargaVal) tambahModalElements.hargaVal.textContent = "0.00";
            if (tambahModalElements.stokVal) tambahModalElements.stokVal.textContent = "0.00";
            if (tambahModalElements.minimalVal) tambahModalElements.minimalVal.textContent = "0.00";
            if (tambahModalElements.totalSpan) tambahModalElements.totalSpan.textContent = "0.00";
            if (tambahModalElements.alertDiv) tambahModalElements.alertDiv.style.display = "block";
            if (tambahModalElements.submitButton) tambahModalElements.submitButton.disabled = true; // ✅ Tambahan penting
        });

        [tambahModalElements.harga, tambahModalElements.stok, tambahModalElements.minimal].forEach(input => {
            if (input) input.addEventListener('input', () => updateSliderValues(tambahModalElements));
        });

        [editModalElements.harga, editModalElements.stok, editModalElements.minimal].forEach(input => {
            if (input) input.addEventListener('input', () => updateSliderValues(editModalElements));
        });
    });
</script>