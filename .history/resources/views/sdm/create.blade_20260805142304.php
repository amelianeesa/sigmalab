<form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-body p-4 bg-white" style="max-height: 70vh; overflow-y: auto;">
        
        <!-- Informasi Personil -->
        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Nama Lengkap & Gelar</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="mis. Budi Santoso" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Posisi / Lab</label>
            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="mis. Analis Lab Kimia" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Upload CV / Dokumen</label>
            <input type="file" name="file_cv" class="form-control" accept="image/*,application/pdf">
            <div class="form-text text-muted" style="font-size: 11px;">Format yang didukung: JPG, PNG, atau PDF (Maks. 2MB).</div>
        </div>

        <hr class="my-4 text-muted opacity-25">

        <!-- Informasi Sertifikasi Awal -->
        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Nama Sertifikasi / Pelatihan Awal</label>
            <input type="text" name="nama_sertifikasi" class="form-control" value="{{ old('nama_sertifikasi') }}" placeholder="mis. Pelatihan K3 Laboratorium">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark small">Tgl Terbit</label>
                <input type="date" name="tgl_terbit" class="form-control" value="{{ old('tgl_terbit', date('Y-m-d')) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark small">Tgl Berakhir</label>
                <input type="date" name="tgl_berakhir" class="form-control" value="{{ old('tgl_berakhir', date('Y-m-d', strtotime('+2 years'))) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark small">Reminder Sebelum Jatuh Tempo</label>
            <select name="reminder" class="form-select">
                <option value="H-30" {{ old('reminder') == 'H-30' ? 'selected' : '' }}>H-30</option>
                <option value="H-60" {{ old('reminder') == 'H-60' ? 'selected' : '' }}>H-60</option>
                <option value="H-90" {{ old('reminder') == 'H-90' ? 'selected' : '' }}>H-90</option>
            </select>
        </div>

    </div>

    <div class="modal-footer bg-light px-4 py-3 border-0">
        <button type="button" class="btn btn-light border px-4 text-secondary fw-semibold" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-dark px-4 fw-semibold shadow-sm">Simpan</button>
    </div>
</form>