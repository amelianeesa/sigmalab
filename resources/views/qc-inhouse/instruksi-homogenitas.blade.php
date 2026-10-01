@extends('layouts.app')
@section('title', 'Instruksi Homogenitas - QC In-House')

@section('content')
@php
    $namaFileInstruksi = 'Instruksi_Homogenitas_' . trim(preg_replace('/[^A-Za-z0-9]+/', '_', $batch->nama_sampel), '_');
@endphp
<style>
    .ins-page {
        padding: 0 20px !important;
        margin-top: -8px !important;
        font-size: 0.78rem;
        color: #000;
    }

    .ins-page nav[aria-label="breadcrumb"],
    .ins-page > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .ins-page .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .ins-page .breadcrumb .breadcrumb-item,
    .ins-page .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .ins-page .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .ins-page .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .ins-page .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .ins-page .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .ins-page .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .ins-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .ins-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .ins-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .ins-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .ins-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .ins-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .ins-page .card {
        border-radius: 0.5rem;
    }

    .ins-page .ins-header {
        padding: 12px 12px 0 12px;
        text-align: center;
    }

    .ins-page .page-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: #000;
    }

    .ins-page .page-subtitle {
        font-size: 0.72rem;
        margin-bottom: 0;
        line-height: 1.4;
        color: #000;
    }

    .ins-page .icon-corporate {
        color: #1b3152;
    }

    .ins-page .card-body {
        padding: 12px !important;
    }

    .ins-page .card-footer {
        padding: 10px 12px !important;
        background-color: #ffffff;
    }

    .ins-page .ins-panel {
        padding: 10px 12px;
        border: 1px solid #dee2e6;
        border-radius: 0.4rem;
        background-color: #f8fafc;
    }

    .ins-page .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 4px;
        padding-bottom: 6px;
        color: #000;
        border-bottom: 1px solid #dee2e6;
    }

    .ins-page .section-note {
        font-size: 0.72rem;
        margin-bottom: 8px;
        line-height: 1.4;
        color: #000;
    }

    .ins-page .step-badge {
        background-color: #1b3152;
        color: #ffffff;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        margin-right: 6px;
    }

    .ins-page .ins-table {
        font-size: 0.78rem;
        margin-bottom: 0;
        background-color: #ffffff;
        border-color: #cfd6df;
    }

    .ins-page .ins-table thead th {
        background-color: #1b3152;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.4rem 0.5rem;
        border-color: rgba(255, 255, 255, 0.25);
    }

    .ins-page .ins-table td {
        padding: 0.3rem 0.5rem;
        border-color: #dfe4ea;
        color: #000;
        vertical-align: middle;
    }

    .ins-page .ins-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .ins-page .ins-table .nomor-botol {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1b3152;
    }

    .ins-page .ins-alert {
        margin-top: 10px;
        padding: 8px 12px;
        font-size: 0.75rem;
        line-height: 1.5;
        color: #000;
        background-color: rgba(27, 49, 82, 0.08);
        border: 0;
        border-left: 4px solid #1b3152;
        border-radius: 6px;
    }

    .ins-page .ins-alert i {
        color: #1b3152;
    }

    .ins-page .ins-alert ul {
        padding-left: 1.1rem;
    }

    .ins-page .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .ins-page .btn-corporate-blue:hover,
    .ins-page .btn-corporate-blue:focus,
    .ins-page .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }

    .ins-page .action-wrap {
        align-items: stretch !important;
    }

    .ins-page .action-wrap .btn {
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1.3;
        white-space: nowrap;
    }

    @media (max-width: 767.98px) {
        .ins-page {
            padding: 0 10px !important;
        }

        .ins-page .breadcrumb,
        .ins-page .breadcrumb .breadcrumb-item,
        .ins-page .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .ins-page .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .ins-page .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .ins-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }

        .ins-page .card-body {
            padding: 10px !important;
        }

        .ins-page .ins-header {
            padding: 10px 10px 0 10px;
        }

        .ins-page .ins-panel {
            padding: 8px 10px;
        }

        .ins-page .action-wrap {
            flex-direction: column;
        }

        .ins-page .action-wrap .btn {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }
    }

    .ins-page .print-kop {
        display: none;
    }

    @page {
        size: A4 portrait;
        margin: 15mm 15mm 15mm 15mm;
    }

    @media print {
        html, body { height: 100% !important; max-height: 100% !important; overflow: hidden !important; margin: 0 !important; padding: 0 !important; }
        body * { visibility: hidden; }
        .card * { visibility: visible; }
        .card { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; overflow: visible !important; page-break-inside: avoid; break-inside: avoid; }
        .card-footer, .breadcrumb { display: none !important; }

        .ins-page { padding: 0 !important; margin: 0 !important; font-size: 10pt; }

        .ins-page .print-kop {
            display: block !important;
            padding: 0 12px;
            margin-bottom: 4px;
        }

        .ins-page .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .ins-page .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .ins-page .kop-left { width: 55%; }
        .ins-page .kop-right { width: 45%; text-align: right; }

        .ins-page .kop-title {
            margin: 0 0 2px 0;
            font-size: 15pt;
            font-weight: 700;
            color: #000;
        }

        .ins-page .kop-sub {
            margin: 0;
            font-size: 10pt;
            color: #555;
        }

        .ins-page .kop-logo {
            width: 140px;
            display: block;
            margin-left: auto;
        }

        .ins-page .kop-divider {
            border-bottom: 2px solid #333;
            margin: 6px 0 8px 0;
        }

        .ins-page .kop-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .ins-page .kop-info td {
            border: none;
            padding: 2px 0;
            font-size: 10pt;
        }

        .ins-page .kop-info .kop-label {
            width: 150px;
            font-weight: 700;
        }

        .ins-page .ins-header { padding: 6px 12px 0 12px; }
        .ins-page .card-body { padding: 8px 12px !important; }
        .ins-page .ins-panel { page-break-inside: avoid; break-inside: avoid; }
        .ins-page .ins-table td { padding: 0.2rem 0.5rem; }
        .ins-page .ins-table thead th { padding: 0.3rem 0.5rem; }
        .ins-page .ins-alert { page-break-inside: avoid; break-inside: avoid; }

        .ins-page .ins-table thead th,
        .ins-page .step-badge,
        .ins-page .ins-alert,
        .ins-page .ins-panel {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .ins-page .ins-table thead th {
            background-color: #1b3152 !important;
            color: #ffffff !important;
        }
    }
</style>

<div class="container-fluid ins-page pb-4">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Instruksi Homogenitas</li>
    </x-qc-breadcrumb>

    <div class="row g-2">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-2">
                <div class="print-kop">
                    <table class="kop-table">
                        <tr>
                            <td class="kop-left">
                                <h2 class="kop-title">SIGMA-LAB PT SUCOFINDO</h2>
                                <p class="kop-sub">Instruksi Uji Homogenitas QC In-House</p>
                            </td>
                            <td class="kop-right">
                                <img src="{{ asset('images/Logo_Suco_Nobg.png') }}" class="kop-logo" alt="Logo PT Sucofindo">
                            </td>
                        </tr>
                    </table>
                    <div class="kop-divider"></div>
                    <table class="kop-info">
                        <tr><td class="kop-label">Nama Sampel</td><td>: {{ $batch->nama_sampel }}</td></tr>
                    </table>
                </div>

                <div class="ins-header">
                    <h5 class="page-title">Instruksi Pelaksanaan Uji Homogenitas</h5>
                    <p class="page-subtitle">Harap cetak dan patuhi urutan pengujian di bawah ini untuk menjaga validitas statistik uji.</p>
                </div>

                <div class="card-body">
                    <div class="ins-panel">
                        <h6 class="section-title"><span class="step-badge">1</span>Urutan Pengambilan Botol</h6>
                        <p class="section-note">Ambil botol dari kotak penyimpanan (isi total 50 botol) persis sesuai urutan acak 10 angka di bawah ini:</p>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center ins-table">
                                <thead>
                                    <tr>
                                        <th width="50%">Urutan Ambil</th>
                                        <th width="50%">Nomor Botol yang Diambil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tabelAcak as $acak)
                                        <tr>
                                            <td class="fw-bold">Ke - {{ $acak->urutan }}</td>
                                            <td class="nomor-botol">{{ $acak->nomor_botol }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="ins-alert">
                        <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian Analis:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Gunakan parameter uji yang telah disetujui:
                                <strong>
                                @foreach($batch->parameters as $p)
                                    {{ $p->parameterUji->nama_parameter }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                                </strong>
                            </li>
                            <li>Metode acuan yang digunakan: <strong>{{ strtoupper($batch->metode_acuan) }}</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="action-wrap d-flex justify-content-center align-items-center gap-2">
                        <button type="button" onclick="window.print()" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                            <i class="fas fa-print me-1"></i> Cetak Instruksi
                        </button>
                        <a href="{{ route('qc-inhouse.homogenitas', $batch->sampel_inhouse_id) }}?v={{ time() }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                            <i class="fas fa-arrow-right me-1"></i> Lanjut Input Data Homogenitas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var judulAsli = document.title;
    var judulCetak = @json($namaFileInstruksi);

    window.addEventListener('beforeprint', function () {
        document.title = judulCetak;
    });

    window.addEventListener('afterprint', function () {
        document.title = judulAsli;
    });
})();
</script>
@endsection