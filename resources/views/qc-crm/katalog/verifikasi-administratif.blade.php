@extends('layouts.app')
@section('title', 'Verifikasi Administratif - ' . $katalog->nomor_lot)

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.show', $katalog->id) }}" class="text-decoration-none">{{ $katalog->nomor_lot }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Verifikasi Administratif</li>
    </x-qc-breadcrumb>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-clipboard-check text-primary me-2"></i>Verifikasi Administratif</h2>
        <p class="text-muted mb-0">Botol: <strong>{{ $katalog->nomor_lot }}</strong> — {{ $katalog->nama_produk }}</p>
    </div>

    @if($katalog->coa_file)
        <a href="{{ asset('storage/' . $katalog->coa_file) }}" target="_blank" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fas fa-file-pdf me-1"></i> Lihat Sertifikat CoA
        </a>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('crm-katalog.verifikasi-administratif.store', $katalog->id) }}" method="POST">
                @csrf

                <h6 class="fw-bold mb-3">Checklist</h6>

                @php
                    $items = [
                        'segel_utuh' => 'Segel botol masih utuh',
                        'fisik_baik' => 'Kondisi fisik botol baik (tidak retak/bocor)',
                        'label_sesuai' => 'Label botol sesuai dengan sertifikat CoA',
                        'belum_kadaluarsa' => 'Belum melewati tanggal kadaluarsa',
                    ];
                @endphp

                @foreach($items as $key => $label)
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ $label }}</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="checklist[{{ $key }}]" id="{{ $key }}_ya" value="1" required>
                                <label class="form-check-label" for="{{ $key }}_ya">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="checklist[{{ $key }}]" id="{{ $key }}_tidak" value="0" required>
                                <label class="form-check-label" for="{{ $key }}_tidak">Tidak</label>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mb-3">
                    <label class="form-label fw-bold small">Catatan (opsional)</label>
                    <textarea name="catatan" class="form-control" rows="3"></textarea>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label fw-bold">Keputusan</label>
                    <select name="keputusan" class="form-select" required>
                        <option value="">-- Pilih Keputusan --</option>
                        <option value="lolos">Lolos — Lanjut ke Verifikasi Teknis</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Verifikasi</button>
            </form>
        </div>
    </div>
</div>
@endsection