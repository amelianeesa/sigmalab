<!-- Global Modal Override Evaluasi -->
<div class="modal fade text-start" id="modalOverrideGlobal" tabindex="-1" aria-labelledby="modalOverrideLabelGlobal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formOverrideGlobal" method="POST" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header py-2 px-3 text-white" style="background-color: #1b3152;">
                    <h5 class="modal-title" id="modalOverrideLabelGlobal" style="font-size: 0.9rem;">Override Evaluasi Control Chart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 0.82rem;">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.75rem;">Status Evaluasi</label>
                        <select name="override_status" id="overrideStatusSelect" class="form-select form-select-sm" style="font-size: 0.8rem;" required>
                            <option value="Terima">Terima (Abaikan Pelanggaran)</option>
                            <option value="Tolak">Tolak (Terdapat Pelanggaran)</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold" style="font-size: 0.75rem;">Keterangan / Alasan Override</label>
                        <textarea name="keterangan_override" id="overrideKeterangan" class="form-control form-control-sm" style="font-size: 0.8rem;" rows="3" required placeholder="Contoh: Melanggar aturan 10x tetapi aturan 1(2s) tidak dilanggar, jadi bisa diabaikan"></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-size: 0.78rem;">Batal</button>
                    <button type="submit" class="btn btn-sm fw-semibold text-white" style="font-size: 0.78rem; background-color: #1b3152; border-color: #1b3152;">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalOverrideGlobal = document.getElementById('modalOverrideGlobal');
        if (modalOverrideGlobal) {
            modalOverrideGlobal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const status = button.getAttribute('data-status');
                const alasan = button.getAttribute('data-alasan');
                
                const form = document.getElementById('formOverrideGlobal');
                form.action = "{{ url('hasil-uji/override-evaluasi') }}/" + id;
                
                document.getElementById('overrideStatusSelect').value = status;
                document.getElementById('overrideKeterangan').value = alasan && alasan !== '-' ? alasan : '';
            });
        }
    });
</script>