@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Tahap 4: Penetapan Target</li>
    </x-qc-breadcrumb>

    <div class="row justify-content-center mt-3">
        <div class="col-xl-10">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h3 class="fw-bold text-dark mb-0"><i class="fas fa-bullseye text-primary me-2"></i>Penetapan Nilai Target (Tahap 4)</h3>
                <a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Sampel</a>
            </div>
            <p class="text-muted">Sesuai prosedur operasional standar, Nilai Target (Mean) dan Simpangan Baku (Standar Deviasi) diwarisi otomatis secara presisi dari kalkulasi <strong>Global Mean</strong> dan <strong>Global Standard Deviation</strong> dari 20 titik uji Homogenitas (Tahap 3) yang telah lolos validasi statistik.</p>

            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
            @endif

            <form action="{{ route('qc-inhouse.penetapan-target.store', $batch->sampel_inhouse_id) }}" method="POST">
                @csrf

                <div class="alert alert-info border-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <strong>Pengesahan Otomatis:</strong> Tidak perlu input manual. Silakan tinjau rentang kendali (Control Limits) di bawah ini lalu klik <strong>"Sahkan Nilai Target"</strong>.
                    </div>
                </div>

                <ul class="nav nav-pills mt-4 mb-3" id="paramTabs" role="tablist">
                    @foreach($batch->parameters as $idx => $param)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold border {{ $idx === 0 ? 'active' : 'text-secondary' }}" id="tab-{{ $param->id }}" data-bs-toggle="pill" data-bs-target="#pane-{{ $param->id }}" type="button" role="tab">
                                {{ $param->parameterUji->nama_parameter }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="paramTabsContent">
                    @foreach($batch->parameters as $idx => $param)
                        @php 
                            $pid = $param->id; 
                            $mean = round($param->mean_target ?? $param->mean_global, 2);
                            $sd = round($param->sd_target ?? $param->sd_global, 2);
                            
                            $ucl = round($mean + (3 * $sd), 2);
                            $lcl = round($mean - (3 * $sd), 2);
                            $uwl = round($mean + (2 * $sd), 2);
                            $lwl = round($mean - (2 * $sd), 2);
                        @endphp
                        <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}" id="pane-{{ $pid }}" role="tabpanel">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body p-5">
                                    <h4 class="fw-bold mb-4 text-center border-bottom pb-3">Statistika Nilai Target : {{ $param->parameterUji->nama_parameter }}</h4>
                                    
                                    <div class="row text-center mb-5 g-4">
                                        <div class="col-md-6">
                                            <div class="p-4 bg-light border rounded h-100">
                                                <div class="text-muted mb-2 text-uppercase fw-bold">Target Mean</div>
                                                <h1 class="display-5 fw-bold text-success mb-0">{{ number_format($mean, 2) }}</h1>
                                                <div class="small text-muted mt-2">Ditarik dari Grand Mean Homogenitas</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-4 bg-light border rounded h-100">
                                                <div class="text-muted mb-2 text-uppercase fw-bold">Target Standard Deviation</div>
                                                <h1 class="display-5 fw-bold text-primary mb-0">{{ number_format($sd, 2) }}</h1>
                                                <div class="small text-muted mt-2">Ditarik dari Global SD Homogenitas</div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="fw-bold mb-3"><i class="fas fa-chart-line text-secondary me-2"></i>Rentang Kendali (Control Chart Limits)</h5>
                                    
                                    <div class="row mb-3 g-3">
                                        <div class="col-md-6">
                                            <div class="card border-danger bg-danger bg-opacity-10 h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title text-danger fw-bold border-bottom border-danger pb-2">Control Limit (+/- 3 SD)</h6>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <div>
                                                            <div class="small text-muted">UCL (+3 SD)</div>
                                                            <div class="fs-5 fw-bold">{{ number_format($ucl, 2) }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="small text-muted">LCL (-3 SD)</div>
                                                            <div class="fs-5 fw-bold">{{ number_format($lcl, 2) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-warning bg-warning bg-opacity-10 h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title text-dark fw-bold border-bottom border-warning pb-2">Warning Limit (+/- 2 SD)</h6>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <div>
                                                            <div class="small text-muted">UWL (+2 SD)</div>
                                                            <div class="fs-5 fw-bold">{{ number_format($uwl, 2) }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="small text-muted">LWL (-2 SD)</div>
                                                            <div class="fs-5 fw-bold">{{ number_format($lwl, 2) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted small fst-italic text-center mt-4">Angka-angka ini akan digunakan sebagai patokan dasar (baseline) untuk Uji Stabilitas bulanan dan Quality Control harian.</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-2 mb-5">
                    @if($batch->status === 'penetapan_target')
                        <button type="submit" class="btn btn-primary px-5 py-3 fs-5 shadow">
                            <i class="fas fa-check-double me-2"></i> Sahkan Semua Nilai Target & Lanjut
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
