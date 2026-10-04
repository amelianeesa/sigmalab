@extends('layouts.app')
@section('title', 'Daftar - Pengadaan')

@section('content')

<style>
    :root {
        --navy: #1b3152;
        --navy-dark: #14253e;
        --navy-soft: #eef2f8;
        --hijau: #198754;
        --hijau-dark: #157347;
        --hijau-soft: #d1e7dd;
        --merah: #dc3545;
        --merah-dark: #bb2d3b;
        --merah-soft: #f8d7da;
    }

    .pengadaan-header-row {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .pengadaan-header-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .modal-body {
        overflow: visible !important;
    }

    .text-navy { color: var(--navy) !important; }
    .bg-merah { background-color: var(--merah) !important; }

    .filter-select {
        position: relative;
        font-size: 0.78rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.78rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .filter-select-trigger:after {
        content: "";
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6c757d;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .filter-select.open .filter-select-trigger {
        border-color: var(--navy);
        box-shadow: 0 0 0 0.2rem rgba(27, 49, 82, 0.15);
    }
    .filter-select-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1060;
        margin-top: 2px;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        padding: 4px 0;
    }
    .filter-select.open .filter-select-options {
        display: block;
    }
    .filter-select-search-wrap {
        padding: 4px 8px 6px;
        border-bottom: 1px solid #eee;
    }
    .filter-select-search-input {
        width: 100%;
        font-size: 0.75rem;
        padding: 4px 6px;
        border: 1px solid #ced4da;
        border-radius: 0.3rem;
    }
    .filter-select-search-input:focus {
        border-color: var(--navy);
        outline: none;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }
    .filter-select-list {
        list-style: none;
        margin: 0;
        padding: 0;
        max-height: 220px;
        overflow-y: auto;
    }
    .filter-select-list li {
        padding: 6px 10px;
        font-size: 0.78rem;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .filter-select-list li:hover {
        background-color: var(--navy-soft);
    }
    .filter-select-list li.selected {
        background-color: var(--navy);
        color: #fff;
    }
    .filter-select-list li.d-none {
        display: none;
    }

    .btn-aksi,
    .btn-modal-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease, border-color .15s ease, color .15s ease;
    }
    .btn-aksi {
        min-height: 30px;
        padding: 0.28rem 0.65rem;
        font-size: 0.72rem;
    }
    .btn-aksi i {
        font-size: 0.72rem;
    }
    .btn-modal-aksi {
        min-width: 96px;
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
    }
    .btn-aksi:not(:disabled):hover,
    .btn-modal-aksi:not(:disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(27, 49, 82, 0.2);
    }
    .btn-aksi:not(:disabled):active,
    .btn-modal-aksi:not(:disabled):active {
        transform: none;
        box-shadow: none;
    }

    .btn-navy {
        background-color: var(--navy);
        border-color: var(--navy);
        color: #fff;
    }
    .btn-navy:hover, .btn-navy:focus, .btn-navy:active {
        background-color: var(--navy-dark);
        border-color: var(--navy-dark);
        color: #fff;
    }
    .btn-hijau {
        background-color: var(--hijau);
        border-color: var(--hijau);
        color: #fff;
    }
    .btn-hijau:hover, .btn-hijau:focus, .btn-hijau:active {
        background-color: var(--hijau-dark);
        border-color: var(--hijau-dark);
        color: #fff;
    }
    .btn-merah {
        background-color: var(--merah);
        border-color: var(--merah);
        color: #fff;
    }
    .btn-merah:hover, .btn-merah:focus, .btn-merah:active {
        background-color: var(--merah-dark);
        border-color: var(--merah-dark);
        color: #fff;
    }
    .btn-outline-navy {
        background-color: transparent;
        border-color: var(--navy);
        color: var(--navy);
    }
    .btn-outline-navy:hover, .btn-outline-navy:focus, .btn-outline-navy.show, .btn-outline-navy:active {
        background-color: var(--navy) !important;
        border-color: var(--navy) !important;
        color: #fff !important;
    }
    .btn-outline-hijau {
        background-color: transparent;
        border-color: var(--hijau);
        color: var(--hijau);
    }
    .btn-outline-hijau:hover, .btn-outline-hijau:focus, .btn-outline-hijau:active {
        background-color: var(--hijau) !important;
        border-color: var(--hijau) !important;
        color: #fff !important;
    }
    .btn-outline-merah {
        background-color: transparent;
        border-color: var(--merah);
        color: var(--merah);
    }
    .btn-outline-merah:hover, .btn-outline-merah:focus, .btn-outline-merah:active {
        background-color: var(--merah) !important;
        border-color: var(--merah) !important;
        color: #fff !important;
    }
    .btn-secondary-soft {
        background-color: #eef0f3;
        border-color: #eef0f3;
        color: #4b5563;
    }
    .btn-secondary-soft:hover, .btn-secondary-soft:focus, .btn-secondary-soft:active {
        background-color: #dde1e7;
        border-color: #dde1e7;
        color: #374151;
    }
    .btn-terkunci,
    .btn-terkunci:disabled {
        background-color: #eef0f3;
        border-color: #dde1e7;
        color: #9aa3af;
        opacity: 1;
        cursor: not-allowed;
        pointer-events: none;
    }
    .wrap-terkunci {
        cursor: not-allowed;
    }

    .aksi-pair {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.35rem;
    }
    .aksi-pair > * {
        min-width: 0;
        margin: 0;
    }
    .aksi-pair .btn-aksi {
        width: 100%;
        height: 100%;
        min-height: 32px;
    }

    .dropdown-menu {
        padding: 4px;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
    }
    .dropdown-menu .dropdown-item {
        border-radius: 0.25rem;
        transition: background-color .15s ease, padding-left .15s ease;
    }
    .dropdown-menu .dropdown-item.item-navy {
        color: var(--navy);
    }
    .dropdown-menu .dropdown-item.item-navy:hover {
        background-color: var(--navy-soft);
        padding-left: 1.1rem;
    }
    .dropdown-menu .dropdown-item.item-merah {
        color: var(--merah);
    }
    .dropdown-menu .dropdown-item.item-merah:hover {
        background-color: var(--merah-soft);
        color: var(--merah-dark);
        padding-left: 1.1rem;
    }

    .status-badge {
        display: inline-block;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 0.35em 0.7em;
        border-radius: 50rem;
        line-height: 1.2;
    }
    .st-tunggu { background-color: #eef0f3; color: #4b5563; }
    .st-ga { background-color: #fff3d6; color: #8a5a00; }
    .st-setuju { background-color: var(--hijau-soft); color: #0f5132; }
    .st-proses { background-color: var(--navy); color: #fff; }
    .st-beli { background-color: #e3e9f3; color: var(--navy); }
    .st-selesai { background-color: var(--hijau); color: #fff; }
    .st-tolak { background-color: var(--merah); color: #fff; }
    .st-batal { background-color: #374151; color: #fff; }

    .kotak-catatan {
        font-size: 0.7rem;
        border-radius: 0.375rem;
        padding: 0.35rem 0.5rem;
    }
    .kotak-tolak {
        background: var(--merah-soft);
        border: 1px solid #f1aeb5;
        color: var(--merah);
    }
    .kotak-batal {
        background: #f3f4f6;
        border: 1px solid #dde1e7;
        color: #374151;
    }
    .kotak-po {
        background: var(--navy-soft);
        border: 1px solid #d5deee;
        color: var(--navy);
    }

    .modal-dialog-pengadaan {
        max-width: 540px;
        margin-left: auto;
        margin-right: auto;
    }
    .modal-dialog-pengadaan.modal-dialog-konfirmasi {
        max-width: 430px;
    }
    .modal-dialog-pengadaan .modal-content {
        border: 0;
        border-radius: 0.6rem;
        box-shadow: 0 0.6rem 1.8rem rgba(0, 0, 0, 0.22);
        overflow: visible;
    }
    .modal-dialog-pengadaan .modal-header {
        padding: 0.9rem 1.4rem !important;
        border-radius: 0.6rem 0.6rem 0 0;
    }
    .modal-dialog-pengadaan .modal-title {
        font-size: 1.1rem !important;
        font-weight: 700;
    }
    .modal-dialog-pengadaan .modal-body {
        padding: 1.25rem 1.4rem !important;
    }
    .modal-dialog-pengadaan .modal-footer {
        padding: 0.85rem 1.4rem !important;
        gap: 0.5rem;
        border-radius: 0 0 0.6rem 0.6rem;
    }
    .modal-dialog-pengadaan .form-label {
        font-size: 0.88rem !important;
        margin-bottom: 0.4rem !important;
    }
    .modal-dialog-pengadaan .form-control,
    .modal-dialog-pengadaan .input-group-text {
        font-size: 0.92rem;
        padding: 0.5rem 0.75rem;
    }
    .modal-dialog-pengadaan .filter-select,
    .modal-dialog-pengadaan .filter-select-trigger,
    .modal-dialog-pengadaan .filter-select-list li {
        font-size: 0.9rem;
    }
    .modal-dialog-pengadaan .filter-select-trigger {
        padding: 0.5rem 0.75rem;
    }
    .modal-dialog-pengadaan .filter-select-list li {
        padding: 0.5rem 0.75rem;
    }
    .modal-dialog-pengadaan .filter-select-search-input {
        font-size: 0.88rem;
        padding: 0.4rem 0.6rem;
    }
    .modal-dialog-pengadaan .btn-modal-aksi {
        min-width: 112px;
        padding: 0.5rem 1.2rem;
        font-size: 0.88rem;
    }

    .flatpickr-input {
        font-size: 0.78rem;
    }
    .flatpickr-calendar {
        font-size: 0.8rem;
    }

    .scroll-hint-pengadaan {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }

    .tabel-pengadaan {
        font-size: 0.75rem;
        border-color: #dee2e6;
    }
    .tabel-pengadaan thead th {
        background-color: var(--navy) !important;
        color: #fff !important;
        border-right: 1px solid rgba(255, 255, 255, 0.35) !important;
        font-weight: 600;
        white-space: nowrap;
    }
    .tabel-pengadaan thead th:last-child {
        border-right: 0 !important;
    }
    .kolom-aksi {
        min-width: 150px;
    }

    @media (max-width: 768px) {
        .tabel-pengadaan {
            min-width: 860px;
        }
        .scroll-hint-pengadaan {
            display: block;
        }
        .btn-aksi {
            min-height: 28px;
            padding: 0.25rem 0.45rem;
            font-size: 0.68rem;
            gap: 0.25rem;
        }
        .btn-aksi i {
            font-size: 0.68rem;
        }
        .btn-modal-aksi {
            min-width: 80px;
            padding: 0.34rem 0.8rem;
            font-size: 0.78rem;
        }
        .modal-footer {
            flex-wrap: nowrap;
        }
    }

    @media (max-width: 576px) {
        .pengadaan-header-row {
            flex-direction: column;
            align-items: stretch !important;
        }
        .pengadaan-header-actions {
            width: 100%;
        }
        .pengadaan-header-actions button {
            flex: 1;
        }
    }
</style>

<div class="container-fluid px-3 px-md-4 pt-0 pb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 pengadaan-header-row">
        <div>
            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.2rem;">Pengadaan Bahan / Barang</h5>
            <ol class="breadcrumb mb-0" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="{{ route('barang.index') }}" class="text-decoration-none text-navy">Inventori Barang & Bahan</a></li>
                <li class="breadcrumb-item text-muted active">Pengadaan Barang & Bahan</li>
            </ol>
        </div>
        <div class="d-flex gap-2 pengadaan-header-actions">
            @if(in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::GA_OFFICER->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value, 'GA', 'GA_OFFICER']))
                <button class="btn btn-aksi btn-outline-hijau" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            @endif

            @php
                $allowedRoles = ['GA', 'Analis Lab', 'Koordinator Laboratorium', 'Admin Aplikasi', \App\Enums\PeranPengguna::GA_OFFICER->value];
                $currentRole = Auth::user()->role->nama_role ?? '';
            @endphp
            @if(in_array($currentRole, $allowedRoles))
                <button class="btn btn-aksi btn-navy" data-bs-toggle="modal" data-bs-target="#tambahPengadaanModal">
                    <i class="fas fa-plus"></i> Ajukan Pengadaan
                </button>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-2">
            <div class="scroll-hint-pengadaan"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 tabel-pengadaan">
                    <thead>
                        <tr class="text-center">
                            <th width="4%" class="py-2">No</th>
                            <th class="py-2">Nama Barang</th>
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Target Waktu</th>
                            <th class="py-2">Diajukan Oleh</th>
                            <th class="py-2">Jumlah</th>
                            <th class="py-2">Status</th>
                            <th width="16%" class="py-2 kolom-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengadaans as $p)
                            @php
                                $roleUser = Auth::user()->role->nama_role ?? '';
                                $isKoor = in_array($roleUser, ['Koordinator Lab', 'Koordinator Laboratorium']);
                                $isGa = in_array($roleUser, [\App\Enums\PeranPengguna::GA_OFFICER->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value, 'GA', 'GA_OFFICER']);
                                $isAdminAplikasi = $roleUser === \App\Enums\PeranPengguna::ADMIN_APLIKASI->value;

                                $statusProses = ['diproses', 'diproses_po', 'pembelian'];
                                $statusBelumProses = ['diajukan', 'menunggu_koordinator', 'menunggu_ga', 'disetujui'];

                                $catatan = $p->catatan_approval ?? '';
                                $labelPenolak = null;
                                $alasanText = $catatan;
                                if (str_contains($catatan, 'Alasan:')) {
                                    [$labelPenolak, $alasanText] = explode('. Alasan:', $catatan, 2);
                                    $alasanText = trim($alasanText);
                                }
                            @endphp
                            <tr id="pengadaan-{{ $p->permintaan_id }}">
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>
                                    <span class="fw-semibold">{{ $p->barang ? $p->barang->nama_barang : 'Barang Dihapus' }}</span><br>
                                    <span class="text-muted" style="font-size:0.7rem;">{{ $p->alasan ?? 'Tidak ada catatan khusus' }}</span>
                                    @if($p->foto)
                                    <div class="mt-1">
                                        <a href="{{ asset($p->foto) }}" target="_blank" class="badge bg-light text-navy border text-decoration-none" style="font-size:0.65rem;">
                                            <i class="fas fa-image me-1"></i>Foto
                                        </a>
                                    </div>
                                    @endif
                                </td>

                                <td class="text-nowrap text-center">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>

                                <td class="text-center">
                                    <span class="text-dark small fw-medium">{{ $p->format_target_waktu }}</span>
                                </td>

                                <td class="text-center">{{ $p->pemohon ? $p->pemohon->username : '-' }}</td>

                                <td class="text-center fw-bold text-navy">
                                    {{ (float) $p->jumlah_diminta }} <span class="text-muted fw-normal" style="font-size:0.7rem;">{{ $p->barang ? $p->barang->satuan : '' }}</span>
                                </td>

                                <td class="text-center" style="min-width:170px;">
                                    @if($p->status == 'menunggu_koordinator' || $p->status == 'diajukan')
                                        <span class="status-badge st-tunggu">Menunggu Koordinator</span>
                                    @elseif($p->status == 'menunggu_ga')
                                        <span class="status-badge st-ga">Menunggu GA</span>
                                    @elseif($p->status == 'disetujui')
                                        <span class="status-badge st-setuju">Disetujui GA</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="status-badge st-tolak">Ditolak</span>
                                        <div class="mt-1 text-start kotak-catatan kotak-tolak">
                                            @if($labelPenolak)
                                                <div class="fw-bold">{{ $labelPenolak }}</div>
                                                <div>Alasan: {{ $alasanText }}</div>
                                            @else
                                                <div>{{ $catatan }}</div>
                                            @endif
                                        </div>
                                    @elseif($p->status == 'batal')
                                        <span class="status-badge st-batal">Dibatalkan</span>
                                        <div class="mt-1 text-start kotak-catatan kotak-batal">
                                            @if($labelPenolak)
                                                <div class="fw-bold">{{ $labelPenolak }}</div>
                                                <div>Alasan: {{ $alasanText }}</div>
                                            @else
                                                <div>{{ $catatan }}</div>
                                            @endif
                                        </div>
                                    @elseif($p->status == 'diproses_po' || $p->status == 'diproses')
                                        <span class="status-badge st-proses">PO</span>
                                        @if($p->catatan_po)
                                            <div class="mt-1 text-start kotak-catatan kotak-po">
                                                <i class="fas fa-info-circle me-1"></i> <b>Catatan:</b> {{ $p->catatan_po }}
                                            </div>
                                        @endif
                                    @elseif($p->status == 'pembelian')
                                        <span class="status-badge st-beli">Proses</span>
                                        @if($p->catatan_po)
                                            <div class="mt-1 text-start kotak-catatan kotak-po">
                                                <i class="fas fa-info-circle me-1"></i> <b>Catatan:</b> {{ $p->catatan_po }}
                                            </div>
                                        @endif
                                    @elseif($p->status == 'selesai')
                                        <span class="status-badge st-selesai">Selesai (Stok Masuk)</span>
                                    @endif
                                </td>

                                <td class="kolom-aksi">
                                    <div class="d-flex flex-column gap-1">
                                        @if($isKoor && ($p->status == 'menunggu_koordinator' || $p->status == 'diajukan'))
                                            <div class="aksi-pair">
                                                <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="menunggu_ga">
                                                    <button class="btn btn-aksi btn-hijau w-100"><i class="fas fa-check"></i> Setujui</button>
                                                </form>
                                                <button type="button" class="btn btn-aksi btn-merah w-100" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->permintaan_id }}">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        @endif

                                        @if($isGa && $p->status == 'menunggu_ga')
                                            <div class="aksi-pair">
                                                <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="disetujui">
                                                    <button class="btn btn-aksi btn-hijau w-100"><i class="fas fa-check"></i> Setujui</button>
                                                </form>
                                                <button type="button" class="btn btn-aksi btn-merah w-100" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->permintaan_id }}">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        @endif

                                        @if($isGa && in_array($p->status, $statusProses))
                                            <div class="dropdown">
                                                <button class="btn btn-aksi btn-outline-navy dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm small">
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1 item-navy fw-semibold" data-bs-toggle="modal" data-bs-target="#updateProsesModal{{ $p->permintaan_id }}">
                                                            <i class="fas fa-edit me-2"></i> Update Progres
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1 item-merah fw-semibold" data-bs-toggle="modal" data-bs-target="#modalBatalGa{{ $p->permintaan_id }}">
                                                            <i class="fas fa-ban me-2"></i> Batalkan Proses
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif

                                        @if($isGa && $p->status == 'disetujui')
                                            <button type="button" class="btn btn-aksi btn-navy w-100" data-bs-toggle="modal" data-bs-target="#modalMetodeGa{{ $p->permintaan_id }}">
                                                <i class="fas fa-tasks"></i> Proses Pengadaan
                                            </button>

                                            <div class="modal fade" id="modalMetodeGa{{ $p->permintaan_id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan">
                                                    <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="modal-content text-start">
                                                        @csrf
                                                        <input type="hidden" name="status" value="diproses">

                                                        <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                                                            <h6 class="modal-title mb-0 fs-6"><i class="fas fa-shopping-cart me-1"></i>Pilih Metode Pengadaan</h6>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body py-2">
                                                            <div class="mb-2">
                                                                <label class="form-label fw-bold small">Metode Penanganan <span class="text-danger">*</span></label>
                                                                <div class="filter-select" data-estimasi-id="{{ $p->permintaan_id }}">
                                                                    <input type="hidden" name="metode_proses" required>
                                                                    <button type="button" class="filter-select-trigger">-- Pilih Metode --</button>
                                                                    <div class="filter-select-options">
                                                                        <ul class="filter-select-list">
                                                                            <li data-value="">-- Pilih Metode --</li>
                                                                            <li data-value="PO">Purchase Order (PO)</li>
                                                                            <li data-value="Pembelian">Pembelian Langsung</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-2" id="divEstimasi{{ $p->permintaan_id }}">
                                                                <label class="form-label fw-bold small" id="labelEstimasi{{ $p->permintaan_id }}">Catatan / Estimasi</label>
                                                                <textarea name="catatan_po" class="form-control form-control-sm" rows="2" placeholder="Contoh: Estimasi tiba 3 hari..."></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer bg-light py-2">
                                                            <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-navy btn-modal-aksi">Simpan & Proses</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="modal fade" id="modalTolak{{ $p->permintaan_id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan">
                                                <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="modal-content text-start border-0 shadow">
                                                    @csrf
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <div class="modal-header bg-merah text-white py-3 px-4">
                                                        <h6 class="modal-title mb-0 fs-5"><i class="fas fa-times-circle me-2"></i>Penolakan Pengadaan</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body px-4 py-3">
                                                        <label class="form-label fw-bold mb-2">Alasan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="catatan_approval" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan..." style="resize: none;"></textarea>
                                                    </div>
                                                    <div class="modal-footer bg-light py-3 px-4">
                                                        <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-merah btn-modal-aksi">Kirim</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        @if($p->status == 'selesai' || $p->foto_diterima)
                                            <div class="p-2 border rounded bg-light text-start" style="font-size:0.7rem;">
                                                <span class="fw-bold text-dark">Penerima: {{ $p->nama_penerima ?? '-' }}</span><br>
                                                @if($p->waktu_diterima)
                                                    <span class="text-muted" style="font-size:0.65rem;">
                                                        <i class="fas fa-clock me-1 text-secondary"></i>{{ \Carbon\Carbon::parse($p->waktu_diterima)->format('d M Y, H:i') }}
                                                    </span><br>
                                                @endif
                                                @if($p->foto_diterima)
                                                    <a href="{{ asset($p->foto_diterima) }}" target="_blank" class="btn btn-aksi btn-outline-navy mt-1 w-100">
                                                        <i class="fas fa-image"></i> Lihat Bukti
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif(in_array($p->status, $statusProses))
                                            <button type="button" class="btn btn-aksi btn-hijau w-100" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $p->permintaan_id }}">
                                                <i class="fas fa-camera"></i> Konfirmasi Terima
                                            </button>

                                            <div class="modal fade" id="modalTerima{{ $p->permintaan_id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan">
                                                    <form action="{{ route('pengadaan.konfirmasiTerima', $p->permintaan_id) }}" method="POST" enctype="multipart/form-data" class="modal-content text-start">
                                                        @csrf
                                                        <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                                                            <h6 class="modal-title mb-0 fs-6"><i class="fas fa-box-open me-1"></i>Konfirmasi Diterima</h6>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-2">
                                                            <div class="mb-2">
                                                                <label class="form-label fw-bold small">Nama Penerima <span class="text-danger">*</span></label>
                                                                <input type="text" name="nama_penerima" class="form-control form-control-sm" required>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label fw-bold small">Bukti Foto <span class="text-danger">*</span></label>
                                                                <input type="file" name="foto_diterima" class="form-control form-control-sm" accept="image/*" capture="environment" required>
                                                            </div>
                                                            <div class="mb-1">
                                                                <label class="form-label fw-bold small">Tgl Expired (Opsional)</label>
                                                                <input type="text" name="tgl_exp" class="form-control form-control-sm flatpickr-date" placeholder="dd/mm/yyyy" autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light py-2">
                                                            <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-navy btn-modal-aksi">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @elseif(in_array($p->status, $statusBelumProses))
                                            <div class="wrap-terkunci" title="Tersedia setelah pengadaan masuk tahap PO atau Proses">
                                                <button type="button" class="btn btn-aksi btn-terkunci w-100" disabled>
                                                    <i class="fas fa-lock"></i> Konfirmasi Terima
                                                </button>
                                            </div>
                                        @endif

                                        @if(Auth::id() == $p->diajukan_oleh && in_array($p->status, ['diajukan', 'menunggu_koordinator']))
                                            <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-aksi btn-outline-merah w-100 btn-konfirmasi-aksi" data-pesan="Batalkan pengajuan pengadaan ini?" data-tombol="Ya, Batalkan!"><i class="fas fa-trash"></i> Batalkan</button>
                                            </form>
                                        @endif

                                        @if($isAdminAplikasi)
                                            <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-aksi btn-merah w-100 btn-konfirmasi-aksi" data-pesan="Data pengadaan ini akan dihapus permanen!" data-tombol="Ya, Hapus!"><i class="fas fa-trash-alt"></i> Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat pengajuan pengadaan barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($pengadaans as $p)
@if(in_array($p->status, ['diproses', 'diproses_po', 'pembelian']))
<div class="modal fade" id="updateProsesModal{{ $p->permintaan_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan">
        <form action="{{ route('pengadaan.update-progres', $p->permintaan_id) }}" method="POST" class="modal-content text-start">
            @csrf
            @method('PUT')
            <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                <h6 class="modal-title mb-0 fs-6"><i class="fas fa-edit me-1"></i>Update Catatan Progres</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <div class="mb-2">
                    <label class="form-label fw-bold small">Catatan / Keterangan:</label>
                    <textarea name="catatan_po" class="form-control form-control-sm" rows="3" required>{{ $p->catatan_po }}</textarea>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-navy btn-modal-aksi">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalBatalGa{{ $p->permintaan_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan">
        <form action="{{ route('pengadaan.batal-progres', $p->permintaan_id) }}" method="POST" class="modal-content text-start border-0 shadow">
            @csrf
            @method('PUT')
            <div class="modal-header bg-merah text-white py-3 px-4">
                <h6 class="modal-title mb-0 fs-5"><i class="fas fa-ban me-2"></i>Batalkan Pengadaan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <label class="form-label fw-bold mb-2">Alasan Pembatalan <span class="text-danger">*</span></label>
                <textarea name="alasan_batal" class="form-control" rows="4" required placeholder="Tuliskan alasan pembatalan..." style="resize: none;"></textarea>
            </div>
            <div class="modal-footer bg-light py-3 px-4">
                <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-merah btn-modal-aksi">Ya, Batalkan</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

<div class="modal fade" id="modalKonfirmasiAksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan modal-dialog-konfirmasi">
        <div class="modal-content border-0 shadow text-center p-3" style="font-size: 0.9rem; border-radius: 8px;">
            <div class="pt-2 pb-1">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: #eef2f8; color: #1b3152; font-size: 28px; border: 2px solid #d5deee;">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0" id="konfirmasiAksiPesan" style="font-size: 0.9rem;"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <button type="button" id="btnKonfirmasiAksi" class="btn btn-merah btn-modal-aksi">Ya</button>
                <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Tidak</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahPengadaanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan">
        <form action="{{ route('pengadaan.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                <h6 class="modal-title mb-0 fs-6">Form Pengajuan Pengadaan Bahan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2 px-3">
                <div class="mb-2">
                    <label class="form-label fw-bold small mb-1">Pilih Barang/Bahan <span class="text-danger">*</span></label>
                    <div class="filter-select" id="selectBarangId">
                        <input type="hidden" name="barang_id" required>
                        <button type="button" class="filter-select-trigger">-- Pilih Barang --</button>
                        <div class="filter-select-options">
                            <div class="filter-select-search-wrap">
                                <input type="text" class="filter-select-search-input" placeholder="Cari barang...">
                            </div>
                            <ul class="filter-select-list">
                                <li data-value="">-- Pilih Barang --</li>
                                @foreach($barangList as $b)
                                    @php
                                        $saldoAkhir = ($b->saldo_awal + $b->penerimaan) - $b->pengeluaran;
                                    @endphp
                                    <li data-value="{{ $b->barang_id }}">
                                        {{ $b->nama_barang }} (Stok saat ini: {{ $saldoAkhir }} {{ $b->satuan }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small mb-1">Jumlah Diminta <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.1" name="jumlah_diminta" class="form-control form-control-sm" required placeholder="Contoh: 100">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small mb-1">Target Batas Waktu Pengadaan <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <input type="number" name="target_tahun" class="form-control" min="0" placeholder="0">
                                <span class="input-group-text">Thn</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <input type="number" name="target_bulan" class="form-control" min="0" max="12" placeholder="0">
                                <span class="input-group-text">Bln</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <input type="number" name="target_hari" class="form-control" min="0" max="31" placeholder="0">
                                <span class="input-group-text">Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small mb-1">Catatan Kebutuhan / Spesifikasi Barang</label>
                    <textarea name="alasan" class="form-control form-control-sm" rows="2" style="resize: none;"></textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold small mb-1">Upload Foto / Referensi Barang <small class="text-muted">(Opsional)</small></label>
                    <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-navy btn-modal-aksi"><i class="fas fa-paper-plane"></i> Ajukan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="exportPdfModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-pengadaan">
        <form action="{{ route('pengadaan.exportPdf') }}" method="GET" target="_blank" class="modal-content">
            <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                <h6 class="modal-title mb-0 fs-6">Export Laporan Pengadaan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    $currentMonthNumPengadaan = (int) date('m');
                    $tahunSekarangPengadaan = (int) date('Y');
                @endphp
                <div class="mb-3">
                    <label class="form-label fw-bold small">Bulan</label>
                    <div class="filter-select">
                        <input type="hidden" name="bulan" value="{{ str_pad($currentMonthNumPengadaan, 2, '0', STR_PAD_LEFT) }}" required>
                        <button type="button" class="filter-select-trigger">{{ \Carbon\Carbon::create()->month($currentMonthNumPengadaan)->translatedFormat('F') }}</button>
                        <div class="filter-select-options">
                            <ul class="filter-select-list">
                                @for($i=1; $i<=12; $i++)
                                    <li data-value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" class="{{ $currentMonthNumPengadaan == $i ? 'selected' : '' }}">
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Tahun</label>
                    <div class="filter-select">
                        <input type="hidden" name="tahun" value="{{ $tahunSekarangPengadaan }}" required>
                        <button type="button" class="filter-select-trigger">{{ $tahunSekarangPengadaan }}</button>
                        <div class="filter-select-options">
                            <ul class="filter-select-list">
                                @for($i=$tahunSekarangPengadaan; $i>=2020; $i--)
                                    <li data-value="{{ $i }}" class="{{ $i == $tahunSekarangPengadaan ? 'selected' : '' }}">{{ $i }}</li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary-soft btn-modal-aksi" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-hijau btn-modal-aksi"><i class="fas fa-download"></i> Download PDF</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script>
    function toggleEstimasi(value, id) {
        const label = document.getElementById('labelEstimasi' + id);
        if (!label) return;

        if (value === 'PO') {
            label.innerText = 'Estimasi PO (Hari / Keterangan)';
        } else if (value === 'Pembelian') {
            label.innerText = 'Estimasi Pembelian Langsung';
        } else {
            label.innerText = 'Catatan / Estimasi';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        let formKonfirmasi = null;
        const modalKonfirmasiEl = document.getElementById('modalKonfirmasiAksi');
        const modalKonfirmasi = modalKonfirmasiEl ? new bootstrap.Modal(modalKonfirmasiEl) : null;

        document.querySelectorAll('.btn-konfirmasi-aksi').forEach(function (btn) {
            btn.addEventListener('click', function () {
                formKonfirmasi = this.closest('form');
                document.getElementById('konfirmasiAksiPesan').textContent = this.getAttribute('data-pesan');
                document.getElementById('btnKonfirmasiAksi').textContent = this.getAttribute('data-tombol');
                if (modalKonfirmasi) modalKonfirmasi.show();
            });
        });

        const btnKonfirmasiAksi = document.getElementById('btnKonfirmasiAksi');
        if (btnKonfirmasiAksi) {
            btnKonfirmasiAksi.addEventListener('click', function () {
                if (formKonfirmasi) formKonfirmasi.submit();
            });
        }

        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true
        });

        document.querySelectorAll('.filter-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.filter-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.filter-select-list li');
            const searchInput = wrapper.querySelector('.filter-select-search-input');
            const estimasiId = wrapper.getAttribute('data-estimasi-id');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.filter-select.open').forEach(function (other) {
                    if (other !== wrapper) other.classList.remove('open');
                });
                wrapper.classList.toggle('open');
                if (wrapper.classList.contains('open') && searchInput) {
                    searchInput.value = '';
                    options.forEach(function (o) { o.classList.remove('d-none'); });
                    setTimeout(function () { searchInput.focus(); }, 50);
                }
            });

            options.forEach(function (li) {
                li.addEventListener('click', function () {
                    hiddenInput.value = li.getAttribute('data-value');
                    trigger.textContent = li.textContent;
                    options.forEach(function (o) { o.classList.remove('selected'); });
                    li.classList.add('selected');
                    wrapper.classList.remove('open');
                    if (estimasiId) {
                        toggleEstimasi(li.getAttribute('data-value'), estimasiId);
                    }
                });
            });

            if (searchInput) {
                searchInput.addEventListener('click', function (e) { e.stopPropagation(); });
                searchInput.addEventListener('input', function () {
                    const q = searchInput.value.toLowerCase();
                    options.forEach(function (li) {
                        const match = li.textContent.toLowerCase().includes(q);
                        li.classList.toggle('d-none', !match);
                    });
                });
            }
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });
    });
</script>
@endpush