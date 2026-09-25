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
                                <th>Units</th>
                                <th style="width:120px;">Lab Value</th>
                                <th style="width:140px;">Assigned Value<br><small class="text-muted">(Alg. Mean)</small></th>
                                <th style="width:120px;">SDPA</th>
                                <th style="width:100px;">Z-score</th>
                                <th style="width:130px;">Comment</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Lookup parameter TM untuk dipakai oleh baris IM
                                $tmParam = $program->parameters->first(function($x) {
                                    return strtoupper($x->parameterUji->nama_parameter ?? '') === 'TM';
                                });
                                $draft = is_array($program->draft_data) ? $program->draft_data : json_decode($program->draft_data ?? '{}', true);
                                
                                // Cari parameter_uji_id milik Carbon untuk fallback CHN historis
                                $cParamId = null;
                                foreach ($program->parameters as $pp) {
                                    if (strtoupper($pp->parameterUji->nama_parameter ?? '') === 'C') {
                                        $cParamId = $pp->parameter_uji_id;
                                        break;
                                    }
                                }
                            @endphp
                            @foreach($program->parameters as $p)
                                @php
                                    $rawName = strtoupper($p->parameterUji->nama_parameter ?? '');
                                    $pid = $p->parameter_uji_id;
                                    $draftInputs = $draft['params'][$pid]['inputs'] ?? [];
                                    
                                    $mappedName = $rawName;
                                    $unit = '-';
                                    $labValue = floatval($p->nilai_akhir);
                                    $method = $p->metode_uji ?? '-';
                                    $showRow = true;

                                    $getDraftVal = function($keySuffix) use ($draftInputs, $pid) {
                                        $key = "params[{$pid}][data][0]{$keySuffix}";
                                        return (isset($draftInputs[$key]) && is_numeric($draftInputs[$key])) ? floatval($draftInputs[$key]) : null;
                                    };

                                    if ($rawName === 'IM' || $rawName === 'TM') {
                                        $mappedName = 'Total Moisture';
                                        $unit = '%, ar';
                                        if ($labValue == 0) {
                                            // Coba ambil dari parameter TM
                                            $labValue = floatval($tmParam->nilai_akhir ?? 0);
                                            
                                            // Jika TM di database juga kosong, fallback ke draft TM
                                            if ($labValue == 0) {
                                                $tmPid = $tmParam->parameter_uji_id ?? $pid;
                                                $tmInputs = $draft['params'][$tmPid]['inputs'] ?? [];
                                                $val1 = isset($tmInputs["params[{$tmPid}][data][0][d1]"]) && is_numeric($tmInputs["params[{$tmPid}][data][0][d1]"]) ? floatval($tmInputs["params[{$tmPid}][data][0][d1]"]) : null;
                                                $val2 = isset($tmInputs["params[{$tmPid}][data][0][d2]"]) && is_numeric($tmInputs["params[{$tmPid}][data][0][d2]"]) ? floatval($tmInputs["params[{$tmPid}][data][0][d2]"]) : null;
                                                if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                                elseif ($val1 !== null) $labValue = $val1;
                                            }
                                        }
                                        if ($rawName === 'TM') $showRow = false;
                                    } elseif ($rawName === 'ASH') {
                                        $mappedName = 'Ash Content';
                                        $unit = '%, db';
                                        if ($labValue == 0) {
                                            $val1 = $getDraftVal('[db1]');
                                            $val2 = $getDraftVal('[db2]');
                                            if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                            elseif ($val1 !== null) $labValue = $val1;
                                        }
                                    } elseif ($rawName === 'VM') {
                                        $mappedName = 'Volatile Matter';
                                        $unit = '%, db';
                                        if ($labValue == 0) {
                                            $val1 = $getDraftVal('[db1]');
                                            $val2 = $getDraftVal('[db2]');
                                            if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                            elseif ($val1 !== null) $labValue = $val1;
                                        }
                                    } elseif ($rawName === 'TOTAL SULFUR (%AD/DB)' || $rawName === 'TOTAL SULFUR' || strpos($rawName, 'SULFUR') !== false) {
                                        $mappedName = 'Total Sulfur';
                                        $unit = '%, db';
                                        if ($labValue == 0) {
                                            // Prioritas: coba ambil db (dry basis) dulu, fallback ke ad
                                            $val1 = $getDraftVal('[db1]');
                                            $val2 = $getDraftVal('[db2]');
                                            if ($val1 === null || $val2 === null) {
                                                $val1 = $getDraftVal('[mentah][ts_1]');
                                                $val2 = $getDraftVal('[mentah][ts_2]');
                                            }
                                            if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                            elseif ($val1 !== null) $labValue = $val1;
                                        }
                                    } elseif ($rawName === 'CV' || $rawName === 'GCV') {
                                        $mappedName = 'GCV';
                                        $unit = 'kcal/kg, db';
                                        if ($labValue == 0) {
                                            $val1 = $getDraftVal('[d1]');
                                            $val2 = $getDraftVal('[d2]');
                                            if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                            elseif ($val1 !== null) $labValue = $val1;
                                        }
                                    } elseif ($rawName === 'C') {
                                        $mappedName = 'Carbon';
                                        $unit = '%, db';
                                        if ($labValue == 0) {
                                            $val1 = $getDraftVal('[d1]');
                                            $val2 = $getDraftVal('[d2]');
                                            if ($val1 === null || $val2 === null) {
                                                $val1 = $getDraftVal('[hasil_1]');
                                                $val2 = $getDraftVal('[hasil_2]');
                                            }
                                            if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                            elseif ($val1 !== null) $labValue = $val1;
                                        }
                                    } elseif ($rawName === 'H') {
                                        $mappedName = 'Hydrogen';
                                        $unit = '%, db';
                                        if ($labValue == 0) {
                                            // Coba ambil dari draft milik H sendiri dulu
                                            $val1 = $getDraftVal('[d1]');
                                            $val2 = $getDraftVal('[d2]');
                                            if ($val1 === null || $val2 === null) {
                                                $val1 = $getDraftVal('[hasil_1]');
                                                $val2 = $getDraftVal('[hasil_2]');
                                            }
                                            // Fallback ke draft Carbon (data historis di mana H terbungkus di C)
                                            if (($val1 === null || $val2 === null) && $cParamId !== null) {
                                                $cInputs = $draft['params'][$cParamId]['inputs'] ?? [];
                                                $val1 = isset($cInputs["params[{$pid}][data][0][hasil_1]"]) && is_numeric($cInputs["params[{$pid}][data][0][hasil_1]"]) ? floatval($cInputs["params[{$pid}][data][0][hasil_1]"]) : null;
                                                $val2 = isset($cInputs["params[{$pid}][data][0][hasil_2]"]) && is_numeric($cInputs["params[{$pid}][data][0][hasil_2]"]) ? floatval($cInputs["params[{$pid}][data][0][hasil_2]"]) : null;
                                            }
                                            if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                            elseif ($val1 !== null) $labValue = $val1;
                                        }
                                    } elseif ($rawName === 'N') {
                                        $mappedName = 'Nitrogen';
                                        $unit = '%, db';
                                        if ($labValue == 0) {
                                            $val1 = $getDraftVal('[d1]');
                                            $val2 = $getDraftVal('[d2]');
                                            if ($val1 === null || $val2 === null) {
                                                $val1 = $getDraftVal('[hasil_1]');
                                                $val2 = $getDraftVal('[hasil_2]');
                                            }
                                            // Fallback ke draft Carbon (data historis)
                                            if (($val1 === null || $val2 === null) && $cParamId !== null) {
                                                $cInputs = $draft['params'][$cParamId]['inputs'] ?? [];
                                                $val1 = isset($cInputs["params[{$pid}][data][0][hasil_1]"]) && is_numeric($cInputs["params[{$pid}][data][0][hasil_1]"]) ? floatval($cInputs["params[{$pid}][data][0][hasil_1]"]) : null;
                                                $val2 = isset($cInputs["params[{$pid}][data][0][hasil_2]"]) && is_numeric($cInputs["params[{$pid}][data][0][hasil_2]"]) ? floatval($cInputs["params[{$pid}][data][0][hasil_2]"]) : null;
                                            }
                                            if ($val1 !== null && $val2 !== null) $labValue = ($val1 + $val2) / 2;
                                            elseif ($val1 !== null) $labValue = $val1;
                                        }
                                    } else {
                                        $showRow = false;
                                    }
                                    
                                    $labValueFormatted = is_numeric($labValue) && $labValue != 0 ? number_format($labValue, 4, '.', '') : '';
                                @endphp
                                @if($showRow)
                                <tr data-pid="{{ $p->id }}">
                                    <td class="text-start fw-bold">{{ $mappedName }}</td>
                                    <td>{{ $unit }}</td>
                                    <td>
                                        <input type="text" inputmode="decimal" class="form-control form-control-sm text-center in-labvalue" 
                                               name="params[{{ $p->id }}][lab_value]" value="{{ $labValueFormatted }}">
                                    </td>
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
                                    <td class="text-muted small">{{ $method }}</td>
                                </tr>
                                @endif
                            @endforeach
                            <!-- Relative Density Manual Row -->
                            <tr>
                                <td class="text-start fw-bold">Relative Density</td>
                                <td>db</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center bg-light" value="NA" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm bg-light" disabled>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm bg-light" disabled>
                                </td>
                                <td class="fw-bold">-</td>
                                <td><span class="text-muted">-</span></td>
                                <td class="text-muted small">-</td>
                            </tr>
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
        const inLabValue = row.querySelector('.in-labvalue');
        const inAssigned = row.querySelector('.in-assigned');
        const inSdpa = row.querySelector('.in-sdpa');
        const outZ = row.querySelector('.out-zscore');
        const outStatus = row.querySelector('.out-status');

        if (!inLabValue || !inAssigned || !inSdpa) return;

        function recalc() {
            const labValue = num(inLabValue.value);
            const assigned = num(inAssigned.value);
            const sdpa = num(inSdpa.value);
            if (assigned === null || sdpa === null || sdpa === 0 || labValue === null) {
                outZ.textContent = '-';
                outStatus.innerHTML = '<span class="text-muted">-</span>';
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

        inLabValue.addEventListener('input', recalc);
        inAssigned.addEventListener('input', recalc);
        inSdpa.addEventListener('input', recalc);
        
        recalc();
    });
});
</script>
@endsection