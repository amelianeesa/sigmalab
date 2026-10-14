@extends('layouts.app')
@section('title', 'Arsip - Pengadaan')

@section('content')

<style>
    :root {
        --brand-navy: #1b3a5c;
        --brand-navy-light: #2c5282;
        --navy-soft: #eef2f8;
        --hijau: #198754;
        --merah: #dc3545;
        --merah-soft: #f8d7da;
    }

    .arsip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .arsip-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .arsip-header-actions form {
        margin: 0;
    }
    .arsip-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.2rem;
    }
    .arsip-breadcrumb {
        font-size: 0.75rem;
        margin-bottom: 0;
    }

    .btn-brand-standard {
        background-color: var(--brand-navy);
        border-color: var(--brand-navy);
        color: #ffffff;
        font-size: 0.8rem;
        padding: 0.35rem 1rem;
        border-radius: 0.375rem;
        transition: transform 0.15s, background-color 0.15s;
    }
    .btn-brand-standard:hover {
        background-color: var(--brand-navy-light);
        border-color: var(--brand-navy-light);
        color: #ffffff;
        transform: translateY(-2px);
    }
    .btn-outline-brand {
        background-color: #ffffff;
        border: 1px solid var(--brand-navy);
        color: var(--brand-navy);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.8rem;
        border-radius: 0.375rem;
        transition: transform 0.15s, background-color 0.15s, color 0.15s;
    }
    .btn-outline-brand:hover {
        background-color: var(--brand-navy);
        color: #ffffff;
        transform: translateY(-2px);
    }
    .btn-bukti-kecil {
        display: inline-flex;
        align-items: center;
        background-color: #ffffff;
        border: 1px solid var(--brand-navy);
        color: var(--brand-navy);
        font-size: 0.62rem;
        font-weight: 600;
        line-height: 1.2;
        padding: 0.15rem 0.45rem;
        border-radius: 0.3rem;
        text-decoration: none;
        transition: background-color 0.15s, color 0.15s;
    }
    .btn-bukti-kecil:hover {
        background-color: var(--brand-navy);
        color: #ffffff;
    }
    .btn-header-aksi {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35rem 1rem;
        border-radius: 0.375rem;
        transition: transform 0.15s;
    }
    .btn-header-aksi:hover {
        transform: translateY(-2px);
    }
    .btn-danger-standard {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.8rem;
        border-radius: 0.375rem;
        transition: transform 0.15s;
    }
    .btn-danger-standard:hover {
        transform: translateY(-2px);
    }

    .arsip-card {
        border-radius: 8px;
    }
    .scroll-hint-arsip {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }
    .arsip-table {
        width: 100%;
        font-size: 0.7rem;
    }
    .arsip-table thead th {
        background-color: var(--brand-navy) !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
        font-size: 0.7rem;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        padding: 0.45rem 0.3rem;
    }
    .arsip-table tbody td {
        vertical-align: middle;
        padding: 0.4rem 0.35rem;
        color: #334155;
        word-break: break-word;
    }
    .arsip-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .arsip-barang {
        font-weight: 700;
        color: #212529;
        display: block;
    }
    .arsip-alasan {
        color: #64748b;
        font-size: 0.68rem;
    }
    .arsip-foto-badge {
        display: inline-block;
        margin-top: 0.3rem;
        font-size: 0.62rem;
        font-weight: 600;
        padding: 0.3em 0.6em;
        border-radius: 0.375rem;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: var(--brand-navy);
        text-decoration: none;
    }
    .arsip-foto-badge:hover {
        background-color: var(--brand-navy);
        border-color: var(--brand-navy);
        color: #ffffff;
    }

    .arsip-col-no { width: 3%; }
    .arsip-col-barang { width: 17%; }
    .arsip-col-tanggal { width: 8%; }
    .arsip-col-target { width: 8%; }
    .arsip-col-pemohon { width: 9%; }
    .arsip-col-jumlah { width: 7%; }
    .arsip-col-status { width: 17%; }
    .arsip-col-bukti { width: 13%; }
    .arsip-col-aksi { width: 11%; }

    .status-badge {
        display: inline-block;
        font-size: 0.64rem;
        font-weight: 600;
        padding: 0.4em 0.7em;
        border-radius: 50rem;
        line-height: 1.2;
    }
    .st-selesai { background-color: var(--hijau); color: #ffffff; }
    .st-tolak { background-color: var(--merah); color: #ffffff; }
    .st-batal { background-color: #374151; color: #ffffff; }

    .kotak-catatan {
        font-size: 0.66rem;
        border-radius: 0.375rem;
        padding: 0.3rem 0.45rem;
        margin-top: 0.35rem;
        text-align: left;
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

    .arsip-bukti {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        align-items: center;
        font-size: 0.68rem;
    }
    .arsip-bukti-nama {
        font-weight: 700;
        color: #212529;
    }
    .arsip-bukti-waktu {
        color: #64748b;
        font-size: 0.62rem;
    }
    .arsip-actions {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .arsip-actions form {
        margin: 0;
    }
    .arsip-actions .btn {
        width: 100%;
        font-size: 0.66rem;
        padding: 0.28rem 0.4rem;
        white-space: nowrap;
    }

    .swal2-popup {
        font-size: 0.85rem !important;
        padding: 1.5rem 1.25rem 1.75rem !important;
        border-radius: 1rem !important;
        width: 30em !important;
    }
    .swal2-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        padding: 0 !important;
        margin-bottom: 0.4rem !important;
        color: #212529 !important;
    }
    .swal2-html-container {
        font-size: 0.85rem !important;
        color: #6c757d !important;
        margin: 0.4rem 0.25rem 0 !important;
    }
    .swal2-icon {
        width: 3.6em !important;
        height: 3.6em !important;
        margin: 0.3em auto 0.7em !important;
    }
    .swal2-icon .swal2-icon-content {
        font-size: 2em !important;
    }
    .swal2-actions {
        margin-top: 1.35rem !important;
        gap: 0.75rem !important;
    }
    .swal2-styled {
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        padding: 0.55rem 1.3rem !important;
        border-radius: 0.4rem !important;
        white-space: nowrap !important;
        line-height: 1.3 !important;
    }

    @media (max-width: 991.98px) {
        .scroll-hint-arsip {
            display: block;
        }
        .arsip-table {
            min-width: 980px;
        }
        .arsip-table thead th {
            white-space: nowrap;
        }
        .arsip-actions {
            min-width: 130px;
        }
    }

    @media (max-width: 767.98px) {
        .arsip-header {
            flex-direction: column;
            align-items: stretch;
        }
        .arsip-header-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .arsip-header-actions form,
        .arsip-header-actions .btn {
            width: 100%;
        }
        .arsip-header-actions > :last-child:nth-child(odd) {
            grid-column: 1 / -1;
        }
    }
</style>

<div class="container-fluid px-3 px-md-4 pt-0 pb-4">
    <div class="arsip-header">
        <div>
            <h5 class="arsip-title">Arsip Pengajuan Pengadaan</h5>
            <ol class="breadcrumb arsip-breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pengadaan.index') }}" class="text-decoration-none text-primary">Pengadaan Barang & Bahan</a></li>
                <li class="breadcrumb-item text-muted active">Arsip Pengadaan</li>
            </ol>
        </div>
        <div class="arsip-header-actions">
            @if($pengadaans->count() > 0)
                <form id="form-restore-semua" action="{{ route('pengadaan.restoreSemuaArsip') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-outline-brand btn-header-aksi" id="btn-restore-semua">
                        <i class="fas fa-eye me-1"></i> Tampilkan Semua
                    </button>
                </form>

                <form id="form-hapus-semua" action="{{ route('pengadaan.destroySemuaArsip') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-header-aksi" id="btn-hapus-semua">
                        <i class="fas fa-trash-alt me-1"></i> Hapus Semua
                    </button>
                </form>
            @endif

            <a href="{{ route('pengadaan.index') }}" class="btn btn-brand-standard shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card arsip-card shadow-sm border-0">
        <div class="card-body p-2">
            <div class="scroll-hint-arsip"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0 arsip-table">
                    <thead>
                        <tr>
                            <th class="arsip-col-no">No</th>
                            <th class="arsip-col-barang">Nama Barang</th>
                            <th class="arsip-col-tanggal">Tanggal</th>
                            <th class="arsip-col-target">Target Waktu</th>
                            <th class="arsip-col-pemohon">Diajukan Oleh</th>
                            <th class="arsip-col-jumlah">Jumlah</th>
                            <th class="arsip-col-status">Status</th>
                            <th class="arsip-col-bukti">Bukti Terima</th>
                            <th class="arsip-col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengadaans as $p)
                            @php
                                $catatan = $p->catatan_approval ?? '';
                                $labelPenolak = null;
                                $alasanText = $catatan;
                                if (str_contains($catatan, 'Alasan:')) {
                                    [$labelPenolak, $alasanText] = explode('. Alasan:', $catatan, 2);
                                    $alasanText = trim($alasanText);
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>
                                    <span class="arsip-barang">{{ $p->barang ? $p->barang->nama_barang : 'Barang Dihapus' }}</span>
                                    <span class="arsip-alasan">{{ $p->alasan ?? 'Tidak ada catatan khusus' }}</span>
                                    @if($p->foto)
                                        <div>
                                            <a href="{{ asset($p->foto) }}" target="_blank" class="arsip-foto-badge">
                                                <i class="fas fa-image me-1"></i>Foto
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>

                                <td class="text-center">{{ $p->format_target_waktu }}</td>

                                <td class="text-center">{{ $p->pemohon ? $p->pemohon->username : '-' }}</td>

                                <td class="text-center">
                                    <strong>{{ (float) $p->jumlah_diminta }}</strong> {{ $p->barang ? $p->barang->satuan : '' }}
                                </td>

                                <td class="text-center">
                                    @if($p->status == 'ditolak')
                                        <span class="status-badge st-tolak">Ditolak</span>
                                        <div class="kotak-catatan kotak-tolak">
                                            @if($labelPenolak)
                                                <div class="fw-bold">{{ $labelPenolak }}</div>
                                                <div>Alasan: {{ $alasanText }}</div>
                                            @else
                                                <div>{{ $catatan }}</div>
                                            @endif
                                        </div>
                                    @elseif($p->status == 'batal')
                                        <span class="status-badge st-batal">Dibatalkan</span>
                                        <div class="kotak-catatan kotak-batal">
                                            @if($labelPenolak)
                                                <div class="fw-bold">{{ $labelPenolak }}</div>
                                                <div>Alasan: {{ $alasanText }}</div>
                                            @else
                                                <div>{{ $catatan }}</div>
                                            @endif
                                        </div>
                                    @elseif($p->status == 'selesai')
                                        <span class="status-badge st-selesai">Selesai (Stok Masuk)</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($p->nama_penerima || $p->waktu_diterima || $p->foto_diterima)
                                        <div class="arsip-bukti">
                                            <span class="arsip-bukti-nama">{{ $p->nama_penerima ?? '-' }}</span>
                                            @if($p->waktu_diterima)
                                                <span class="arsip-bukti-waktu">
                                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($p->waktu_diterima)->format('d M Y, H:i') }}
                                                </span>
                                            @endif
                                            @if($p->foto_diterima)
                                                <a href="{{ asset($p->foto_diterima) }}" target="_blank" class="btn-bukti-kecil">
                                                    <i class="fas fa-image me-1"></i> Lihat Bukti
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="arsip-actions">
                                        <form id="form-restore-{{ $p->permintaan_id }}" action="{{ route('pengadaan.restoreArsip', $p->permintaan_id) }}" method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-outline-brand btn-restore" data-id="{{ $p->permintaan_id }}" title="Kembalikan dari arsip">
                                                <i class="fas fa-undo me-1"></i> Batal Arsip
                                            </button>
                                        </form>

                                        <form id="form-hapus-{{ $p->permintaan_id }}" action="{{ route('pengadaan.destroyPermanen', $p->permintaan_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-danger-standard btn-hapus" data-id="{{ $p->permintaan_id }}" title="Hapus Permanen">
                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada data di dalam arsip.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-restore').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Pengajuan ini akan dikembalikan ke daftar pengadaan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1b3a5c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kembalikan!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('form-restore-' + id).submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-hapus').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Hapus permanen?',
                    text: 'Data arsip ini akan dihapus permanen dan tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            });
        });

        const btnRestoreSemua = document.getElementById('btn-restore-semua');
        if (btnRestoreSemua) {
            btnRestoreSemua.addEventListener('click', function () {
                Swal.fire({
                    title: 'Tampilkan semua arsip?',
                    text: 'Semua data arsip akan dikembalikan ke daftar pengadaan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1b3a5c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tampilkan Semua!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('form-restore-semua').submit();
                    }
                });
            });
        }

        const btnHapusSemua = document.getElementById('btn-hapus-semua');
        if (btnHapusSemua) {
            btnHapusSemua.addEventListener('click', function () {
                Swal.fire({
                    title: 'Hapus semua arsip?',
                    text: 'Semua data arsip akan dihapus permanen dan tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('form-hapus-semua').submit();
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection