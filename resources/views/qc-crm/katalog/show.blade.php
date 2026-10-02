@extends('layouts.app')
@section('title', 'Detail Data - crm-katalog')

@section('content')
@include('qc-crm._qc-compact')

@php
    // Batas "segera kedaluwarsa" (hari) — samakan dengan qc-crm/index
    $batasHariExpired = 90;
    $expState = 'none';
    $sisaHari = null;

    if ($katalog->tanggal_expired) {
        $sisaHari = (int) now()->startOfDay()->diffInDays($katalog->tanggal_expired->copy()->startOfDay(), false);
        $expState = $sisaHari < 0 ? 'kedaluwarsa' : ($sisaHari <= $batasHariExpired ? 'segera' : 'aktif');
    }
@endphp

<style>
    .qc-page { padding: 4px 20px !important; }

    /* ===== Tombol ===== */
    .qc-page .btn-corporate-blue,
    #addParamModal .btn-corporate-blue {
        background-color: #1b3152 !important; border-color: #1b3152 !important; color: #fff !important;
    }
    .qc-page .btn-corporate-blue:hover,
    #addParamModal .btn-corporate-blue:hover {
        background-color: #14253e !important; border-color: #14253e !important; color: #fff !important;
    }
    .qc-action-btn {
        width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; padding: 0 !important; border-radius: 6px;
    }

    /* ===== Tabel ===== */
    .qc-page .table-qc thead th {
        background-color: #1b3152 !important; color: #fff !important; border-color: #fff !important;
        font-size: 0.75rem !important; white-space: nowrap;
    }

    /* ===== Modal (di luar .qc-compact, jadi di-style sendiri) ===== */
    #addParamModal .form-label { font-size: 0.75rem; margin-bottom: 0.2rem; }
    #addParamModal .form-control,
    #addParamModal .form-select {
        font-size: 0.8rem !important; padding: 0.3rem 0.6rem !important; min-height: 0;
    }
    #addParamModal .select2-container--bootstrap-5 .select2-selection { font-size: 0.8rem; min-height: 0; padding: 0.2rem 0.4rem; }
    #addParamModal .form-text, #addParamModal .small { font-size: 0.72rem !important; }
    #addParamModal .btn, #modalHapusParamWrap .btn { font-size: 0.78rem; padding: 0.3rem 0.75rem; }

    /* ===== Mobile ===== */
    @media (max-width: 767.98px) {
        .qc-page { padding: 4px 10px !important; }

        .table-stack thead { display: none; }
        .table-stack, .table-stack tbody, .table-stack tr, .table-stack td { display: block; width: 100%; }
        .table-stack tbody tr {
            border: 1px solid #dee2e6; border-radius: 8px; margin: 0 0 10px; padding: 6px 12px;
            background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }
        .table-stack td {
            display: flex; justify-content: space-between; align-items: center; gap: 12px;
            text-align: right; border: 0 !important; padding: 5px 0 !important; background: transparent !important;
        }
        .table-stack td[data-label]::before {
            content: attr(data-label); font-weight: 600; color: #1b3152; text-align: left; flex-shrink: 0;
        }
        .table-stack td.td-aksi { justify-content: flex-end; border-top: 1px solid #eee !important; margin-top: 4px; padding-top: 8px !important; }
        .table-stack td.td-aksi::before { display: none; }
        .table-stack td.td-empty { display: block; text-align: center; }
    }
</style>

<div class="container-fluid qc-page qc-compact pb-4">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $katalog->nomor_lot }} — Detail Botol</li>
    </x-qc-breadcrumb>

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-2 mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
                <i class="fas fa-info-circle me-2" style="color: #1b3152;"></i>Detail Botol CRM
            </h5>
            <div class="text-muted" style="font-size: 0.78rem;">Kelola nilai sertifikat parameter uji untuk botol ini.</div>
        </div>
        <a href="{{ route('crm-katalog.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm align-self-start align-self-md-center">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if($expState === 'kedaluwarsa')
        <div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 ps-3 pe-5 mb-2" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian!</strong> Botol CRM ini sudah kedaluwarsa sejak {{ $katalog->tanggal_expired->format('d-m-Y') }}.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @elseif($expState === 'segera')
        <div class="alert alert-warning alert-dismissible fade show shadow-sm py-2 ps-3 pe-5 mb-2" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-clock me-1"></i> <strong>Perhatian!</strong> Botol CRM ini akan kedaluwarsa dalam <strong>{{ $sisaHari }} hari</strong> ({{ $katalog->tanggal_expired->format('d-m-Y') }}).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @endif

    <div class="row g-3">
        {{-- Informasi Botol --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2" style="color: #1b3152;"></i>Informasi Botol</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 40%;">Nomor Lot</td>
                            <td class="fw-bold"><span class="badge bg-primary">{{ $katalog->nomor_lot }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor Sertifikat</td>
                            <td class="fw-bold">{{ $katalog->nomor_sertifikat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Produk</td>
                            <td class="fw-bold">{{ $katalog->nama_produk }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Produsen</td>
                            <td class="fw-bold">{{ $katalog->produsen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Expired Date</td>
                            <td class="fw-bold">
                                @if($katalog->tanggal_expired)
                                    {{ $katalog->tanggal_expired->format('d-m-Y') }}
                                    @if($expState === 'kedaluwarsa')
                                        <span class="badge bg-danger d-block mt-1 px-2 py-1"><i class="fas fa-times-circle"></i> Kedaluwarsa</span>
                                    @elseif($expState === 'segera')
                                        <span class="badge bg-warning text-dark d-block mt-1 px-2 py-1"><i class="fas fa-clock"></i> Segera Berakhir ({{ $sisaHari }}h)</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="fw-bold">
                                @if($katalog->is_active)
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Inaktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="fw-bold mb-0"><i class="fas fa-list me-2" style="color: #1b3152;"></i>Nilai Sertifikat (True Value)</h5>
                    <button class="btn btn-corporate-blue btn-sm shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addParamModal">
                        <i class="fas fa-plus me-1"></i> Tambah Parameter
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0 table-qc table-stack">
                            <thead>
                                <tr>
                                    <th>Parameter Uji</th>
                                    <th>True Value (Sertifikat)</th>
                                    <th>Ketidakpastian (±)</th>
                                    <th class="text-center" style="width: 70px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($katalog->sertifikats as $s)
                                <tr>
                                    <td data-label="Parameter Uji" class="fw-bold">{{ $s->parameterUji->nama_parameter ?? 'Unknown' }}</td>
                                    <td data-label="True Value"><span class="badge bg-success">{{ $s->cert_value }}</span></td>
                                    <td data-label="Ketidakpastian (±)"><span class="text-muted fw-bold">± {{ $s->cert_u }}</span></td>
                                    <td class="text-center td-aksi">
                                        <button type="button" class="btn btn-danger btn-sm qc-action-btn shadow-sm" title="Hapus" aria-label="Hapus"
                                                data-bs-toggle="modal" data-bs-target="#modalHapusParam{{ $s->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4 td-empty">
                                        <i class="fas fa-vial fs-3 text-light mb-2 d-block"></i>
                                        Belum ada parameter yang didaftarkan pada botol ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalHapusParamWrap">
    @foreach($katalog->sertifikats as $s)
    <div class="modal fade" id="modalHapusParam{{ $s->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content border-0 shadow text-center p-3" style="font-size: 0.82rem; border-radius: 8px;">
                <div class="pt-2 pb-1">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #fff8e6; color: #f0ad4e; font-size: 24px; border: 2px solid #ffeeba;">
                        <i class="fas fa-exclamation"></i>
                    </div>
                </div>
                <div class="modal-body px-2 py-2">
                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Apakah Anda yakin?</h5>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">
                        Parameter <strong>{{ $s->parameterUji->nama_parameter ?? 'ini' }}</strong> akan dihapus dari sertifikat botol ini.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                    <form action="{{ route('crm-katalog.destroy-sertifikat', [$katalog->id, $s->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm fw-semibold rounded-2">Ya, Hapus!</button>
                    </form>
                    <button type="button" class="btn btn-secondary btn-sm fw-semibold rounded-2" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="modal fade" id="addParamModal" tabindex="-1" aria-labelledby="addParamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('crm-katalog.store-sertifikat', $katalog->id) }}" method="POST" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white py-2 px-3" style="background-color: #1b3152;">
                    <h5 class="modal-title" id="addParamModalLabel" style="font-size: 0.9rem;">Tambah Parameter ke Botol</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Parameter Uji <span class="text-danger">*</span></label>
                        <select name="parameter_uji_id" id="selectParameterUji" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Parameter --</option>
                            @foreach($parameterList as $p)
                                <option value="{{ $p->parameter_uji_id }}">{{ $p->nama_parameter }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-12 col-sm-6 mb-2">
                            <label class="form-label">True Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.0001" class="form-control form-control-sm" name="cert_value" required placeholder="Contoh: 6500" inputmode="decimal">
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <label class="form-label">Ketidakpastian (±) <span class="text-danger">*</span></label>
                            <input type="number" step="0.0001" class="form-control form-control-sm" name="cert_u" required placeholder="Contoh: 50" inputmode="decimal">
                        </div>
                    </div>
                    <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i>True Value dan ketidakpastian akan menjadi acuan batas mutlak saat analis memasukkan hasil uji harian.</p>
                </div>
                <div class="modal-footer py-2 px-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-corporate-blue btn-sm fw-semibold">Simpan ke Botol</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#selectParameterUji').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('#addParamModal')
    });
    document.getElementById('addParamModal').addEventListener('hidden.bs.modal', function () {
        this.querySelector('form').reset();
        $('#selectParameterUji').val('').trigger('change.select2');
    });
});
</script>
@endsection