<!-- Global Modal Override Evaluasi -->
<div class="modal fade text-start" id="modalOverrideGlobal" tabindex="-1" aria-labelledby="modalOverrideLabelGlobal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formOverrideGlobal" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalOverrideLabelGlobal">Override Evaluasi Control Chart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Status Evaluasi</label>
                        <select name="override_status" id="overrideStatusSelect" class="form-select" required>
                            <option value="Terima">Terima (Abaikan Pelanggaran)</option>
                            <option value="Tolak">Tolak (Terdapat Pelanggaran)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Keterangan / Alasan Override</label>
                        <textarea name="keterangan_override" id="overrideKeterangan" class="form-control" rows="3" required placeholder="Contoh: Melanggar aturan 10x tetapi aturan 1(2s) tidak dilanggar, jadi bisa diabaikan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
