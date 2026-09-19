{{-- Tab AFT Correction: Kalibrasi Alat + Tabel Interpolasi --}}
<div class="p-4">
    {{-- Bagian A: Form Input Poin Kalibrasi --}}
    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
        <i class="fas fa-thermometer-half me-2"></i>Poin Kalibrasi Utama
    </h6>
    <div class="alert alert-warning border-start border-4 border-warning rounded-0 py-2">
        <i class="fas fa-info-circle me-1"></i> Masukkan titik-titik kalibrasi alat AFT. Semua kolom diisi manual. Tabel interpolasi (kelipatan 5°C) akan di-generate otomatis.
    </div>

    <div id="aft-viewing-badge" class="alert alert-warning border-start border-4 border-warning rounded-0 py-2 d-none">
        <i class="fas fa-history me-1"></i>
        Kamu sedang melihat riwayat kalibrasi <strong id="aft-viewing-name">-</strong> — <u>ini bukan kalibrasi yang sedang aktif</u>.
        Jika ditekan <strong>"Simpan Kalibrasi"</strong> saat ini, data yang tampil akan dijadikan kalibrasi aktif yang baru.
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-bordered table-sm align-middle text-center" id="aft-calib-table">
            <thead class="table-light">
                <tr>
                    <th style="width: 30%;">Eq. Sett (°C)</th>
                    <th style="width: 35%;">Std. Read</th>
                    <th style="width: 35%;">Correction</th>
                </tr>
            </thead>
            <tbody id="aft-calib-body">
                <tr class="aft-calib-row">
                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm text-center aft-eq" placeholder="e.g. 1000"></td>
                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm text-center aft-std" placeholder="e.g. 993.6949"></td>
                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm text-center aft-corr" placeholder="e.g. -6.3"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-start">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="aft-add-row">
                            <i class="fas fa-plus me-1"></i> Tambah Baris
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" id="aft-remove-row">
                            <i class="fas fa-minus me-1"></i> Hapus Baris Terakhir
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success mt-1 ms-3" id="aft-generate">
                            <i class="fas fa-calculator me-1"></i> Generate Interpolasi
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Riwayat Kalibrasi --}}
    <div class="row g-3 mb-4 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-bold">Riwayat Kalibrasi</label>
            <select class="form-select form-select-sm" id="aft-history-select">
                <option value="">-- Kalibrasi Aktif (Terbaru) --</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-sm btn-success" id="aft-save-calib">
                <i class="fas fa-save me-1"></i> Simpan Kalibrasi
            </button>
        </div>
    </div>

    {{-- Bagian B: Tabel Interpolasi (Auto-generate) --}}
    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
        <i class="fas fa-list-ol me-2"></i>Tabel Interpolasi (Kelipatan 5°C)
    </h6>
    <div id="aft-interpolation-container">
        <div class="text-muted text-center py-4">
            <i class="fas fa-arrow-up me-1"></i> Isi poin kalibrasi di atas, lalu klik <strong>Generate Interpolasi</strong> untuk menampilkan tabel.
        </div>
    </div>
</div>
