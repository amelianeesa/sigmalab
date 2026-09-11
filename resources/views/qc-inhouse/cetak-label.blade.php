@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5 cetak-container">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Cetak Label Botol</li>
    </x-qc-breadcrumb>

    <div class="row mt-3 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 text-center">
                    <h3 class="fw-bold text-dark mb-2"><i class="fas fa-tags text-primary me-2"></i>Cetak Label Identitas Botol</h3>
                    <p class="text-muted mb-4">Berikut adalah <strong>{{ $batch->jumlah_botol }}</strong> label botol yang dihasilkan untuk batch <strong>{{ $batch->kode_batch }}</strong>.</p>
                    
                    <button onclick="window.print()" class="btn btn-outline-dark px-4 me-2">
                        <i class="fas fa-print me-1"></i> Print Label
                    </button>
                    <a href="{{ route('qc-inhouse.instruksi-homogenitas', $batch->sampel_inhouse_id) }}" class="btn btn-primary px-4">
                        <i class="fas fa-arrow-right me-1"></i> Lanjut ke Uji Homogenitas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Cetak Label -->
    <div class="print-area">
        <div class="row g-3">
            @foreach($labels as $label)
                <div class="col-4 label-col">
                    <div class="border border-dark rounded p-3 text-center bg-white h-100 shadow-sm label-box">
                        <div class="small fw-bold text-muted mb-1 border-bottom pb-1">IN-HOUSE STANDARD</div>
                        <h4 class="fw-bold text-dark mb-1 mt-2">{{ $label }}</h4>
                        <div class="small text-secondary">{{ $batch->jenis_batubara }} - {{ \Carbon\Carbon::parse($batch->tanggal_preparasi)->format('d/m/Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
/* CSS khusus untuk layar cetak */
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
    .label-col {
        width: 33.333333%;
        float: left;
        padding: 5px;
    }
    .label-box {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
}
</style>
@endsection
