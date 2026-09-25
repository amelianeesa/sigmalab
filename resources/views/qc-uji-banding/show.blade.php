@extends('layouts.app')
@section('title', 'Detail Data - QC Uji Banding')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item active">Detail</li>
    </x-qc-breadcrumb>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="fas fa-balance-scale text-primary me-2"></i>Detail Uji Banding</h2>
        </div>
        <div>
            <a href="{{ route('qc-uji-banding.edit', $program->id) }}" class="btn btn-outline-secondary me-2"><i class="fas fa-edit"></i> Edit Data</a>
            <a href="{{ route('qc-uji-banding.evaluasi.form', $program->id) }}" class="btn btn-outline-primary me-2"><i class="fas fa-chart-bar"></i> Input Hasil Evaluasi Vendor</a>
            <a href="{{ route('qc-uji-banding.ringkasan', $program->id) }}" class="btn btn-outline-success me-2"><i class="fas fa-table"></i> Ringkasan Unjuk Kerja</a>
            <a href="{{ route('qc-uji-banding.printPdf', $program->id) }}" class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf"></i> Cetak Laporan (PDF)</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm border-0">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Program</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted w-50">Nama Program</td>
                            <td class="fw-bold">{{ $program->nama_program }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Penyelenggara</td>
                            <td class="fw-bold">{{ $program->penyelenggara }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kode Sampel</td>
                            <td class="fw-bold"><span class="badge bg-secondary">{{ $program->kode_sampel }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Terima</td>
                            <td class="fw-bold">{{ $program->tanggal_terima->format('d M Y') }}</td>
                        </tr>

                        @if($program->keterangan)
                        <tr>
                            <td class="text-muted">Keterangan</td>
                            <td class="fw-bold">{{ $program->keterangan }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Parameter Uji & Evaluasi Vendor</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Parameter</th>
                                    <th>Nilai Lab</th>
                                    <th>Z-Score Vendor</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($program->parameters as $param)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $param->parameterUji->nama_parameter }}</td>
                                    <td class="text-primary fw-bold">{{ $param->nilai_akhir }}</td>
                                    <td>{{ $param->z_score ?? '-' }}</td>
                                    <td>
                                        @if($param->status_evaluasi === 'menunggu')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu Vendor</span>
                                        @elseif($param->status_evaluasi === 'inlier')
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Inlier</span>
                                        @elseif($param->status_evaluasi === 'warning')
                                            <span class="badge bg-info"><i class="fas fa-exclamation-circle"></i> Warning</span>
                                        @elseif($param->status_evaluasi === 'outlier')
                                            <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Outlier</span>
                                            @if($param->status_investigasi === 'menunggu_investigasi')
                                                <div class="small text-danger mt-1"><i class="fas fa-exclamation-triangle"></i> Butuh LKS</div>
                                            @elseif($param->status_investigasi === 'selesai_investigasi')
                                                <div class="small text-success mt-1"><i class="fas fa-check-double"></i> LKS Selesai</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        
                                        @if($param->status_evaluasi === 'outlier')
                                            @if($param->status_investigasi === 'menunggu_investigasi')
                                                <a href="{{ route('qc-uji-banding.investigasi', [$program->id, $param->id]) }}" class="btn btn-sm btn-danger" title="Isi Lembar Ketidaksesuaian (LKS)"><i class="fas fa-file-alt"></i> Isi LKS</a>
                                            @elseif($param->status_investigasi === 'selesai_investigasi')
                                                <div class="btn-group">
                                                    <a href="{{ route('qc-uji-banding.cetak.pdf', [$program->id, $param->id]) }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                                                    <a href="{{ route('qc-uji-banding.cetak.word', [$program->id, $param->id]) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-word"></i> Word</a>
                                                </div>
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
        </div>
    
    <!-- Bagian Data Mentah (Preview Tampilan Cetak) -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Preview Data Mentah Pengujian</h5>
        </div>
        <div class="card-body">
            <!-- Render Data Mentah untuk Proximate Analysis sebagai contoh -->
            @php
                // Filter hanya Proximate Analysis untuk preview
                $proximateParams = $program->parameters->filter(function($p) {
                    return $p->parameterUji && $p->parameterUji->kategori_parameter === 'Proximate Analysis';
                });
            @endphp
            
            @if($proximateParams->count() > 0)
                <h5 class="fw-bold border-bottom pb-2">Modul Proximate Analysis</h5>
                @foreach($proximateParams as $param)
                    @php 
                        $code = $param->parameterUji->kode_parameter; 
                        $dataMentah = $param->data_mentah;
                    @endphp
                    
                    <div class="mt-4 mb-2 fw-bold text-primary">{{ $param->parameterUji->nama_parameter }} ({{ $code }})</div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle text-center" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    @if($code === 'IM')
                                        <th>SAMPLE ID</th>
                                        <th>DISH NO</th>
                                        <th>M1</th>
                                        <th>M2</th>
                                        <th>M3</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th class="bg-warning bg-opacity-25">M%</th>
                                        <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                        <th>AVERAGE %</th>
                                    @elseif($code === 'ASH')
                                        <th>SAMPLE ID</th>
                                        <th>DISH NO</th>
                                        <th>M1</th>
                                        <th>M2</th>
                                        <th>M2-M1</th>
                                        <th>M3</th>
                                        <th>M3-M1</th>
                                        <th class="bg-warning bg-opacity-25">ASH%</th>
                                        <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                        <th>AVERAGE %adb</th>
                                    @elseif($code === 'VM')
                                        <th>SAMPLE ID</th>
                                        <th>DISH NO</th>
                                        <th>M1</th>
                                        <th>M2</th>
                                        <th>M2-M1</th>
                                        <th>M3</th>
                                        <th>M2-M3</th>
                                        <th>%LOSS</th>
                                        <th>%M adb</th>
                                        <th class="bg-warning bg-opacity-25">%VM</th>
                                        <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                        <th>AVERAGE %adb</th>
                                    @elseif($code === 'FC')
                                        <th>KODE SAMPEL</th>
                                        <th>IM %</th>
                                        <th>ASH %</th>
                                        <th>VM %</th>
                                        <th class="bg-warning bg-opacity-25">FC %</th>
                                        <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                        <th>AVERAGE %adb</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($dataMentah['data']) && is_array($dataMentah['data']))
                                    @foreach($dataMentah['data'] as $row)
                                        <!-- Simplo -->
                                        <tr>
                                            @if($code !== 'FC')
                                                <td rowspan="2">{{ $row['sample_id'] ?? '-' }}</td>
                                                <td>{{ $row['dish_1'] ?? 'S' }}</td>
                                                <td>{{ $row['mentah']['m1_1'] ?? '' }}</td>
                                                
                                                @if($code === 'IM')
                                                    <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['a_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['a_1'])) : '' }}</td>
                                                    <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['b_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['b_1'])) : '' }}</td>
                                                    <td>{{ $row['mentah']['a_1'] ?? '' }}</td>
                                                    <td>{{ $row['mentah']['b_1'] ?? '' }}</td>
                                                @elseif($code === 'ASH')
                                                    <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['m2m1_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['m2m1_1'])) : '' }}</td>
                                                    <td>{{ $row['mentah']['m2m1_1'] ?? '' }}</td>
                                                    <td>{{ $row['mentah']['m3_1'] ?? '' }}</td>
                                                    <td>{{ isset($row['mentah']['m3_1'], $row['mentah']['m1_1']) ? (floatval($row['mentah']['m3_1']) - floatval($row['mentah']['m1_1'])) : '' }}</td>
                                                @elseif($code === 'VM')
                                                    <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['m2m1_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['m2m1_1'])) : '' }}</td>
                                                    <td>{{ $row['mentah']['m2m1_1'] ?? '' }}</td>
                                                    <td>{{ $row['mentah']['m3_1'] ?? '' }}</td>
                                                    <td>{{ isset($row['mentah']['m2m1_1'], $row['mentah']['m3_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['m2m1_1']) - floatval($row['mentah']['m3_1'])) : '' }}</td>
                                                    <td>{{ isset($row['mentah']['loss_1']) ? $row['mentah']['loss_1'] : '' }}</td>
                                                    <td>{{ isset($row['mentah']['m_adb_1']) ? $row['mentah']['m_adb_1'] : '' }}</td>
                                                @endif
                                                
                                                <td class="bg-warning bg-opacity-10 fw-bold">{{ $row['d1'] ?? '' }}</td>
                                            @else
                                                <td rowspan="2">{{ $row['kode_sampel'] ?? '-' }}</td>
                                                <td>{{ $row['im_1'] ?? '' }}</td>
                                                <td>{{ $row['ash_1'] ?? '' }}</td>
                                                <td>{{ $row['vm_1'] ?? '' }}</td>
                                                <td class="bg-warning bg-opacity-10 fw-bold">{{ $row['d1'] ?? '' }}</td>
                                            @endif
                                            
                                            <!-- These span 2 rows -->
                                            <td rowspan="2">{{ $row['diff'] ?? '-' }}</td>
                                            <td rowspan="2">{{ $row['tol'] ?? '-' }}</td>
                                            <td rowspan="2">{{ $row['avg'] ?? '-' }}</td>
                                        </tr>
                                        <!-- Duplo -->
                                        <tr>
                                            @if($code !== 'FC')
                                                <td>{{ $row['dish_2'] ?? 'D' }}</td>
                                                <td>{{ $row['mentah']['m1_2'] ?? '' }}</td>
                                                
                                                @if($code === 'IM')
                                                    <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['a_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['a_2'])) : '' }}</td>
                                                    <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['b_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['b_2'])) : '' }}</td>
                                                    <td>{{ $row['mentah']['a_2'] ?? '' }}</td>
                                                    <td>{{ $row['mentah']['b_2'] ?? '' }}</td>
                                                @elseif($code === 'ASH')
                                                    <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['m2m1_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['m2m1_2'])) : '' }}</td>
                                                    <td>{{ $row['mentah']['m2m1_2'] ?? '' }}</td>
                                                    <td>{{ $row['mentah']['m3_2'] ?? '' }}</td>
                                                    <td>{{ isset($row['mentah']['m3_2'], $row['mentah']['m1_2']) ? (floatval($row['mentah']['m3_2']) - floatval($row['mentah']['m1_2'])) : '' }}</td>
                                                @elseif($code === 'VM')
                                                    <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['m2m1_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['m2m1_2'])) : '' }}</td>
                                                    <td>{{ $row['mentah']['m2m1_2'] ?? '' }}</td>
                                                    <td>{{ $row['mentah']['m3_2'] ?? '' }}</td>
                                                    <td>{{ isset($row['mentah']['m2m1_2'], $row['mentah']['m3_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['m2m1_2']) - floatval($row['mentah']['m3_2'])) : '' }}</td>
                                                    <td>{{ isset($row['mentah']['loss_2']) ? $row['mentah']['loss_2'] : '' }}</td>
                                                    <td>{{ isset($row['mentah']['m_adb_2']) ? $row['mentah']['m_adb_2'] : '' }}</td>
                                                @endif
                                                
                                                <td class="bg-warning bg-opacity-10 fw-bold">{{ $row['d2'] ?? '' }}</td>
                                            @else
                                                <td>{{ $row['im_2'] ?? '' }}</td>
                                                <td>{{ $row['ash_2'] ?? '' }}</td>
                                                <td>{{ $row['vm_2'] ?? '' }}</td>
                                                <td class="bg-warning bg-opacity-10 fw-bold">{{ $row['d2'] ?? '' }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @else
                <div class="text-center text-muted py-3">Tidak ada data mentah Proximate Analysis.</div>
            @endif
        </div>
    </div>
</div>
@endsection



