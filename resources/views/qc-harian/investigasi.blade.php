@extends('layouts.app')
@section('title', 'Investigasi - QC Harian')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
        padding-bottom: 1.5rem !important;
        font-size: 0.78rem;
        color: #000000;
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

    .dashboard-container .card {
        border-radius: 0.5rem;
    }

    .card-body {
        padding: 12px !important;
    }

    .card-header-sm {
        padding: 8px 12px;
        background-color: #ffffff;
        border-bottom: 1px solid #e3e8ef;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .page-title {
        margin: 8px 0 10px 0;
        font-size: 1rem;
        font-weight: 700;
        color: #000000;
    }

    .section-title {
        margin-bottom: 0;
        font-size: 0.85rem;
        font-weight: 700;
        color: #000000;
    }

    .icon-corporate {
        color: #1b3152;
    }

    .dashboard-container .alert-danger {
        padding: 0.45rem 0.75rem !important;
        font-size: 0.75rem !important;
        line-height: 1.45;
        border-radius: 6px;
    }

    /* ===== Kartu detail outlier (aksen merah seperti laporan investigasi) ===== */
    .outlier-card {
        border: 1px solid #f5c2c7 !important;
        border-left: 4px solid #dc3545 !important;
    }

    .outlier-card .card-header-sm {
        color: #842029;
        background-color: #fdecea;
        border-bottom: 1px solid #f5c2c7;
    }

    .outlier-card .section-title {
        color: #842029;
    }

    .detail-table {
        width: 100%;
        margin: 0;
    }

    .detail-table td {
        padding: 6px 0;
        font-size: 0.78rem;
        color: #000000;
        border-bottom: 1px dashed #dfe4ea;
        vertical-align: middle;
        word-break: break-word;
    }

    .detail-table tr:last-child td {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .detail-table tr:first-child td {
        padding-top: 0;
    }

    .detail-table td:first-child {
        width: 42%;
        padding-right: 8px;
        font-size: 0.74rem;
        font-weight: 600;
        color: #495057 !important;
    }

    .detail-table .nilai-akhir {
        font-size: 0.95rem !important;
    }

    .detail-table .badge {
        padding: 0.35em 0.6em;
        font-size: 0.68rem;
        font-weight: 600;
        border-radius: 4px;
    }

    /* ===== Aturan penguncian ===== */
    .lock-card {
        background-color: #f8fafc !important;
        border: 1px solid #dee2e6 !important;
    }

    .lock-card h6 {
        margin-bottom: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #1b3152 !important;
    }

    .lock-card p {
        font-size: 0.75rem;
        line-height: 1.5;
        color: #000000 !important;
    }

    /* ===== Form CAPA ===== */
    .dashboard-container .form-label {
        margin-bottom: 3px;
        font-size: 0.76rem;
        font-weight: 600;
        color: #000000;
    }

    .form-hint {
        margin-bottom: 6px;
        font-size: 0.72rem;
        line-height: 1.45;
        color: #495057;
    }

    .dashboard-container .form-control {
        font-size: 0.78rem;
        color: #000000;
        border-color: #ced4da;
    }

    .dashboard-container .form-control::placeholder {
        color: #8a939c;
    }

    .dashboard-container .form-control:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .dashboard-container .form-text {
        margin-top: 4px;
        font-size: 0.7rem;
    }

    .form-divider {
        margin: 12px 0;
        border-color: #dee2e6;
        opacity: 1;
    }

    /* ===== Buttons (seragam) ===== */
    .dashboard-container .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.38rem 0.85rem;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.3;
        border-radius: 0.375rem;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border: 1px solid #1b3152 !important;
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

    /* ===== Mobile ===== */
    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 0 10px !important;
            padding-bottom: 1.25rem !important;
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

        .card-body {
            padding: 10px !important;
        }

        .page-title {
            font-size: 0.95rem;
        }

        .dashboard-container .form-control {
            font-size: 16px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch !important;
            gap: 0.5rem;
        }

        .form-actions .btn {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }

        .detail-table td:first-child {
            width: 44%;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('verifikasi-mutu.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('qc-harian.index') }}" class="text-decoration-none">Pengujian Harian QC</a></li>
        <li class="breadcrumb-item active">Investigasi OOC</li>
    </ol>
    <h5 class="page-title">
        <i class="fas fa-exclamation-triangle icon-corporate me-2"></i>Tindakan Perbaikan (CAPA) Out of Control
    </h5>

    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row g-2">
        <div class="col-lg-5">
            <div class="card shadow-sm outlier-card mb-2">
                <div class="card-header-sm">
                    <h6 class="section-title"><i class="fas fa-vial me-2"></i>Detail Pengujian Outlier</h6>
                </div>
                <div class="card-body">
                    <table class="detail-table">
                        <tr>
                            <td class="text-muted">Parameter</td>
                            <td class="fw-bold">{{ strtoupper($qc->parameterUji->nama_parameter) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Pengujian</td>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($qc->tanggal_uji)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Analis</td>
                            <td class="fw-bold">{{ $qc->analis ? $qc->analis->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Simplo (D1)</td>
                            <td class="fw-bold">{{ number_format($qc->nilai_d1, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Duplo (D2)</td>
                            <td class="fw-bold">{{ $qc->nilai_d2 ? number_format($qc->nilai_d2, 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Akhir Rata-rata</td>
                            <td class="fw-bold text-danger nilai-akhir">{{ number_format($qc->nilai_akhir, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pelanggaran Rule</td>
                            <td><span class="badge bg-danger text-wrap text-start lh-base">{{ $qc->pelanggaran_rule }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 lock-card mb-2">
                <div class="card-body">
                    <h6><i class="fas fa-info-circle me-2"></i>Aturan Penguncian Sistem</h6>
                    <p class="mb-0">
                        Sistem telah <strong>membekukan sementara</strong> fasilitas input data pengujian harian untuk parameter <strong>{{ strtoupper($qc->parameterUji->nama_parameter) }}</strong>. Anda maupun analis lain tidak dapat menambahkan data QC untuk parameter ini hingga Root Cause Analysis (Akar Masalah) dan Tindakan Perbaikan (Corrective Action) dilaporkan pada form di samping.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-header-sm">
                    <h6 class="section-title"><i class="fas fa-clipboard-check icon-corporate me-2"></i>Laporan Tindakan Perbaikan (CAPA)</h6>
                </div>
                <div class="card-body">

                    <form action="{{ route('qc-harian.investigasi.store', $qc->id) }}" method="POST" id="formCapa">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Analisis Akar Masalah & Tindakan Perbaikan <span class="text-danger">*</span></label>
                            <p class="form-hint">Jelaskan mengapa hasil pengujian menjadi outlier (misal: kalibrasi alat bergeser, reagen kadaluarsa, kesalahan preparasi) dan apa tindakan perbaikannya (misal: kalibrasi ulang, ganti reagen, re-test).</p>
                            <textarea name="catatan_investigasi" id="catatan_investigasi" rows="8" class="form-control" required minlength="10" placeholder="Contoh: Terjadi kesalahan penimbangan pada botol reagen A. Tindakan: Larutan dibuang dan dibuat reagen baru, kemudian alat dikalibrasi ulang. Pengujian sampel ditahan menunggu hasil running QC selanjutnya."></textarea>
                            <div class="form-text text-danger" id="charCount">Minimal 10 karakter.</div>
                        </div>

                        <hr class="form-divider">

                        <div class="form-actions d-flex justify-content-between align-items-center">
                            <a href="{{ route('qc-harian.index') }}" class="btn btn-kembali btn-sm px-4 shadow-sm">Kembali ke Dashboard</a>
                            <button type="submit" class="btn btn-corporate-blue btn-sm px-4 shadow-sm" id="btnSubmit">
                                <i class="fas fa-unlock me-2"></i>Simpan Laporan & Buka Kunci Parameter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('catatan_investigasi');
        const charCount = document.getElementById('charCount');
        const btnSubmit = document.getElementById('btnSubmit');
        
        textarea.addEventListener('input', function() {
            if(this.value.length < 10) {
                charCount.classList.add('text-danger');
                charCount.classList.remove('text-success');
                charCount.innerHTML = `Minimal 10 karakter. (Saat ini: ${this.value.length})`;
            } else {
                charCount.classList.remove('text-danger');
                charCount.classList.add('text-success');
                charCount.innerHTML = `Panjang karakter sudah mencukupi.`;
            }
        });

        document.getElementById('formCapa').addEventListener('submit', function(e) {
            if(textarea.value.length < 10) {
                e.preventDefault();
                alert('Teks investigasi terlalu singkat!');
                return;
            }
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            btnSubmit.disabled = true;
        });
    });
</script>
@endsection