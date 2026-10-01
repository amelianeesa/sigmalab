@extends('layouts.app')
@section('title', 'Preparasi - QC In-House')

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
    }

    .page-subtitle {
        font-size: 0.72rem;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1px;
    }

    .section-note {
        font-size: 0.68rem;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .step-badge {
        background-color: #1b3152;
        color: #ffffff !important;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        margin-right: 6px;
    }

    .form-label {
        font-size: 0.74rem;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .form-control,
    .form-select {
        font-size: 0.78rem;
        padding-top: 0.28rem;
        padding-bottom: 0.28rem;
        color: #000000;
    }

    .form-control::placeholder {
        color: #8a939c;
    }

    .form-control[readonly] {
        background-color: #f1f4f8;
        color: #000000;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(1.5em + 0.56rem + 2px);
        font-size: 0.78rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single {
        display: flex;
        align-items: center;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        color: #000000;
        font-weight: 400;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #ced4da;
        box-shadow: none;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #ced4da;
    }

    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option {
        font-size: 0.82rem;
        padding: 8px 12px;
    }

    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option[aria-selected="true"],
    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option--selected {
        background-color: rgba(27, 49, 82, 0.18) !important;
        color: #000000 !important;
    }

    .select2-container .select2-results__option,
    .select2-container .select2-results__options .select2-results__option,
    .select2-dropdown .select2-results__option,
    .form-select option {
        color: #000000 !important;
    }

    .select2-container .select2-results__option:hover,
    .select2-container .select2-results__option--highlighted,
    .select2-container .select2-results__option--highlighted[aria-selected],
    .select2-dropdown .select2-results__option:hover,
    .select2-dropdown .select2-results__option--highlighted,
    .select2-dropdown .select2-results__option--highlighted[aria-selected] {
        background-color: rgba(27, 49, 82, 0.15) !important;
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

    .btn-corporate-blue:disabled {
        opacity: 0.6;
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

    .submit-wrap {
        align-items: stretch !important;
    }

    .submit-wrap .btn {
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1.3;
        white-space: nowrap;
    }

    .dashboard-container .page-title,
    .dashboard-container .page-subtitle,
    .dashboard-container .section-title,
    .dashboard-container .section-note,
    .dashboard-container .form-label,
    .dashboard-container .text-secondary,
    .dashboard-container .text-dark,
    .dashboard-container .text-muted,
    .dashboard-container .select2-selection__rendered {
        color: #000000 !important;
    }

    .sop-card {
        background-color: #1b3152;
        color: #ffffff;
        border: 0 !important;
    }

    .sop-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 12px 4px 12px;
        cursor: default;
    }

    .sop-card .sop-title {
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #ffffff;
    }

    .sop-chevron {
        display: none;
        color: #ffffff;
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }

    .sop-header[aria-expanded="true"] .sop-chevron {
        transform: rotate(180deg);
    }

    .sop-content {
        padding: 6px 12px 12px 12px;
    }

    .sop-card p {
        font-size: 0.78rem;
        line-height: 1.5;
        color: #e8eef7;
    }

    .sop-card strong {
        color: #ffffff;
    }

    @media (min-width: 992px) {
        .sop-collapse.collapse:not(.show) {
            display: block;
        }
    }

    @media (max-width: 991.98px) {
        .sop-header {
            cursor: pointer;
            padding: 10px 12px;
        }

        .sop-chevron {
            display: inline-block;
        }

        .sop-content {
            padding-top: 2px;
        }
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

        .form-control,
        .form-select {
            font-size: 16px;
            min-height: 40px;
        }

        textarea.form-control {
            min-height: 80px;
        }

        .form-label {
            font-size: 0.78rem;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 40px;
            font-size: 16px;
            border: 1px solid #ced4da;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            font-size: 16px;
        }

        .select2-container--bootstrap-5 .select2-dropdown .select2-results__option {
            font-size: 16px;
            padding: 12px 14px;
        }

        .sop-card p {
            font-size: 0.82rem;
        }

        .submit-wrap {
            flex-direction: column-reverse;
        }

        .submit-wrap .btn {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="container-fluid dashboard-container" style="font-size: 0.78rem;">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Tahap 2: Preparasi & Ekuilibrium</li>
    </x-qc-breadcrumb>

    <div class="row g-2">
        <div class="col-xl-9 col-lg-8 order-2 order-lg-1">
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body">
                    <h5 class="page-title text-dark"><i class="fas fa-balance-scale icon-corporate me-2"></i>Tahap 2: Preparasi</h5>
                    <p class="page-subtitle">Lakukan penghamparan batubara bulk, periksa laju kehilangan bobot hingga mencapai ekuilibrium (&lt; 0.1% per jam), lalu kemas ke dalam botol.</p>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 mb-2 rounded-3" style="font-size: 0.75rem;">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('qc-inhouse.preparasi.store', $batch->sampel_inhouse_id) }}" method="POST" id="formPreparasi">
                        @csrf

                        <div class="mb-3">
                            <h6 class="section-title text-dark border-bottom pb-2 mb-2"><span class="step-badge">Langkah 1</span>Identitas Acuan & Hamparan</h6>
                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Nama Sampel</label>
                                    <input type="text" class="form-control" value="{{ $batch->nama_sampel }}" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Metode Acuan <span class="text-danger">*</span></label>
                                    <select name="metode_acuan" id="metode_acuan" class="form-select" required>
                                        <option value="">-- Pilih Metode Acuan --</option>
                                        <option value="astm" {{ old('metode_acuan', $batch->metode_acuan) == 'astm' ? 'selected' : '' }}>ASTM</option>
                                        <option value="iso" {{ old('metode_acuan', $batch->metode_acuan) == 'iso' ? 'selected' : '' }}>ISO</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 mt-3" id="sectionPengemasan">
                            <h6 class="section-title text-dark border-bottom pb-2 mb-2"><span class="step-badge">Langkah 2</span>Pengemasan & Pelabelan Botol</h6>
                            <p class="section-note">Bagian ini hanya boleh diisi setelah batubara mencapai bobot konstan, dikemas dalam plastik ganda, dan dimasukkan ke dalam botol.</p>

                            <div class="row g-2">
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Kode Batch <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_batch" class="form-control" value="{{ old('kode_batch', $batch->kode_batch ?? 'INH-'.date('ym')) }}" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Jumlah Botol Total <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_botol" class="form-control" value="{{ old('jumlah_botol', $batch->jumlah_botol ?? 50) }}" min="10" inputmode="numeric" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Nomor Awal Botol <span class="text-danger">*</span></label>
                                    <input type="number" name="nomor_awal_botol" class="form-control" value="{{ old('nomor_awal_botol', $batch->nomor_awal_botol ?? 1) }}" min="1" inputmode="numeric" required>
                                </div>
                            </div>

                            <div class="mt-2">
                                <label class="form-label">Catatan Preparasi (Opsional)</label>
                                <textarea name="catatan_preparasi" class="form-control" rows="2" placeholder="Suhu ruangan, kondisi ayak 60 mesh, dll...">{{ old('catatan_preparasi', $batch->catatan_preparasi) }}</textarea>
                            </div>
                        </div>

                        <div class="submit-wrap d-flex justify-content-end align-items-center gap-2 mt-3">
                            <a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="btn btn-kembali btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            @if($batch->status === 'preparasi')
                            <button type="submit" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold" id="btnSubmit">
                                <i class="fas fa-save me-1"></i> Simpan & Lanjut Homogenitas
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 order-1 order-lg-2">
            <div class="card sop-card shadow-sm mb-2">
                <div class="sop-header" role="button" data-bs-toggle="collapse" data-bs-target="#sopBody" aria-expanded="false" aria-controls="sopBody">
                    <h6 class="sop-title"><i class="fas fa-info-circle me-1"></i> SOP Air-Drying</h6>
                    <i class="fas fa-chevron-down sop-chevron"></i>
                </div>
                <div id="sopBody" class="collapse sop-collapse">
                    <div class="sop-content">
                        <p class="mb-2"><strong>Langkah 1:</strong> Sampel batubara giling dihamparkan di nampan untuk memastikan tidak ada pengotor.</p>
                        <p class="mb-0"><strong>Langkah 2:</strong> Batubara dikemas dalam kantong plastik ganda, dimasukkan botol plastik, diberi label, dan dilanjut ke Uji Homogenitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#metode_acuan').select2({
        theme: 'bootstrap-5',
        width: '100%',
        minimumResultsForSearch: Infinity
    });
});
</script>
@endsection