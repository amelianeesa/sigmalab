@extends('layouts.app')
@section('title', 'Ringkasan Unjuk Kerja - ' . $program->nama_program)

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.show', $program->id) }}" class="text-decoration-none">{{ $program->nama_program }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ringkasan Unjuk Kerja</li>
    </x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="fas fa-chart-bar text-primary me-2"></i>Ringkasan Unjuk Kerja</h2>
            <p class="text-muted mb-0">{{ $program->nama_program }} — {{ $program->kode_sampel }}</p>
        </div>
        <a href="{{ route('qc-uji-banding.evaluasi.form', $program->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-edit me-1"></i> Edit Hasil Evaluasi
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Parameter</th>
                            <th>Lab Value</th>
                            <th>Alg. Mean</th>
                            <th>SDPA</th>
                            <th>Z-score</th>
                            <th>Comment</th>
                            <th>Method</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($program->parameters as $p)
                            <tr>
                                <td class="text-start fw-bold">{{ strtoupper($p->parameterUji->nama_parameter ?? '-') }}</td>
                                <td>{{ number_format($p->nilai_akhir, 4) }}</td>
                                <td>{{ $p->target_vendor !== null ? number_format($p->target_vendor, 4) : '-' }}</td>
                                <td>{{ $p->sdpa !== null ? number_format($p->sdpa, 4) : '-' }}</td>
                                <td class="fw-bold">{{ $p->z_score ?? '-' }}</td>
                                <td>
                                    @if($p->status_evaluasi === 'inlier')
                                        <span class="badge bg-success">Acceptable</span>
                                    @elseif($p->status_evaluasi === 'warning')
                                        <span class="badge bg-warning text-dark">Warning</span>
                                    @elseif($p->status_evaluasi === 'outlier')
                                        <span class="badge bg-danger">Outlier</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu Vendor</span>
                                    @endif
                                </td>
                                <td>{{ $p->metode_uji ?? '-' }}</td>
                                <td>
                                    @if($p->status_evaluasi === 'outlier')
                                        @if($p->status_investigasi === 'menunggu_investigasi')
                                            <a href="{{ route('qc-uji-banding.investigasi', [$program->id, $p->id]) }}" class="btn btn-sm btn-danger">
                                                <i class="fas fa-edit"></i> Isi LKS
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">LKS Selesai</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Z Score per Parameter</h6>
        </div>
        <div class="card-body">
            <canvas id="zScoreChart" height="90"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels = @json($program->parameters->map(fn($p) => strtoupper($p->parameterUji->nama_parameter ?? '-'))->values());
    const zScores = @json($program->parameters->map(fn($p) => $p->z_score)->values());
    const colors = zScores.map(z => {
        if (z === null) return 'rgba(200,200,200,0.5)';
        const az = Math.abs(z);
        if (az <= 2) return 'rgba(25,135,84,0.7)';
        if (az < 3) return 'rgba(255,193,7,0.7)';
        return 'rgba(220,53,69,0.7)';
    });

    new Chart(document.getElementById('zScoreChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{ label: 'Z Score', data: zScores, backgroundColor: colors }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    suggestedMin: -3.5,
                    suggestedMax: 3.5,
                    grid: {
                        color: (ctx) => (ctx.tick.value === -2 || ctx.tick.value === 2) ? 'rgba(255,193,7,0.5)' :
                                        (ctx.tick.value === -3 || ctx.tick.value === 3) ? 'rgba(220,53,69,0.5)' : 'rgba(0,0,0,0.05)'
                    }
                }
            }
        }
    });
});
</script>
@endsection