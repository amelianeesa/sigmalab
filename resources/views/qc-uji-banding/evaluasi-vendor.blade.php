@extends('layouts.app')
@section('title', 'Evaluasi Vendor - ' . $program->nama_program)

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.show', $program->id) }}" class="text-decoration-none">{{ $program->nama_program }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Hasil Evaluasi Vendor</li>
    </x-qc-breadcrumb>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-chart-bar text-primary me-2"></i>Input Hasil Evaluasi Vendor</h2>
        <p class="text-muted mb-0">Masukkan Assigned Value (Alg. Mean) dan SDPA sesuai laporan uji profisiensi dari vendor. Z-score dan status dihitung otomatis.</p>
    </div>

    <form action="{{ route('qc-uji-banding.evaluasi.store', $program->id) }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle text-center mb-0" id="evalTable">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">Parameter</th>
                                <th>Lab Value</th>
                                <th style="width:140px;">Assigned Value<br><small class="text-muted">(Alg. Mean)</small></th>
                                <th style="width:120px;">SDPA</th>
                                <th style="width:100px;">Z-score</th>
                                <th style="width:130px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($program->parameters as $p)
                                <tr data-pid="{{ $p->id }}" data-labvalue="{{ $p->nilai_akhir }}">
                                    <td class="text-start fw-bold">{{ strtoupper($p->parameterUji->nama_parameter ?? '-') }}</td>
                                    <td class="fw-bold">{{ number_format($p->nilai_akhir, 4) }}</td>
                                    <td>
                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-assigned"
                                               name="params[{{ $p->id }}][target_vendor]" value="{{ $p->target_vendor }}">
                                    </td>
                                    <td>
                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-sdpa"
                                               name="params[{{ $p->id }}][sdpa]" value="{{ $p->sdpa }}">
                                    </td>
                                    <td class="fw-bold out-zscore">{{ $p->z_score ?? '-' }}</td>
                                    <td class="out-status">
                                        @if($p->status_evaluasi === 'inlier')
                                            <span class="badge bg-success">Acceptable</span>
                                        @elseif($p->status_evaluasi === 'warning')
                                            <span class="badge bg-warning text-dark">Warning</span>
                                        @elseif($p->status_evaluasi === 'outlier')
                                            <span class="badge bg-danger">Outlier</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan & Lihat Ringkasan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function num(v) { const n = parseFloat(v); return isNaN(n) ? null : n; }

    document.querySelectorAll('#evalTable tbody tr').forEach(row => {
        const labValue = num(row.dataset.labvalue);
        const inAssigned = row.querySelector('.in-assigned');
        const inSdpa = row.querySelector('.in-sdpa');
        const outZ = row.querySelector('.out-zscore');
        const outStatus = row.querySelector('.out-status');

        function recalc() {
            const assigned = num(inAssigned.value);
            const sdpa = num(inSdpa.value);
            if (assigned === null || sdpa === null || sdpa === 0 || labValue === null) {
                return;
            }
            const z = (labValue - assigned) / sdpa;
            const absZ = Math.abs(z);
            outZ.textContent = z.toFixed(2);

            let badge = '';
            if (absZ <= 2) badge = '<span class="badge bg-success">Acceptable</span>';
            else if (absZ < 3) badge = '<span class="badge bg-warning text-dark">Warning</span>';
            else badge = '<span class="badge bg-danger">Outlier</span>';
            outStatus.innerHTML = badge;
        }

        inAssigned.addEventListener('input', recalc);
        inSdpa.addEventListener('input', recalc);
    });
});
</script>
@endsection