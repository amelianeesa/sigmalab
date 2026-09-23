@extends('layouts.app')
@section('title', 'Verifikasi Teknis - ' . $katalog->nomor_lot)

@section('content')

<style>
    /* Memaksa tabel agar melebar sesuai isinya dan memunculkan scrollbar */
    .param-table {
        min-width: max-content;
    }
    
    /* Mencegah teks judul kolom (header) terpotong/turun ke baris baru */
    .param-table th {
        white-space: nowrap;
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
    
    /* Memberikan lebar minimal pada kotak input angka agar tidak gepeng */
    .param-table td input.form-control {
        min-width: 110px;
    }
    
    /* Memberikan lebar minimal pada kotak dropdown (select) */
    .param-table td select.form-select {
        min-width: 130px;
    }
    
    /* Memberikan lebar minimal pada teks hasil/kalkulasi otomatis */
    .param-table td[class*="out-"] {
        min-width: 80px;
        white-space: nowrap;
    }
</style>

<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.show', $katalog->id) }}" class="text-decoration-none">{{ $katalog->nomor_lot }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Verifikasi Teknis</li>
    </x-qc-breadcrumb>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-flask text-primary me-2"></i>Verifikasi Teknis</h2>
        <p class="text-muted mb-0">Botol: <strong>{{ $katalog->nomor_lot }}</strong> — {{ $katalog->nama_produk }}</p>
    </div>

    <form action="{{ route('crm-katalog.verifikasi-teknis.store', $katalog->id) }}" method="POST" id="formVerTeknis">
        @csrf

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Analis</label>
                @php
                    // Ambil analis_id dari data draft pertama yang ditemukan, atau dari 'old' input jika validasi gagal
                    $firstData = collect($existingData)->first();
                    $savedAnalisId = old('analis_id', $firstData ? $firstData->analis_id : '');
                @endphp
                <select name="analis_id" class="form-select" required>
                    <option value="">-- Pilih Analis --</option>
                    @foreach(\App\Models\Personil::orderBy('nama')->get() as $p)
                        <option value="{{ $p->id }}" {{ $p->id == $savedAnalisId ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white pt-3 border-bottom-0">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    @foreach($katalog->sertifikats as $index => $s)
                        @php $code = strtoupper($s->parameterUji->nama_parameter); @endphp
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold {{ $index === 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#pane-{{ $s->parameter_uji_id }}"
                                    type="button" role="tab">
                                {{ $code }}
                                @if(\App\Models\CrmVerifikasiTeknis::where('crm_katalog_id', $katalog->id)->where('parameter_uji_id', $s->parameter_uji_id)->exists())
                                    <i class="fas fa-check-circle text-success ms-1"></i>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    @foreach($katalog->sertifikats as $index => $s)
                        @php
                            $code = strtoupper($s->parameterUji->nama_parameter);
                            $pid = $s->parameter_uji_id;
                        @endphp
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $pid }}" role="tabpanel">
                            <div class="alert alert-secondary m-3 py-2 small">
                                Sertifikat: True Value = <strong>{{ $s->cert_value }}</strong> ± <strong>{{ $s->cert_u }}</strong>
                                (rentang inlier: {{ $s->cert_value - $s->cert_u }} — {{ $s->cert_value + $s->cert_u }})
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table table-bordered table-sm align-middle text-center param-table" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Pengujian Ke-</th>
                                        @if(in_array($code, ['IM','RM']))
                                            <th>M1</th><th>M2</th><th>M3</th><th>A</th><th>B</th><th class="bg-warning bg-opacity-25">M%</th>
                                        @elseif($code === 'ASH')
                                            <th>M1</th><th>M2</th><th>M2-M1</th><th>M3</th><th>M3-M1</th><th class="bg-warning bg-opacity-25">ASH%</th>
                                        @elseif($code === 'VM')
                                            <th>M1</th><th>M2-M1</th><th>M2</th><th>M3</th><th>M2-M3</th><th>LOSS%</th><th>IM</th><th class="bg-warning bg-opacity-25">VM%</th>
                                        @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                            <th>Massa Sample</th><th class="bg-warning bg-opacity-25">TS (adb)</th>
                                        @elseif(in_array($code, ['CV','GCV']))
                                            <th>Vessel No</th><th>Call ID</th><th>Weight of Crucible</th><th>Sample Mass</th><th>Preliminary Result (cal/g)</th><th>Ee (cal/°C)</th><th>t (°C)</th><th>Volume of Titrant (ml)</th><th>Length of Fuse (cm)</th><th>Total Sulfur % adb</th><th class="bg-warning bg-opacity-25">Final Result (cal/g) adb</th>
                                        @elseif($code === 'CHN')
                                            <th>Weight</th><th>N % db</th><th>C % db</th><th>H % db</th>
                                        @elseif($code === 'AFT')
                                            <th>Reducing/Oxidizing</th><th>IDT</th><th>ST</th><th>HT</th><th>FT</th>
                                        @else
                                            <th>DISH NO</th><th class="bg-warning bg-opacity-25">Hasil</th>
                                        @endif
                                        @if(!in_array($code, ['CHN', 'AFT', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                            <th colspan="2">Absolute Differences</th>
                                        @endif
                                        @if(!in_array($code, ['CHN', 'AFT']))
                                            <th class="bg-warning bg-opacity-25">Average</th>
                                        @endif
                                        @if(in_array($code, ['ASH','VM','TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV','GCV']))
                                            <th class="bg-info bg-opacity-25">%db</th>
                                        @endif
                                        @if(in_array($code, ['ASH','VM','CV','GCV']))
                                            <th class="bg-info bg-opacity-10">db (per baris)</th>
                                        @endif
                                        </tr>
                                    </thead>
                                    @php
                                        $draftRows = ($existingData[$pid]->data_mentah ?? null) ?: [[]];
                                    @endphp
                                    @foreach($draftRows as $rIndex => $mentah)
                                    <tbody class="crm-tbody" data-index="{{ $rIndex }}">
                                        <tr class="row-entry simplo-row" data-type="simplo">
                                            <td rowspan="2" class="align-middle fw-bold">
                                                <span class="pengujian-no">{{ $rIndex + 1 }}</span>
                                                @if(!in_array($code, ['CHN','AFT']))
                                                    <input type="hidden" class="hid-d1" name="params[{{ $pid }}][data][{{ $rIndex }}][nilai_d1]">
                                                    <input type="hidden" class="hid-d2" name="params[{{ $pid }}][data][{{ $rIndex }}][nilai_d2]">
                                                @endif
                                            </td>
                                            @if(in_array($code, ['IM','RM']))
                                                <td><input class="form-control form-control-sm in-m1-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2-1 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-m3-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-a-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][a_1]" value="{{ $mentah['a_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-b-1 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-1 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif($code === 'ASH')
                                                <td><input class="form-control form-control-sm in-m1-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2-1 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-m2m1-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m2m1_1]" value="{{ $mentah['m2m1_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m3-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m3m1-1 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-1 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif($code === 'VM')
                                                <td><input class="form-control form-control-sm in-m1-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2m1-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m2m1_1]" value="{{ $mentah['m2m1_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2-1 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-m3-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2m3-1 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-loss-1 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-im-1 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-1 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                <td><input class="form-control form-control-sm in-mass-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][mass_1]" value="{{ $mentah['mass_1'] ?? '' }}"></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-1 fw-bold text-primary" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][ts_1]" value="{{ $mentah['ts_1'] ?? '' }}"></td>
                                            @elseif(in_array($code, ['CV','GCV']))
                                                <td><input class="form-control form-control-sm in-vessel-1" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][vessel_1]" value="{{ $mentah['vessel_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-callid-1" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][callid_1]" value="{{ $mentah['callid_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-wc-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][wc_1]" value="{{ $mentah['wc_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-mass-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][mass_1]" value="{{ $mentah['mass_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-pre-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][pre_1]" value="{{ $mentah['pre_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-ee-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][ee_1]" value="{{ $mentah['ee_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-t-1 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-vt-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][vt_1]" value="{{ $mentah['vt_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-lf-1" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][lf_1]" value="{{ $mentah['lf_1'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-ts-1 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-1 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif($code === 'CHN')
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][weight]" value="{{ $mentah['weight'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][n_db]" value="{{ $mentah['n_db'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][c_db]" value="{{ $mentah['c_db'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][h_db]" value="{{ $mentah['h_db'] ?? '' }}"></td>
                                            @elseif($code === 'AFT')
                                                <td>
                                                    <select class="form-select form-select-sm" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][atmosfer]">
                                                        <option value="Reducing" {{ ($mentah['atmosfer'] ?? '') == 'Reducing' ? 'selected' : '' }}>Reducing</option>
                                                        <option value="Oxidizing" {{ ($mentah['atmosfer'] ?? '') == 'Oxidizing' ? 'selected' : '' }}>Oxidizing</option>
                                                    </select>
                                                </td>
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][idt]" value="{{ $mentah['idt'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][st]" value="{{ $mentah['st'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][ht]" value="{{ $mentah['ht'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][ft]" value="{{ $mentah['ft'] ?? '' }}"></td>
                                            @else
                                                <td><input class="form-control form-control-sm in-dish-1" value="S"></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-1 fw-bold text-primary" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][hasil_1]" value="{{ $mentah['hasil_1'] ?? '' }}"></td>
                                            @endif
                                            @if(!in_array($code, ['CHN', 'AFT', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                <td rowspan="2" class="out-diff">-</td>
                                                <td rowspan="2" class="out-yesno">-</td>
                                            @endif
                                            @if(!in_array($code, ['CHN', 'AFT']))
                                                <td rowspan="2" class="out-avg bg-warning bg-opacity-25 fw-bold">-</td>
                                            @endif
                                            @if(in_array($code, ['ASH','VM','TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV','GCV']))
                                                <td rowspan="2" class="out-avg-db bg-info bg-opacity-25 fw-bold">-</td>
                                            @endif
                                            @if(in_array($code, ['ASH','VM','CV','GCV']))
                                                <td class="out-db-1 bg-info bg-opacity-10">-</td>
                                            @endif
                                        </tr>
                                        <tr class="row-entry duplo-row" data-type="duplo">
                                            @if(in_array($code, ['IM','RM']))
                                                <td><input class="form-control form-control-sm in-m1-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2-2 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-m3-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-a-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][a_2]" value="{{ $mentah['a_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-b-2 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-2 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif($code === 'ASH')
                                                <td><input class="form-control form-control-sm in-m1-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2-2 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-m2m1-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m2m1_2]" value="{{ $mentah['m2m1_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m3-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m3m1-2 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-2 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif($code === 'VM')
                                                <td><input class="form-control form-control-sm in-m1-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2m1-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m2m1_2]" value="{{ $mentah['m2m1_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2-2 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-m3-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-m2m3-2 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-loss-2 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-im-2 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-2 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                <td><input class="form-control form-control-sm in-mass-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][mass_2]" value="{{ $mentah['mass_2'] ?? '' }}"></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-2 fw-bold text-primary" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][ts_2]" value="{{ $mentah['ts_2'] ?? '' }}"></td>
                                            @elseif(in_array($code, ['CV','GCV']))
                                                <td><input class="form-control form-control-sm in-vessel-2" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][vessel_2]" value="{{ $mentah['vessel_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-callid-2" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][callid_2]" value="{{ $mentah['callid_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-wc-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][wc_2]" value="{{ $mentah['wc_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-mass-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][mass_2]" value="{{ $mentah['mass_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-pre-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][pre_2]" value="{{ $mentah['pre_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-ee-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][ee_2]" value="{{ $mentah['ee_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-t-2 bg-light" readonly></td>
                                                <td><input class="form-control form-control-sm in-vt-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][vt_2]" value="{{ $mentah['vt_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-lf-2" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][lf_2]" value="{{ $mentah['lf_2'] ?? '' }}"></td>
                                                <td><input class="form-control form-control-sm in-ts-2 bg-light" readonly></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-2 fw-bold text-primary bg-transparent border-0" readonly></td>
                                            @elseif(in_array($code, ['CHN','AFT']))
                                                {{-- baris duplo tidak dipakai untuk CHN/AFT, kosongkan lewat CSS d-none di JS --}}
                                            @else
                                                <td><input class="form-control form-control-sm in-dish-2" value="D"></td>
                                                <td class="bg-warning bg-opacity-10"><input class="form-control form-control-sm in-hasil-2 fw-bold text-primary" inputmode="decimal" name="params[{{ $pid }}][data][{{ $rIndex }}][mentah][hasil_2]" value="{{ $mentah['hasil_2'] ?? '' }}"></td>
                                            @endif
                                            @if(in_array($code, ['ASH','VM','CV','GCV']))
                                                <td class="out-db-2 bg-info bg-opacity-10">-</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                    @endforeach
                                    <tfoot>
                                    <tfoot>
                                        <tr>
                                            <td colspan="20" class="text-start">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-add-pengujian" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                                    <i class="fas fa-plus"></i> Tambah Pengujian
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-pengujian" data-pid="{{ $pid }}">
                                                    <i class="fas fa-minus"></i> Hapus Pengujian Terakhir
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="card bg-light border-0 mt-3 mx-3 mb-4 preview-box" data-pid="{{ $pid }}" style="display: none;">
                                <div class="card-body py-3">
                                    <h6 class="text-primary fw-bold mb-3"><i class="fas fa-chart-line me-2"></i>Live Evaluasi (Preview)</h6>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <span class="small text-muted d-block">True Value (Sertifikat)</span>
                                            <strong class="fs-5">{{ $s->cert_value }}</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="small text-muted d-block">Rentang Inlier (Batas Bawah - Atas)</span>
                                            <strong class="fs-5">{{ $s->cert_value - $s->cert_u }} <span class="fw-normal text-muted mx-1">—</span> {{ $s->cert_value + $s->cert_u }}</strong>
                                        </div>
                                        <div class="col-md-3 border-start">
                                            <span class="small text-muted d-block">Nilai Akhir Pengujian (Mean)</span>
                                            <strong class="fs-5 text-dark out-nilai-akhir">-</strong>
                                        </div>
                                        <div class="col-md-3 border-start">
                                            <span class="small text-muted d-block">Keputusan</span>
                                            <strong class="fs-5 out-keputusan">-</strong>
                                        </div>
                                    </div>
                                    <input type="hidden" class="hid-cert-val" value="{{ $s->cert_value }}">
                                    <input type="hidden" class="hid-cert-u" value="{{ $s->cert_u }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" name="action" value="draft" class="btn btn-outline-secondary px-4">
            <i class="fas fa-save me-1"></i> Simpan Draft
        </button>
        <button type="submit" name="action" value="final" class="btn btn-primary px-4">
            <i class="fas fa-check-circle me-1"></i> Selesaikan Verifikasi
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function num(v) { const n = parseFloat(v); return (v === '' || v === null || isNaN(n)) ? 0 : n; }
    const R2 = n => Math.round(n * 100) / 100;
    const R0 = n => Math.round(n);

    // FIX: Gunakan document.querySelector agar bisa menyeberang mencari tabel di tab lain (IM/RM)
    function findImTbody(index) {
        const imTable = document.querySelector('table[data-code="IM"], table[data-code="RM"]');
        if (!imTable) return null;
        return imTable.querySelector(`.crm-tbody[data-index="${index}"]`);
    }
    
    // ambil M% IM pada baris (simplo/duplo) yang sama
    function getIm(index, type) {
        const tb = findImTbody(index);
        if (!tb) return 0;
        const row = tb.querySelector(`.row-entry[data-type="${type}"]`);
        const i = type === 'simplo' ? 1 : 2;
        return num(row?.querySelector('.in-hasil-'+i)?.value);
    }
    
    // ambil rata-rata M% IM (simplo+duplo) pada pengujian yang sama
    function getImAvg(index) {
        const s = getIm(index, 'simplo');
        const d = getIm(index, 'duplo');
        return (s && d) ? (s + d) / 2 : 0;
    }

    function calc(tbody) {
        const table = tbody.closest('table');
        const code = table.dataset.code;
        const index = tbody.dataset.index;
        const s = tbody.querySelector('.row-entry[data-type="simplo"]');
        const d = tbody.querySelector('.row-entry[data-type="duplo"]');
        let v1 = 0, v2 = 0;

        if (['IM','RM'].includes(code)) {
            [s, d].forEach((row, idx) => {
                const i = idx + 1;
                const m1 = num(row.querySelector('.in-m1-'+i)?.value);
                const a = num(row.querySelector('.in-a-'+i)?.value);
                const m3 = num(row.querySelector('.in-m3-'+i)?.value);
                if (m1 && a) row.querySelector('.in-m2-'+i).value = (m1+a).toFixed(4);
                const b = (m3 && m1) ? (m3 - m1) : 0;
                row.querySelector('.in-b-'+i).value = (m3 && m1) ? b.toFixed(4) : '';
                const val = a ? ((a-b)/a*100) : 0;
                row.querySelector('.in-hasil-'+i).value = a ? val.toFixed(2) : '';
                if (i===1) v1 = val; else v2 = val;
            });
        } else if (code === 'ASH') {
            [s, d].forEach((row, idx) => {
                const i = idx + 1;
                const m1 = num(row.querySelector('.in-m1-'+i)?.value);
                const m2m1 = num(row.querySelector('.in-m2m1-'+i)?.value);
                const m3 = num(row.querySelector('.in-m3-'+i)?.value);
                if (m1 && m2m1) row.querySelector('.in-m2-'+i).value = (m1+m2m1).toFixed(4);
                const m3m1 = (m3 && m1) ? (m3 - m1) : 0;
                row.querySelector('.in-m3m1-'+i).value = (m3 && m1) ? m3m1.toFixed(4) : '';
                const val = m2m1 ? (m3m1/m2m1*100) : 0;
                row.querySelector('.in-hasil-'+i).value = m2m1 ? val.toFixed(2) : '';
                if (i===1) v1 = val; else v2 = val;

                // FIX: panggil getIm tanpa parameter tabPane
                const imRow = getIm(index, i===1?'simplo':'duplo');
                const dbCell = tbody.querySelector('.out-db-'+i);
                if (dbCell) dbCell.textContent = (imRow && val) ? R2(100/(100-imRow)*val) : '-';
            });
        } else if (code === 'VM') {
            [s, d].forEach((row, idx) => {
                const i = idx + 1;
                const m1 = num(row.querySelector('.in-m1-'+i)?.value);
                const m2m1 = num(row.querySelector('.in-m2m1-'+i)?.value);
                const m3 = num(row.querySelector('.in-m3-'+i)?.value);
                if (m1 && m2m1) row.querySelector('.in-m2-'+i).value = (m1+m2m1).toFixed(4);
                const m2 = m1 + m2m1;
                const m2m3 = (m2 && m3) ? (m2 - m3) : 0;
                row.querySelector('.in-m2m3-'+i).value = (m2 && m3) ? m2m3.toFixed(4) : '';
                const loss = m2m1 ? (m2m3/m2m1*100) : 0;
                row.querySelector('.in-loss-'+i).value = m2m1 ? loss.toFixed(4) : '';

                // FIX: panggil getIm tanpa parameter tabPane
                const imVal = getIm(index, i===1?'simplo':'duplo');
                row.querySelector('.in-im-'+i).value = imVal || '';

                const val = loss - imVal;
                row.querySelector('.in-hasil-'+i).value = (loss || imVal) ? val.toFixed(2) : '';
                if (i===1) v1 = val; else v2 = val;

                const dbCell = tbody.querySelector('.out-db-'+i);
                if (dbCell) dbCell.textContent = (imVal && val) ? R2(100/(100-imVal)*val) : '-';
            });
        } else if (['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)'].includes(code)) {
            v1 = num(s.querySelector('.in-hasil-1')?.value);
            v2 = num(d.querySelector('.in-hasil-2')?.value);
        } else if (['CV','GCV'].includes(code)) {
            [s, d].forEach((row, idx) => {
                const i = idx + 1;
                const pre = num(row.querySelector('.in-pre-'+i)?.value);
                const ee = num(row.querySelector('.in-ee-'+i)?.value);
                const mass = num(row.querySelector('.in-mass-'+i)?.value);
                const t = ee ? (pre/ee*mass) : 0;
                row.querySelector('.in-t-'+i).value = ee ? t.toFixed(4) : '';

                const vt = num(row.querySelector('.in-vt-'+i)?.value);
                const lf = num(row.querySelector('.in-lf-'+i)?.value);
                
                // FIX: Menyeberang tab untuk mencari TS secara global
                const tsReal = (function() {
                    const tsTable = document.querySelector('table[data-code="TS"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR"], table[data-code="TOTAL SULFUR (%AD/DB)"]');
                    const tsTb = tsTable?.querySelector(`.crm-tbody[data-index="${index}"]`);
                    const tsRow = tsTb?.querySelector(`.row-entry[data-type="${i===1?'simplo':'duplo'}"]`);
                    return num(tsRow?.querySelector('.in-hasil-'+i)?.value);
                })();
                row.querySelector('.in-ts-'+i).value = tsReal || '';

                const val = mass ? R0((pre - (14.3*0.0699*vt) - (2.3*lf) - (13.3*tsReal*mass)) / mass) : 0;
                row.querySelector('.in-hasil-'+i).value = mass ? val : '';
                if (i===1) v1 = val; else v2 = val;
            });
        } else if (['CHN','AFT'].includes(code)) {
            return;
        } else {
            v1 = num(s.querySelector('.in-hasil-1')?.value);
            v2 = num(d.querySelector('.in-hasil-2')?.value);
        }

        const diff = tbody.querySelector('.out-diff');
        const yesno = tbody.querySelector('.out-yesno');
        const avg = tbody.querySelector('.out-avg');
        const hidD1 = tbody.querySelector('.hid-d1');
        const hidD2 = tbody.querySelector('.hid-d2');
        const avgDb = tbody.querySelector('.out-avg-db');

        if (v1 && v2) {
            const selisih = Math.abs(v1-v2);
            if (diff) diff.textContent = selisih.toFixed(2);
            const a = (v1+v2)/2;
            if (avg) avg.textContent = a.toFixed(2);
            if (hidD1) hidD1.value = v1;
            if (hidD2) hidD2.value = v2;

            let batas = null;
            if (['IM','RM'].includes(code)) batas = 0.09 + (0.1 * a);
            else if (code === 'ASH') batas = 0.22;
            else if (code === 'VM') batas = 1;
            
            if (batas !== null && yesno) {
                yesno.textContent = selisih < batas ? 'YES' : 'NO';
                yesno.className = 'out-yesno fw-bold ' + (selisih < batas ? 'text-success' : 'text-danger');
            }

            if (avgDb) {
                // FIX: panggil getImAvg tanpa parameter tabPane
                const imAvg = getImAvg(index);
                avgDb.textContent = imAvg ? R2(100/(100-imAvg)*a) : '-';
            }
        } else {
            if (diff) diff.textContent = '-'; if (yesno) yesno.textContent = '-';
            if (avg) avg.textContent = '-'; if (avgDb) avgDb.textContent = '-';
            if (hidD1) hidD1.value = ''; if (hidD2) hidD2.value = '';
        }
    }

    // Fungsi memicu kalkulasi 2 putaran (agar nilai silang antar-tab ke-update sempurna)
    // Fungsi memicu kalkulasi 2 putaran dan menghitung Live Preview
    function calcAll() {
        const allTbodies = document.querySelectorAll('.crm-tbody');
        allTbodies.forEach(tb => calc(tb)); 
        allTbodies.forEach(tb => calc(tb)); 

        // LOGIKA BARU UNTUK MENGHITUNG LIVE PREVIEW PER PARAMETER
        document.querySelectorAll('.tab-pane').forEach(pane => {
            const previewBox = pane.querySelector('.preview-box');
            if (!previewBox) return;

            const averages = [];
            // Ambil semua angka dari kolom 'Average' di parameter ini
            pane.querySelectorAll('.out-avg').forEach(avgCell => {
                const val = parseFloat(avgCell.textContent);
                if (!isNaN(val)) averages.push(val);
            });

            if (averages.length > 0) {
                // Tampilkan kotak preview
                previewBox.style.display = 'block';

                // Hitung rata-rata akhir (mean dari semua pengujian)
                const sum = averages.reduce((a, b) => a + b, 0);
                const nilaiAkhir = sum / averages.length;

                // Ambil batas sertifikat dari input hidden
                const certVal = parseFloat(previewBox.querySelector('.hid-cert-val').value);
                const certU = parseFloat(previewBox.querySelector('.hid-cert-u').value);
                const batasBawah = certVal - certU;
                const batasAtas = certVal + certU;

                // Tentukan Inlier / Outlier
                const isInlier = (nilaiAkhir >= batasBawah && nilaiAkhir <= batasAtas);

                // Update teks di layar
                previewBox.querySelector('.out-nilai-akhir').textContent = nilaiAkhir.toFixed(4);
                
                const keputusanEl = previewBox.querySelector('.out-keputusan');
                if (isInlier) {
                    keputusanEl.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i><span class="text-success">INLIER</span>';
                } else {
                    keputusanEl.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i><span class="text-danger">OUTLIER</span>';
                }
            } else {
                // Sembunyikan jika belum ada data valid sama sekali
                previewBox.style.display = 'none';
            }
        });
    }

    function attachListeners(tbody) {
        const code = tbody.closest('table').dataset.code;
        if (['CHN','AFT'].includes(code)) {
            tbody.querySelectorAll('.row-entry[data-type="duplo"]').forEach(r => r.remove());
        }
        tbody.querySelectorAll('input:not([readonly]), select').forEach(inp => {
            inp.addEventListener('input', calcAll);
            inp.addEventListener('change', calcAll);
        });
    }

    document.querySelectorAll('.crm-tbody').forEach(attachListeners);
    setTimeout(calcAll, 100); 

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-add-pengujian')) {
            const table = e.target.closest('.btn-add-pengujian').closest('table');
            const tbodies = table.querySelectorAll('.crm-tbody');
            const lastTbody = tbodies[tbodies.length - 1];
            const newIndex = parseInt(lastTbody.dataset.index) + 1;
            const newTbody = lastTbody.cloneNode(true);
            newTbody.dataset.index = newIndex;

            newTbody.querySelectorAll('[name]').forEach(inp => {
                inp.name = inp.name.replace(/[data]\[\d+\]/, `[data][${newIndex}]`);
            });
            newTbody.querySelectorAll('input:not([type="hidden"])').forEach(inp => inp.value = '');
            newTbody.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
            newTbody.querySelectorAll('.out-diff, .out-yesno, .out-avg, .out-avg-db, [class*="out-db-"]').forEach(el => el.textContent = '-');
            newTbody.querySelector('.pengujian-no').textContent = newIndex + 1;

            table.insertBefore(newTbody, table.querySelector('tfoot'));
            attachListeners(newTbody);
        }
        if (e.target.closest('.btn-remove-pengujian')) {
            const table = e.target.closest('.btn-remove-pengujian').closest('table');
            const tbodies = table.querySelectorAll('.crm-tbody');
            if (tbodies.length > 1) {
                tbodies[tbodies.length - 1].remove();
                calcAll(); // Kalkulasi ulang setelah dihapus
            } else {
                alert('Minimal harus ada 1 pengujian.');
            }
        }
    });
});
</script>
@endsection