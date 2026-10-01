@if(($jenisGrafik ?? 'in_house') === 'crm')
<!-- Table Card CRM -->
<div class="col-xl-9 col-md-6 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header py-2" style="background-color: #1b3152; color: #ffffff; font-weight: 600; font-size: 0.85rem;">
            Tabel Data Evaluasi Sertifikat CRM
        </div>
        <div class="card-body p-0">
            <div class="table-responsive lt-scroll">
                <table class="table table-bordered table-striped table-hover mb-0 text-center lt-table lt-stack">
                    <thead class="lt-head sticky-top">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pengujian Ke-</th>
                            <th title="Nilai True Value Sertifikat CRM">True Value</th>
                            <th title="Uncertainty (U)">Uncertainty (U)</th>
                            <th title="Batas Bawah Penerimaan">Batas Bawah</th>
                            <th title="Batas Atas Penerimaan">Batas Atas</th>
                            <th>Hasil Analisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($stats['evaluations']))
                            @foreach($stats['evaluations'] as $index => $eval)
                            <tr>
                                <td data-label="Tanggal">{{ $hasilList[$index]->created_at->format('d/m/Y') }}</td>
                                <td data-label="Pengujian Ke-">{{ $index + 1 }}</td>
                                <td data-label="True Value" class="text-primary fw-bold">{{ number_format($eval['cert_value'], 4, ',', '.') }}</td>
                                <td data-label="Uncertainty (U)" class="text-secondary">±{{ number_format($eval['cert_u'], 4, ',', '.') }}</td>
                                <td data-label="Batas Bawah" class="text-danger">{{ number_format($eval['batas_bawah'], 4, ',', '.') }}</td>
                                <td data-label="Batas Atas" class="text-danger">{{ number_format($eval['batas_atas'], 4, ',', '.') }}</td>
                                <td data-label="Hasil Analisa" class="fw-bold text-primary">
                                    {{ number_format($hasilList[$index]->nilai_hasil, 4, ',', '.') }}
                                </td>
                                <td data-label="Status">
                                    @if($eval['status'] === 'Terima')
                                        <span class="badge bg-success">Diterima</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr><td colspan="8" class="lt-empty">Data evaluasi CRM tidak tersedia.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
@php 
    $validIndex = 1; 
    $fixedLcl = $selectedParameter->lcl ?? 0;
    $fixedLwl = $selectedParameter->uwl_bawah ?? 0;
    $fixedMean = $selectedParameter->mean ?? 0;
    $fixedUwl = $selectedParameter->uwl_atas ?? 0;
    $fixedUcl = $selectedParameter->ucl ?? 0;
    $fixedSd = $selectedParameter->sd ?? 0;
    $minus1Sd = $fixedMean - $fixedSd;
    $plus1Sd = $fixedMean + $fixedSd;
@endphp
<!-- Table Card Control Chart Limit -->
<div class="col-xl-9 col-md-6 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header py-2" style="background-color: #1b3152; color: #ffffff; font-weight: 600; font-size: 0.85rem;">
            Tabel Limit Control Chart In-house
        </div>
        <div class="card-body p-0">

            {{-- Ringkasan batas kontrol (HP) — di layar besar tampil sebagai kolom tabel --}}
            <div class="d-md-none px-2 pt-2">
                <div class="row g-1">
                    <div class="col-4"><div class="lt-chip"><small>LCL</small><strong class="text-danger">{{ number_format($fixedLcl, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="lt-chip"><small>LWL</small><strong class="text-warning">{{ number_format($fixedLwl, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="lt-chip"><small>μ-1σ</small><strong class="text-success">{{ number_format($minus1Sd, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="lt-chip"><small>μ</small><strong class="text-primary">{{ number_format($fixedMean, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="lt-chip"><small>μ+1σ</small><strong class="text-success">{{ number_format($plus1Sd, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="lt-chip"><small>UWL</small><strong class="text-warning">{{ number_format($fixedUwl, 2, ',', '.') }}</strong></div></div>
                    <div class="col-12"><div class="lt-chip"><small>UCL</small><strong class="text-danger">{{ number_format($fixedUcl, 2, ',', '.') }}</strong></div></div>
                </div>
            </div>

            <div class="table-responsive lt-scroll mt-2 mt-md-0">
                <table class="table table-bordered table-striped table-hover mb-0 text-center lt-table">
                    <thead class="lt-head sticky-top">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pengujian Ke-</th>
                            <th class="d-none d-md-table-cell" title="Lower Control Limit (-3SD)">LCL<br>({{ number_format($fixedLcl, 2) }})</th>
                            <th class="d-none d-md-table-cell" title="Lower Warning Limit (-2SD)">LWL<br>({{ number_format($fixedLwl, 2) }})</th>
                            <th class="d-none d-md-table-cell" title="Mean - 1SD">μ-1σ<br>({{ number_format($minus1Sd, 2) }})</th>
                            <th class="d-none d-md-table-cell">μ<br>({{ number_format($fixedMean, 2) }})</th>
                            <th class="d-none d-md-table-cell" title="Mean + 1SD">μ+1σ<br>({{ number_format($plus1Sd, 2) }})</th>
                            <th class="d-none d-md-table-cell" title="Upper Warning Limit (+2SD)">UWL<br>({{ number_format($fixedUwl, 2) }})</th>
                            <th class="d-none d-md-table-cell" title="Upper Control Limit (+3SD)">UCL<br>({{ number_format($fixedUcl, 2) }})</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilList as $hasil)
                        @if($hasil->status_berketerimaan !== 'gagal_duplo')
                        <tr>
                            <td>{{ $hasil->created_at->format('d/m/Y') }}</td>
                            <td>{{ $validIndex++ }}</td>
                            <td class="d-none d-md-table-cell text-danger">{{ number_format($fixedLcl, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell text-warning text-dark">{{ number_format($fixedLwl, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell text-success">{{ number_format($minus1Sd, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell text-primary fw-bold">{{ number_format($fixedMean, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell text-success">{{ number_format($plus1Sd, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell text-warning text-dark">{{ number_format($fixedUwl, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell text-danger">{{ number_format($fixedUcl, 2, ',', '.') }}</td>
                            <td class="fw-bold text-primary">
                                {{ number_format($hasil->nilai_hasil, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .lt-scroll { max-height: 250px; overflow-y: auto; }
    .lt-head th {
        background-color: #1b3152 !important; color: #ffffff !important; border-color: #ffffff !important;
        font-size: 0.72rem !important; vertical-align: middle !important;
    }
    .lt-table td { font-size: 0.75rem !important; vertical-align: middle !important; }

    .lt-chip { border: 1px solid #dee2e6; border-radius: 6px; padding: 4px 8px; text-align: center; background: #fff; }
    .lt-chip small { display: block; font-size: 0.62rem; color: #6c757d; }
    .lt-chip strong { font-size: 0.75rem; }

    @media (max-width: 767.98px) {
        .lt-scroll { max-height: 380px; }
        .lt-table th, .lt-table td { padding: 6px 6px !important; }

        /* tabel CRM jadi kartu per baris */
        .lt-stack thead { display: none; }
        .lt-stack, .lt-stack tbody, .lt-stack tr, .lt-stack td { display: block; width: 100%; }
        .lt-stack tbody tr {
            border: 1px solid #dee2e6; border-radius: 8px; margin: 8px; padding: 6px 12px;
            width: auto; background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }
        .lt-stack td {
            display: flex; justify-content: space-between; align-items: center; gap: 12px;
            text-align: right; border: 0 !important; padding: 4px 0 !important; background: transparent !important;
        }
        .lt-stack td[data-label]::before {
            content: attr(data-label); font-weight: 600; color: #1b3152; text-align: left; flex-shrink: 0;
        }
        .lt-stack td.lt-empty { display: block; text-align: center; }
    }
</style>