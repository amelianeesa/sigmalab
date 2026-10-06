@extends('layouts.app')
@section('title', 'Ringkasan Unjuk Kerja - ' . $program->nama_program)

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
    }

    .dashboard-container nav[aria-label="breadcrumb"],
    .dashboard-container > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .dashboard-container .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item,
    .dashboard-container .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .dashboard-container .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .dashboard-container .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .card-body {
        padding: 10px !important;
    }

    .table th, .table td {
        padding: 8px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }

    .table thead th {
        font-size: 0.75rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
        text-align: center !important;
    }

    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }

    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .btn-corporate-blue:hover,
    .btn-corporate-blue:focus,
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }

    .btn-outline-corporate {
        color: #1b3152 !important;
        border-color: #1b3152 !important;
    }

    .btn-outline-corporate:hover,
    .btn-outline-corporate:focus {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .qc-table {
        min-width: 780px;
    }

    .qc-history-table {
        min-width: 560px;
    }

    .history-wrap {
        max-width: 720px;
        margin: 0 auto;
    }

    .chart-box {
        position: relative;
        width: 100%;
        max-width: 600px;
        height: 260px;
        margin: 0 auto;
    }

    .detail-heading {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1b3152;
        margin-bottom: 0;
    }

    .detail-muted {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 0 10px !important;
        }

        .dashboard-container .breadcrumb,
        .dashboard-container .breadcrumb .breadcrumb-item,
        .dashboard-container .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            width: 100%;
        }

        .chart-box {
            height: 220px;
        }
    }
</style>

<div class="container-fluid dashboard-container" style="font-size: 0.82rem;">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.show', $program->id) }}">{{ $program->nama_program }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ringkasan Unjuk Kerja</li>
    </x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
                <i class="fas fa-chart-bar me-2" style="color: #1b3152;"></i>Ringkasan Unjuk Kerja
            </h5>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ $program->nama_program }} — {{ $program->kode_sampel }}</p>
        </div>

        <div class="header-actions d-grid d-md-flex align-items-center gap-2">
            <a href="{{ route('qc-uji-banding.evaluasi.form', $program->id) }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                <i class="fas fa-edit me-1"></i> Edit Hasil Evaluasi
            </a>
        </div>
    </div>

    @php
        $paramMap = [];
        $sulfurAliases = ['TOTAL SULFUR (%AD/DB)', 'TOTAL SULFUR', 'TS'];

        foreach ($program->parameters as $p) {
            if ($p->target_vendor === null) {
                continue;
            }
            $rawName = strtoupper($p->parameterUji->nama_parameter ?? '');

            if ($rawName === 'TM') {
                $paramMap[] = ['param' => $p, 'name' => 'Total Moisture', 'unit' => '%, ar', 'lab_value' => floatval($p->nilai_akhir)];
            } elseif ($rawName === 'IM') {
                $paramMap[] = ['param' => $p, 'name' => 'Inherent Moisture', 'unit' => '%, adb', 'lab_value' => floatval($p->nilai_akhir)];
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

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center mb-0 qc-table" style="font-size: 0.78rem;">
                    <thead class="align-middle">
                        <tr>
                            <th class="text-start">Parameter</th>
                            <th>Units</th>
                            <th>Lab Value</th>
                            <th>Alg. Mean</th>
                            <th>SDPA</th>
                            <th>Z-score</th>
                            <th style="width: 110px;">Comment</th>
                            <th>Method</th>
                            <th style="width: 90px;">Aksi</th>
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
                                        <span class="badge bg-success" style="font-size: 0.7rem;">Acceptable</span>
                                    @elseif($p->status_evaluasi === 'warning')
                                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Warning</span>
                                    @elseif($p->status_evaluasi === 'outlier')
                                        <span class="badge bg-danger" style="font-size: 0.7rem;">Outlier</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">Menunggu Vendor</span>
                                    @endif
                                </td>
                                <td>{{ $p->metode_uji ?? '-' }}</td>
                                <td class="text-nowrap">
                                    @if($p->status_evaluasi === 'outlier')
                                        @if($p->status_investigasi === 'menunggu_investigasi')
                                            <a href="{{ route('qc-uji-banding.investigasi', [$program->id, $p->id]) }}" class="btn btn-danger btn-sm py-1 px-2 shadow-sm" style="font-size: 0.75rem;">
                                                <i class="fas fa-edit"></i> Isi LKS
                                            </a>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">LKS Selesai</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach

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

    @foreach($paramMap as $idx => $item)
        @php $p = $item['param']; @endphp
        <div class="card mb-4 border-0 shadow-sm" id="detail-{{ $idx }}">
            <div class="card-body">
                <div class="text-center mb-3">
                    <h5 class="detail-heading">Sucofindo Proficiency Test - Coal</h5>
                    <p class="detail-muted">Historical Performance</p>
                    <h5 class="detail-heading">{{ $item['name'] }}</h5>
                    <p class="mb-0" style="font-size: 0.78rem;">Laboratory : <strong>{{ $program->nama_lab ?? 'PT SUCOFINDO Cabang Cilacap' }}</strong></p>
                </div>

                <div class="history-wrap mb-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle text-center mb-0 qc-history-table" style="font-size: 0.78rem;">
                            <thead class="align-middle">
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
                </div>

                <div class="text-center mb-2">
                    <h6 class="detail-heading">{{ $item['name'] }}</h6>
                </div>
                <div class="chart-box">
                    <canvas id="chartParam{{ $idx }}"></canvas>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="modal fade" id="modalPilihEvaluasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #1b3152;">
                <h5 class="modal-title" style="font-size: 1rem;"><i class="fas fa-list-check me-2"></i>Pilih Parameter Uji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('qc-uji-banding.evaluasi.form', $program->id) }}" method="GET">
                <div class="modal-body">
                    <p class="text-muted mb-3" style="font-size: 0.78rem;">Centang parameter apa saja yang ingin diedit atau dievaluasi ulang:</p>
                    <div class="row g-2">
                        @foreach($program->parameters as $param)
                        <div class="col-12 col-sm-6">
                            <label class="d-flex gap-2 align-items-center border rounded p-2 h-100" style="cursor: pointer;">
                                <input class="form-check-input flex-shrink-0 mt-0" type="checkbox" name="p[]" value="{{ $param->id }}" {{ $param->z_score !== null ? 'checked' : '' }}>
                                <strong class="text-dark" style="font-size: 0.8rem;">{{ $param->parameterUji->nama_parameter }}</strong>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-corporate-blue btn-sm shadow-sm">Lanjutkan Pengisian <i class="fas fa-arrow-right ms-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isMobile = window.innerWidth < 768;

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
                        backgroundColor: '#1b3152',
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        pointRadius: isMobile ? 5 : 7,
                        pointHoverRadius: isMobile ? 7 : 9,
                        pointStyle: 'circle',
                        showLine: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: { boxWidth: 12, font: { size: isMobile ? 10 : 11 } }
                        },
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
                                font: { size: isMobile ? 10 : 11 },
                                callback: function(val) {
                                    return periods[val] || '';
                                }
                            },
                            title: {
                                display: true,
                                text: 'RESULT / PERIOD',
                                font: { weight: 'bold', size: isMobile ? 10 : 11 }
                            }
                        },
                        y: {
                            suggestedMin: -3,
                            suggestedMax: 3,
                            ticks: { stepSize: 1, font: { size: isMobile ? 10 : 11 } },
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