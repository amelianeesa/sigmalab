@extends('layouts.app')
@section('title', 'Verifikasi Administratif - ' . $katalog->nomor_lot)

@section('content')
@include('qc-crm._qc-compact')

@php
    // Status kedaluwarsa botol (batas "segera" = 90 hari, samakan dengan halaman lain)
    $batasHariExpired = 90;
    $expState = 'none';
    $sisaHari = null;

    if ($katalog->tanggal_expired) {
        $sisaHari = (int) now()->startOfDay()->diffInDays($katalog->tanggal_expired->copy()->startOfDay(), false);
        $expState = $sisaHari < 0 ? 'kedaluwarsa' : ($sisaHari <= $batasHariExpired ? 'segera' : 'aktif');
    }

    $items = [
        'segel_utuh' => 'Segel botol masih utuh',
        'fisik_baik' => 'Kondisi fisik botol baik (tidak retak/bocor)',
        'label_sesuai' => 'Label botol sesuai dengan sertifikat CoA',
        'belum_kadaluarsa' => 'Belum melewati tanggal kadaluarsa',
    ];
@endphp

<style>
    .qc-page { padding: 4px 20px !important; }

    .qc-page .btn-corporate-blue {
        background-color: #1b3152 !important; border-color: #1b3152 !important; color: #fff !important;
    }
    .qc-page .btn-corporate-blue:hover, .qc-page .btn-corporate-blue:focus, .qc-page .btn-corporate-blue:active {
        background-color: #14253e !important; border-color: #14253e !important; color: #fff !important;
    }
    .qc-page .btn-outline-corporate { color: #1b3152 !important; border: 1px solid #1b3152 !important; background: #fff !important; }
    .qc-page .btn-outline-corporate:hover { background-color: #1b3152 !important; color: #fff !important; }

    .qc-page .select2-container--bootstrap-5 .select2-selection { font-size: 0.8rem; min-height: 0; padding: 0.2rem 0.4rem; }

    .checklist-item { border-bottom: 1px solid #eee; }
    .checklist-item:last-of-type { border-bottom: 0; }
    .checklist-item .btn-group .btn { min-width: 72px; }

    @media (max-width: 767.98px) {
        .qc-page { padding: 4px 10px !important; }
        .checklist-item .btn-group { width: 100%; }
        .checklist-item .btn-group .btn { flex: 1; padding-top: 0.4rem; padding-bottom: 0.4rem; }
    }
</style>

<div class="container-fluid qc-page qc-compact pb-4">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.show', $katalog->id) }}" class="text-decoration-none">{{ $katalog->nomor_lot }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Verifikasi Administratif</li>
    </x-qc-breadcrumb>

    <div class="mt-2 mb-3">
        <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
            <i class="fas fa-clipboard-check me-2" style="color: #1b3152;"></i>Verifikasi Administratif
        </h5>
        <div class="text-muted" style="font-size: 0.78rem;">Botol: <strong>{{ $katalog->nomor_lot }}</strong> — {{ $katalog->nama_produk }}</div>
    </div>

    {{-- NOTIFIKASI --}}
    @if($expState === 'kedaluwarsa')
        <div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 ps-3 pe-5 mb-2" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian!</strong> Botol ini sudah kedaluwarsa sejak {{ $katalog->tanggal_expired->format('d-m-Y') }}.
            Periksa kembali poin checklist "Belum melewati tanggal kadaluarsa".
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @elseif($expState === 'segera')
        <div class="alert alert-warning alert-dismissible fade show shadow-sm py-2 ps-3 pe-5 mb-2" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-clock me-1"></i> <strong>Perhatian!</strong> Botol ini akan kedaluwarsa dalam <strong>{{ $sisaHari }} hari</strong> ({{ $katalog->tanggal_expired->format('d-m-Y') }}).
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @endif

    <div class="row g-3">
        {{-- Info Botol --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2" style="color: #1b3152;"></i>Informasi Botol</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-2">
                        <tr>
                            <td class="text-muted" style="width: 40%;">Nomor Lot</td>
                            <td class="fw-bold"><span class="badge bg-primary">{{ $katalog->nomor_lot }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Produk</td>
                            <td class="fw-bold">{{ $katalog->nama_produk }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. Sertifikat</td>
                            <td class="fw-bold">{{ $katalog->nomor_sertifikat ?? '-' }}</td>
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
                    </table>

                    @if($katalog->coa_file)
                        <a href="{{ asset('storage/' . $katalog->coa_file) }}" target="_blank" class="btn btn-outline-corporate btn-sm w-100">
                            <i class="fas fa-file-pdf me-1"></i> Lihat Sertifikat CoA
                        </a>
                    @else
                        <div class="text-muted small"><i class="fas fa-info-circle me-1"></i>Belum ada file CoA yang diunggah.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Verifikasi --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-list-check me-2" style="color: #1b3152;"></i>Checklist Verifikasi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('crm-katalog.verifikasi-administratif.store', $katalog->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            @foreach($items as $key => $label)
                                <div class="checklist-item d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 py-2">
                                    <div class="fw-semibold" style="font-size: 0.8rem;">{{ $label }}</div>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="{{ $label }}">
                                        <input class="btn-check" type="radio" name="checklist[{{ $key }}]" id="{{ $key }}_ya" value="1" autocomplete="off" required {{ old('checklist.' . $key) === '1' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="{{ $key }}_ya"><i class="fas fa-check me-1"></i>Ya</label>

                                        <input class="btn-check" type="radio" name="checklist[{{ $key }}]" id="{{ $key }}_tidak" value="0" autocomplete="off" required {{ old('checklist.' . $key) === '0' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="{{ $key }}_tidak"><i class="fas fa-times me-1"></i>Tidak</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-control form-control-sm" rows="3" placeholder="Tulis catatan verifikasi jika ada...">{{ old('catatan') }}</textarea>
                        </div>

                        <hr>

                        <div class="mb-2">
                            <label class="form-label fw-bold">Keputusan <span class="text-danger">*</span></label>
                            <select name="keputusan" id="keputusan" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Keputusan --</option>
                                <option value="lolos" {{ old('keputusan') === 'lolos' ? 'selected' : '' }}>Lolos — Lanjut ke Verifikasi Teknis</option>
                                <option value="ditolak" {{ old('keputusan') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div id="warnChecklist" class="alert alert-warning py-2 mb-3 d-none" style="font-size: 0.78rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> Ada poin checklist yang dijawab <strong>Tidak</strong>, tetapi keputusan yang dipilih <strong>Lolos</strong>. Pastikan keputusan sudah sesuai.
                        </div>

                        <div class="d-grid d-md-flex justify-content-md-end gap-2 mt-3">
                            <a href="{{ route('crm-katalog.index') }}" class="btn btn-light border order-last order-md-first">Batal</a>
                            <button type="submit" class="btn btn-corporate-blue shadow-sm fw-semibold px-4"><i class="fas fa-save me-1"></i> Simpan Verifikasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $keputusan = $('#keputusan');
    $keputusan.select2({ theme: 'bootstrap-5', width: '100%' });

    const warn = document.getElementById('warnChecklist');

    function updateWarn() {
        const adaTidak = document.querySelectorAll('input[name^="checklist"][value="0"]:checked').length > 0;
        warn.classList.toggle('d-none', !(adaTidak && $keputusan.val() === 'lolos'));
    }

    document.querySelectorAll('.btn-check').forEach(r => r.addEventListener('change', updateWarn));
    $keputusan.on('change', updateWarn);
    updateWarn();
});
</script>
@endsection