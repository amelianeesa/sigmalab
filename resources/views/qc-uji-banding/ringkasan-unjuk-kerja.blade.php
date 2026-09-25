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

    @php
        // Mapping nama parameter agar konsisten dengan halaman evaluasi vendor
        $paramMap = [];
        $allowedParams = ['IM', 'TM', 'ASH', 'VM', 'C', 'H', 'N', 'CV', 'GCV'];
        $sulfurAliases = ['TOTAL SULFUR (%AD/DB)', 'TOTAL SULFUR', 'TS'];

        // Lookup TM untuk IM
        $tmParam = $program->parameters->first(function($x) {
            return strtoupper($x->parameterUji->nama_parameter ?? '') === 'TM';
        });

        foreach ($program->parameters as $p) {
            $rawName = strtoupper($p->parameterUji->nama_parameter ?? '');

            if ($rawName === 'IM' || $rawName === 'TM') {
                if ($rawName === 'TM') continue; // TM tidak ditampilkan sendiri
                
                $val = floatval($p->nilai_akhir);
                if ($val == 0) {
                    $val = floatval($tmParam->nilai_akhir ?? 0);
                }
                
                $paramMap[] = [
                    'param' => $p,
                    'name' => 'Total Moisture',
                    'unit' => '%, ar',
                    'lab_value' => $val,
                ];
            } elseif ($rawName === 'ASH') {
                $paramMap[] = ['param' => $p, 'name' => 'Ash Content', 'unit' => '%, db', 'lab_value' => floatval($p->nilai_akhir)];
            } elseif ($rawName === 'VM') {
                $paramMap[] = ['param' => $p, 'name' => 'Volatile Matter', 'unit' => '%, db', 'lab_value' => floatval($p->nilai_akhir)];
            } elseif (strpos($rawName, 'SULFUR') !== false || in_array($rawName, $sulfurAliases)) {
                $paramMap[] = ['param' => $p, 'name' => 'Total Sulfur', 'unit' => '%, db', 'lab_value' => floatval($p->nilai_akhir)];
            } elseif ($rawName === 'CV' || $rawName === 'GCV') {
                $paramMap[] = ['param' => $p, 'name' => 'GCV', 'unit' => 'kcal/kg, db', 'lab_value' => floatval($p->nilai_akhir)];
            } elseif ($rawName === 'C') {
                $paramMap[] = ['param' => $p, 'name' => 'Carbon', 'unit' => '%, db', 'lab_value' => floatval($p->nilai_akhir)];
            } elseif ($rawName === 'H') {
                $paramMap[] = ['param' => $p, 'name' => 'Hydrogen', 'unit' => '%, db', 'lab_value' => floatval($p->nilai_akhir)];
            } elseif ($rawName === 'N') {
                $paramMap[] = ['param' => $p, 'name' => 'Nitrogen', 'unit' => '%, db', 'lab_value' => floatval($p->nilai_akhir)];
            }
        }
    @endphp

    {{-- ============ TABEL RINGKASAN ============ --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Parameter</th>
                            <th>Units</th>
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
                        @foreach($paramMap as $item)
                            @php $p = $item['param']; @endphp
                            <tr>
                                <td class="text-start fw-bold">{{ $item['name'] }}</td>
                                <td>{{ $item['unit'] }}</td>
                                <td>{{ $item['lab_value'] != 0 ? number_format($item['lab_value'], 4) : '-' }}</td>
                                <td>{{ $p->target_vendor !== null ? number_format($p->target_vendor, 4) : '-' }}</td>
                                <td>{{ $p->sdpa !== null ? number_format($p->sdpa, 4) : '-' }}</td>
                                <td class="fw-bold">{{ $p->z_score !== null ? number_format($p->z_score, 2) : '-' }}</td>
                                <td>
                                    @if($p->status_evaluasi === 'inlier')
                                        <span class="badge bg-success">Acceptable</span>
                                    @elseif($p->status_evaluasi === 'warning')
                                        <span class="badge bg-warning text-dark">Warning</span>
                                    @elseif($p->status_evaluasi === 'outlier')
                                        <span class="badge bg-danger">Outlier</span>
                                    @else
                                        <span class="badge bg-secondary">Menunggu Vendor</span>
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
                        {{-- Baris Relative Density (hardcoded) --}}
                        <tr>
                            <td class="text-start fw-bold">Relative Density</td>
                            <td>db</td>
                            <td>NA</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============ DETAIL PER PARAMETER ============ --}}
    @foreach($paramMap as $idx => $item)
        @php $p = $item['param']; @endphp
        <div class="card border-0 shadow-sm mb-4" id="detail-{{ $idx }}">
            <div class="card-body">
                {{-- Header --}}
                <div class="text-center mb-3">
                    <h5 class="fw-bold mb-0">Sucofindo Proficiency Test - Coal</h5>
                    <p class="text-muted mb-1">Historical Performance</p>
                    <h5 class="fw-bold">{{ $item['name'] }}</h5>
                    <p class="mb-0">Laboratory : <strong>{{ $program->nama_lab ?? 'PT SUCOFINDO Cabang Cilacap' }}</strong></p>
                </div>

                {{-- Tabel Historical --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm align-middle text-center" style="max-width: 700px; margin: 0 auto;">
                        <thead class="table-light">
                            <tr>
                                <th>Period</th>
                                <th>Year</th>
                                <th>Lab Value</th>
                                <th>Alg. Mean</th>
                                <th>SDPA</th>
                                <th>Z score</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($period = 1; $period <= 3; $period++)
                                <tr>
                                    <td>{{ str_pad($period, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ date('Y') }}</td>
                                    @if($period === 1)
                                        <td>{{ $item['lab_value'] != 0 ? number_format($item['lab_value'], 2, ',', '.') : '' }}</td>
                                        <td>{{ $p->target_vendor !== null ? number_format($p->target_vendor, 2, ',', '.') : '' }}</td>
                                        <td>{{ $p->sdpa !== null ? number_format($p->sdpa, 2, ',', '.') : '' }}</td>
                                        <td>{{ $p->z_score !== null ? number_format($p->z_score, 2, ',', '.') : '' }}</td>
                                        <td>
                                            @if($p->status_evaluasi === 'inlier') acceptable
                                            @elseif($p->status_evaluasi === 'warning') warning
                                            @elseif($p->status_evaluasi === 'outlier') outlier
                                            @endif
                                        </td>
                                    @else
                                        <td></td><td></td><td></td><td></td><td></td>
                                    @endif
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                {{-- Chart Z-Score per Period --}}
                <div class="text-center mb-2">
                    <h6 class="fw-bold">{{ $item['name'] }}</h6>
                </div>
                <div style="max-width: 600px; margin: 0 auto;">
                    <canvas id="chartParam{{ $idx }}" height="200"></canvas>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($paramMap as $idx => $item)
        @php $p = $item['param']; @endphp
        (function() {
            const ctx = document.getElementById('chartParam{{ $idx }}');
            if (!ctx) return;

            const zScore = {!! json_encode($p->z_score) !!};
            const periods = ['01', '02', '03'];
            const data = [zScore, null, null];

            new Chart(ctx.getContext('2d'), {
                type: 'scatter',
                data: {
                    labels: periods,
                    datasets: [{
                        label: 'Z Score',
                        data: data.map((v, i) => v !== null ? { x: i, y: v } : null).filter(v => v !== null),
                        backgroundColor: 'rgba(13, 110, 253, 1)',
                        pointRadius: 6,
                        pointStyle: 'circle',
                        showLine: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Z Score: ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            min: -0.5,
                            max: 2.5,
                            ticks: {
                                stepSize: 1,
                                callback: function(val) {
                                    return periods[val] || '';
                                }
                            },
                            title: { display: true, text: 'RESULT / PERIOD', font: { weight: 'bold' } }
                        },
                        y: {
                            suggestedMin: -3,
                            suggestedMax: 3,
                            ticks: { stepSize: 1 },
                            grid: {
                                color: function(ctx) {
                                    if (ctx.tick.value === 2 || ctx.tick.value === -2) return 'rgba(255,193,7,0.6)';
                                    if (ctx.tick.value === 3 || ctx.tick.value === -3) return 'rgba(220,53,69,0.6)';
                                    return 'rgba(0,0,0,0.07)';
                                }
                            }
                        }
                    }
                }
            });
        })();
    @endforeach
});
</script>
@endsection