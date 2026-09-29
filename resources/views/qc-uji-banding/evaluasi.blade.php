@extends('layouts.app')
@section('title', 'Evaluasi - QC Uji Banding')

@section('content')
<div class="container-fluid px-4 pb-5">
    <div class="mb-4">
        <a href="{{ route('qc-uji-banding.show', $program->id) }}" class="btn btn-sm btn-outline-secondary mb-2"><i class="fas fa-arrow-left"></i> Kembali</a>
        <h2 class="fw-bold text-dark mb-0"><i class="fas fa-edit text-primary me-2"></i>Evaluasi Vendor Uji Banding</h2>
        <p class="text-muted">Masukkan nilai target/Z-Score dari laporan penyelenggara untuk parameter <span class="fw-bold text-dark">{{ $parameter->parameterUji->nama_parameter }}</span>.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold text-center">Data Pengujian (Lab Sucofindo)</h5>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                    <div class="display-3 fw-bold text-primary mb-2">{{ $parameter->nilai_akhir }}</div>
                    <div class="text-muted">Nilai Akhir Uji Banding</div>
                    <hr class="w-75">
                    <table class="table table-sm text-start w-75">
                        <tr><td class="text-muted">Analis</td><td class="fw-bold text-end">{{ $parameter->analis->nama ?? '-' }}</td></tr>
                        <tr><td class="text-muted">D1</td><td class="fw-bold text-end">{{ $parameter->nilai_d1 ?? '-' }}</td></tr>
                        <tr><td class="text-muted">D2</td><td class="fw-bold text-end">{{ $parameter->nilai_d2 ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-check-double me-2"></i>Input Keputusan Vendor</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('qc-uji-banding.update-evaluasi', [$program->id, $parameter->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Vendor (Reference Value) <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="number" step="any" name="target_vendor" class="form-control" value="{{ $parameter->target_vendor }}" placeholder="Misal: 6500">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Z-Score Vendor <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="number" step="any" name="z_score" class="form-control" value="{{ $parameter->z_score }}" placeholder="Misal: 0.85">
                            <small class="text-muted">Biasanya Z-Score < 2 (Inlier), 2 - 3 (Warning), > 3 (Outlier)</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Status Evaluasi Akhir <span class="text-danger">*</span></label>
                            <select name="status_evaluasi" class="form-select form-select-lg" required>
                                <option value="menunggu" {{ $parameter->status_evaluasi == 'menunggu' ? 'selected' : '' }}>-- Belum Dievaluasi --</option>
                                <option value="inlier" {{ $parameter->status_evaluasi == 'inlier' ? 'selected' : '' }}>✅ Inlier (Memenuhi Syarat)</option>
                                <option value="warning" {{ $parameter->status_evaluasi == 'warning' ? 'selected' : '' }}>⚠️ Warning (Peringatan)</option>
                                <option value="outlier" {{ $parameter->status_evaluasi == 'outlier' ? 'selected' : '' }}>❌ Outlier (Tidak Memenuhi Syarat)</option>
                            </select>
                            <div class="alert alert-danger mt-2 mb-0 d-none" id="alertOutlier">
                                <i class="fas fa-exclamation-triangle me-2"></i> Mengubah status menjadi <strong>Outlier</strong> akan mewajibkan pengisian Lembar Ketidaksesuaian (LKS) / Investigasi.
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan Evaluasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectStatus = document.querySelector('select[name="status_evaluasi"]');
    const alertOutlier = document.getElementById('alertOutlier');
    
    function checkStatus() {
        if (selectStatus.value === 'outlier') {
            alertOutlier.classList.remove('d-none');
        } else {
            alertOutlier.classList.add('d-none');
        }
    }
    
    selectStatus.addEventListener('change', checkStatus);
    checkStatus();
});
</script>
@endsection

