@extends('layouts.app')
@section('title', 'Cetak Label - QC In-House')

@php
    $namaFileLabel = 'Label_inhouse_' . trim(preg_replace('/[^A-Za-z0-9]+/', '_', $batch->nama_sampel), '_');
@endphp

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
        padding: 12px !important;
    }

    .page-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: #000000 !important;
    }

    .page-subtitle {
        font-size: 0.72rem;
        margin-bottom: 10px;
        line-height: 1.4;
        color: #000000 !important;
    }

    .icon-corporate {
        color: #1b3152;
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

    .btn-kembali {
        background-color: #6c757d !important;
        border: 1px solid #6c757d !important;
        color: #ffffff !important;
    }

    .btn-kembali:hover,
    .btn-kembali:focus,
    .btn-kembali:active {
        background-color: #ffffff !important;
        border-color: #6c757d !important;
        color: #6c757d !important;
    }

    .action-wrap {
        align-items: stretch !important;
    }

    .action-wrap .btn {
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1.3;
        white-space: nowrap;
    }

    .label-box {
        border: 1px solid #1b3152;
        border-radius: 6px;
        background: #ffffff;
        padding: 8px 6px;
        text-align: center;
        height: 100%;
    }

    .label-header {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #ffffff;
        background-color: #1b3152;
        border-radius: 3px;
        padding: 2px 4px;
        margin-bottom: 6px;
    }

    .label-code {
        font-size: 0.95rem;
        font-weight: 700;
        color: #000000;
        margin: 0 0 2px 0;
        word-break: break-all;
    }

    .label-info {
        font-size: 0.65rem;
        color: #000000;
        line-height: 1.3;
    }

    #print-root {
        display: none;
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

        .card-body {
            padding: 10px !important;
        }

        .action-wrap {
            flex-direction: column-reverse;
        }

        .action-wrap .btn {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }

        .label-code {
            font-size: 0.85rem;
        }
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            background: #ffffff !important;
        }

        body.printing > *:not(#print-root) {
            display: none !important;
        }

        body.printing #print-root {
            display: block !important;
            position: static;
            width: 100%;
        }

        #print-root .print-area {
            width: 100%;
        }

        #print-root .row {
            margin: 0 !important;
            --bs-gutter-x: 0;
            --bs-gutter-y: 0;
        }

        #print-root .label-col {
            flex: 0 0 33.333333% !important;
            max-width: 33.333333% !important;
            width: 33.333333% !important;
            padding: 2px 3px !important;
            margin: 0 !important;
        }

        #print-root .label-box {
            border: 1px solid #000000 !important;
            border-radius: 4px;
            padding: 3px 4px;
            height: auto;
            box-shadow: none !important;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        #print-root .label-header {
            font-size: 0.6rem;
            line-height: 1.3;
            padding: 1px 4px;
            margin-bottom: 2px;
            background-color: #1b3152 !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #print-root .label-code {
            font-size: 1rem;
            line-height: 1.15;
            margin: 0 0 1px 0;
        }

        #print-root .label-info {
            font-size: 0.65rem;
            line-height: 1.2;
        }
    }
</style>

<div class="container-fluid dashboard-container" style="font-size: 0.78rem;">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Cetak Label Botol</li>
    </x-qc-breadcrumb>

    <div class="card shadow-sm border-0 mb-2 no-print">
        <div class="card-body">
            <h5 class="page-title"><i class="fas fa-tags icon-corporate me-2"></i>Cetak Label Identitas Botol</h5>
            <p class="page-subtitle">
                Berikut adalah <strong>{{ $batch->jumlah_botol }}</strong> label botol yang dihasilkan untuk batch <strong>{{ $batch->kode_batch }}</strong>.
            </p>

            <div class="action-wrap d-flex justify-content-end align-items-center gap-2">
                <a href="{{ route('qc-inhouse.preparasi', $batch->sampel_inhouse_id) }}" class="btn btn-kembali btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="button" onclick="window.print()" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                    <i class="fas fa-print me-1"></i> Print Label
                </button>
                <a href="{{ route('qc-inhouse.instruksi-homogenitas', $batch->sampel_inhouse_id) }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                    <i class="fas fa-arrow-right me-1"></i> Lanjut ke Uji Homogenitas
                </a>
            </div>
        </div>
    </div>

    <div class="print-area">
        <div class="row g-2">
            @foreach($labels as $label)
                <div class="col-6 col-md-4 col-xl-3 label-col">
                    <div class="label-box">
                        <div class="label-header">IN-HOUSE STANDARD</div>
                        <h4 class="label-code">{{ $label }}</h4>
                        <div class="label-info">{{ $batch->jenis_batubara }} - {{ \Carbon\Carbon::parse($batch->tanggal_preparasi)->format('d/m/Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
(function () {
    var printRoot = null;
    var judulAsli = document.title;
    var judulCetak = @json($namaFileLabel);

    function siapkanCetak() {
        if (printRoot) return;
        var sumber = document.querySelector('.print-area');
        if (!sumber) return;

        printRoot = document.createElement('div');
        printRoot.id = 'print-root';
        printRoot.innerHTML = sumber.outerHTML;
        document.body.appendChild(printRoot);
        document.body.classList.add('printing');

        document.title = judulCetak;
    }

    function bersihkanCetak() {
        document.body.classList.remove('printing');
        if (printRoot && printRoot.parentNode) {
            printRoot.parentNode.removeChild(printRoot);
        }
        printRoot = null;
        document.title = judulAsli;
    }

    window.addEventListener('beforeprint', siapkanCetak);
    window.addEventListener('afterprint', bersihkanCetak);
})();
</script>
@endsection